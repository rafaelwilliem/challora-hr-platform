<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Services\User\SavedJobService;

class SavedJobController extends Controller
{
    protected $savedJobService;

    public function __construct(SavedJobService $savedJobService)
    {
        $this->savedJobService = $savedJobService;
    }

    public function index()
    {
        $userId = auth()->id();
        $savedJobs = $this->savedJobService->getSavedJobs($userId);
        $appliedJobIds = $this->savedJobService->getAppliedJobIds($userId);

        return view('user.jobs.saved', [
            'savedJobs' => $savedJobs,
            'appliedJobIds' => $appliedJobIds,
            'pageTitle' => 'Saved Jobs',
        ]);
    }

    public function save(JobPosting $job)
    {
        $this->savedJobService->saveJob(auth()->user(), $job->id);

        return back()->with('flash_toast', [
            'message' => 'Job saved successfully.',
        ]);
    }

    public function unsave(JobPosting $job)
    {
        $this->savedJobService->unsaveJob(auth()->user(), $job->id);

        return back()->with('flash_toast', [
            'message' => 'Job removed from saved list.',
        ]);
    }
}
