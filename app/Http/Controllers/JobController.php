<?php

namespace App\Http\Controllers;

use App\Jobs\Ai\GenerateTopJobsJob;
use App\Models\AiUserJobRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    protected $searchService;

    public function __construct(\App\Services\JobSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $appliedJobIds = [];
        $savedJobIds = [];
        $isProfileComplete = true;
        $topJobRecommendationIds = [];

        if (Auth::check() && Auth::user()?->role === \App\Enums\UserRole::USER) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            $appliedJobIds = cache()->remember("user_{$user->id}_applied_jobs", now()->addMinutes(5), function () use ($user) {
                return $user->applications()->pluck('job_id')->toArray();
            });
            
            $savedJobIds = cache()->remember("user_{$user->id}_saved_jobs", now()->addMinutes(5), function () use ($user) {
                return $user->savedJobs()->pluck('job_postings.id')->toArray();
            });
            
            if (empty($user->cv_path) || empty($user->diploma_path) || empty($user->photo_path)) {
                $isProfileComplete = false;
            }

            $topJobRecommendationIds = AiUserJobRecommendation::where('user_id', $user->id)
                ->pluck('job_id')
                ->toArray();

            if (empty($topJobRecommendationIds)) {
                $dispatchLockKey = "user_{$user->id}_top_jobs_dispatch_lock";
                if (Cache::add($dispatchLockKey, true, now()->addMinutes(10))) {
                    GenerateTopJobsJob::dispatch($user->id);
                }
            }
        }

        $jobs = $this->searchService->search($request, 10, $topJobRecommendationIds);

        return view('user.jobs.index', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'isProfileComplete' => $isProfileComplete,
            'topJobRecommendationIds' => $topJobRecommendationIds,
            'aiRecommendationsPending' => request('top_choice') === '1' && empty($topJobRecommendationIds),
            'pageTitle' => 'Job Listings',
        ]);
    }

    public function show(\App\Models\JobPosting $job)
    {
        $alreadyApplied = false;
        $isSaved = false;
        $missingDocs = [];

        if (Auth::check() && Auth::user()?->role === \App\Enums\UserRole::USER) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $alreadyApplied = $user->applications()->where('job_id', $job->id)->exists();
            $isSaved = $user->savedJobs()->where('job_postings.id', $job->id)->exists();
            
            if (empty($user->cv_path)) $missingDocs[] = 'CV';
            if (empty($user->diploma_path)) $missingDocs[] = 'Diploma';
            if (empty($user->photo_path)) $missingDocs[] = 'Photo';
        }

        $relatedJobs = \App\Models\JobPosting::where('id', '!=', $job->id)
            ->where(function($q) use ($job) {
                $q->where('job_type', $job->job_type)
                  ->orWhere('location', 'like', "%{$job->location}%");
            })
            ->limit(6)
            ->get();

        return view('user.jobs.show', [
            'job' => $job,
            'isApplied' => $alreadyApplied,
            'isSaved' => $isSaved,
            'missingDocs' => $missingDocs,
            'relatedJobs' => $relatedJobs,
            'pageTitle' => $job->title,
        ]);
    }
}
