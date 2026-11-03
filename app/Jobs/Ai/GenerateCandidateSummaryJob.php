<?php

namespace App\Jobs\Ai;

use App\Models\AiCandidateSummary;
use App\Models\Application;
use App\Services\Ai\AiGatewayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateCandidateSummaryJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public int $applicationId)
    {
    }

    public function handle(AiGatewayService $aiGateway): void
    {
        $application = Application::with(['job', 'user.workExperiences', 'user.achievements'])->find($this->applicationId);
        if (!$application) {
            return;
        }

        $summary = AiCandidateSummary::firstOrNew(['application_id' => $application->id]);
        $summary->fill(['status' => 'processing'])->save();

        $response = $aiGateway->summarizeCandidate([
            'job_description' => $application->job?->description ?? '',
            'candidate_name' => $application->user?->name ?? 'Candidate',
            'candidate_profile' => [
                'job_title' => $application->job?->title,
                'summary' => $application->user?->user_summary,
                'skills' => $application->user?->skills
                    ? array_filter(array_map('trim', explode(',', $application->user->skills)))
                    : [],
                'work_experiences' => $application->user?->workExperiences?->map(fn ($exp) => [
                    'title' => $exp->title,
                    'company_name' => $exp->company_name,
                    'description' => $exp->description,
                ])->toArray() ?? [],
                'achievements' => $application->user?->achievements?->map(fn ($achievement) => [
                    'title' => $achievement->title,
                    'type' => $achievement->type,
                ])->toArray() ?? [],
            ],
        ]);

        if (!$response['ok']) {
            $summary->update([
                'status' => 'failed',
                'error_message' => $response['error'],
            ]);
            return;
        }

        $data = $response['data'];
        $summary->update([
            'pros_json' => $data['pros'] ?? [],
            'cons_json' => $data['cons'] ?? [],
            'summary_text' => $data['short_summary'] ?? null,
            'recommendation' => $data['recommendation'] ?? null,
            'model_version' => config('ai.model_version'),
            'status' => 'completed',
            'error_message' => null,
            'generated_at' => now(),
        ]);

        // Bust HR intelligence dashboard cache so fresh summary appears immediately
        cache()->forget("hr_intelligence_dashboard_" . $application->job?->created_by);
    }
}
