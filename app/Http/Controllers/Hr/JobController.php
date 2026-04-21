<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Services\Hr\JobManagementService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobManagementService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index()
    {
        $jobs = $this->jobService->getHrJobs(auth()->id());
            
        return view('hr.jobs.index', [
            'jobs' => $jobs,
            'pageTitle' => 'Manage Listings',
        ]);
    }

    public function create()
    {
        return view('hr.jobs.create', [
            'pageTitle' => 'Create New Listing',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateJob($request);
        
        $this->jobService->createJob(auth()->id(), $validated);

        return redirect()->route('hr.dashboard')
            ->with('flash_toast', ['message' => 'Listing published successfully.']);
    }

    public function edit(JobPosting $job)
    {
        $this->authorizeOwner($job);
        
        return view('hr.jobs.edit', [
            'job' => $job,
            'pageTitle' => 'Edit Listing',
        ]);
    }

    public function update(Request $request, JobPosting $job)
    {
        $this->authorizeOwner($job);

        $validated = $this->validateJob($request);
        
        $this->jobService->updateJob($job, $validated);

        return redirect()->route('hr.dashboard')
            ->with('flash_toast', ['message' => 'Listing updated successfully.']);
    }

    public function destroy(JobPosting $job)
    {
        $this->authorizeOwner($job);
        
        $this->jobService->deleteJob($job);

        return back()->with('flash_toast', ['message' => 'Listing deleted successfully.']);
    }

    protected function validateJob(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string'],
            'salary_range' => ['nullable', 'string'],
            'min_salary' => ['nullable', 'numeric'],
            'max_salary' => ['nullable', 'numeric'],
            'job_type' => ['nullable', 'string'],
            'min_education' => ['nullable', 'string'],
            'experience_level' => ['nullable', 'string'],
            'is_urgent' => ['boolean'],
            'deadline' => ['nullable', 'date'],
            'max_applicants' => ['nullable', 'integer'],
            'skills' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
        ]);
    }

    protected function authorizeOwner(JobPosting $job)
    {
        if ($job->created_by !== auth()->id()) {
            abort(403);
        }
    }
}
