<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Services\User\JobApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(JobApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index()
    {
        $applications = $this->applicationService->getUserApplications(auth()->id());
            
        return view('user.applications.index', [
            'applications' => $applications,
            'pageTitle' => 'Application Status',
        ]);
    }

    public function apply(Request $request, JobPosting $job)
    {
        try {
            $this->applicationService->applyForJob(auth()->user(), $job);
            
            return redirect()->route('jobs.show', $job->id)
                ->with('flash_toast', ['message' => 'Application sent successfully.']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            
            if (str_starts_with($message, 'MISSING_DOCS:')) {
                $docs = substr($message, 13);
                return redirect()->route('user.settings.edit')
                    ->withErrors(['message' => 'Please complete the following documents: ' . $docs]);
            }
            
            return back()->withErrors(['message' => $message]);
        }
    }
}
