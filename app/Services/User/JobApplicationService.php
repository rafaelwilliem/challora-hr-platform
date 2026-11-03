<?php

namespace App\Services\User;

use App\Jobs\Ai\GenerateCvRatingJob;
use App\Jobs\Ai\GenerateCandidateSummaryJob;
use App\Models\JobPosting;
use App\Models\User;
use App\Enums\ApplicationStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class JobApplicationService
{
    public function getUserApplications(int $userId): LengthAwarePaginator
    {
        return User::findOrFail($userId)->applications()
            ->with('job')
            ->latest()
            ->paginate(10);
    }

    /**
     * @throws Exception
     */
    public function applyForJob(User $user, JobPosting $job): void
    {
        if ($user->applications()->where('job_id', $job->id)->exists()) {
            throw new Exception('You have already applied for this position.');
        }

        if ($job->deadline && $job->deadline->isPast()) {
            throw new Exception('This job posting is closed.');
        }

        $missingDocs = [];
        if (empty($user->cv_path)) $missingDocs[] = 'CV';
        if (empty($user->diploma_path)) $missingDocs[] = 'Diploma';
        if (empty($user->photo_path)) $missingDocs[] = 'Photo';

        if (!empty($missingDocs)) {
            throw new Exception('MISSING_DOCS:' . implode(', ', $missingDocs));
        }

        $application = $user->applications()->create([
            'job_id'       => $job->id,
            'cv_path'      => $user->cv_path,
            'diploma_path' => $user->diploma_path,
            'photo_path'   => $user->photo_path,
            'status'       => ApplicationStatus::PENDING,
        ]);

        cache()->forget("user_{$user->id}_applied_jobs");
        cache()->forget("hr_stats_dashboard_{$job->created_by}");

        // Automatically trigger AI scoring when a candidate applies
        GenerateCvRatingJob::dispatch($application->id);
        GenerateCandidateSummaryJob::dispatch($application->id);
    }
}
