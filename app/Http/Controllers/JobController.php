<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $searchService;

    public function __construct(\App\Services\JobSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $jobs = $this->searchService->search($request);
        
        $appliedJobIds = [];
        $savedJobIds = [];
        $isProfileComplete = true;

        if (auth()->check() && auth()->user()->role === \App\Enums\UserRole::USER) {
            $user = auth()->user();
            $appliedJobIds = $user->applications()->pluck('job_id')->toArray();
            $savedJobIds = $user->savedJobs()->pluck('job_postings.id')->toArray();
            
            if (empty($user->cv_path) || empty($user->diploma_path) || empty($user->photo_path)) {
                $isProfileComplete = false;
            }
        }

        return view('user.jobs.index', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'savedJobIds' => $savedJobIds,
            'isProfileComplete' => $isProfileComplete,
            'pageTitle' => 'Lowongan Kerja',
        ]);
    }

    public function show(\App\Models\JobPosting $job)
    {
        $alreadyApplied = false;
        $isSaved = false;
        $missingDocs = [];

        if (auth()->check() && auth()->user()->role === \App\Enums\UserRole::USER) {
            $user = auth()->user();
            $alreadyApplied = $user->applications()->where('job_id', $job->id)->exists();
            $isSaved = $user->savedJobs()->where('job_postings.id', $job->id)->exists();
            
            if (empty($user->cv_path)) $missingDocs[] = 'CV';
            if (empty($user->diploma_path)) $missingDocs[] = 'ijazah';
            if (empty($user->photo_path)) $missingDocs[] = 'pas foto';
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
            'alreadyApplied' => $alreadyApplied,
            'isSaved' => $isSaved,
            'missingDocs' => $missingDocs,
            'relatedJobs' => $relatedJobs,
            'pageTitle' => $job->title,
        ]);
    }
}
