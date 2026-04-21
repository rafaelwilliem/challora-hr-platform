<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\Hr\ApplicationManagementService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationManagementService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index(Request $request)
    {
        $applications = $this->applicationService->getApplications(
            auth()->id(),
            $request->get('status'),
            $request->get('job_id')
        );
        
        $jobs = auth()->user()->jobPostings()->select('id', 'title')->get();

        return view('hr.applications.index', [
            'applications' => $applications,
            'jobs' => $jobs,
            'pageTitle' => 'Manage Applications',
        ]);
    }

    public function berkas(Application $application)
    {
        $this->authorizeOwner($application);

        $application->load(['job', 'user.workExperiences', 'user.achievements']);

        return view('hr.applications.berkas', [
            'application' => $application,
            'user' => $application->user,
            'pageTitle' => 'Applicant Files: ' . $application->user->name,
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $this->authorizeOwner($application);

        $request->validate([
            'status' => ['required', Rule::enum(\App\Enums\ApplicationStatus::class)],
        ]);

        $this->applicationService->updateStatus($application, $request->status);

        return back()->with('flash_toast', [
            'message' => 'Application status successfully updated to ' . $request->status,
        ]);
    }

    protected function authorizeOwner(Application $application)
    {
        if ($application->job->created_by !== auth()->id()) {
            abort(403);
        }
    }
}
