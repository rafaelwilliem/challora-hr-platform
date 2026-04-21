<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Application::whereHas('job', function($q) {
            $q->where('created_by', auth()->id());
        })->with(['job', 'user']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($jobId = $request->get('job_id')) {
            $query->where('job_id', $jobId);
        }

        $applications = $query->latest()->get();
        $jobs = auth()->user()->jobPostings()->select('id', 'title')->get();

        return view('hr.applications.index', [
            'applications' => $applications,
            'jobs' => $jobs,
            'pageTitle' => 'Kelola Lamaran',
        ]);
    }

    public function berkas(\App\Models\Application $application)
    {
        $this->authorizeOwner($application);

        $application->load(['job', 'user.workExperiences', 'user.achievements']);

        return view('hr.applications.berkas', [
            'application' => $application,
            'user' => $application->user,
            'pageTitle' => 'Berkas Pelamar: ' . $application->user->name,
        ]);
    }

    public function updateStatus(Request $request, \App\Models\Application $application)
    {
        $this->authorizeOwner($application);

        $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\ApplicationStatus::class)],
        ]);

        $application->update([
            'status' => $request->status,
        ]);

        return back()->with('flash_toast', [
            'message' => 'Status lamaran berhasil diperbarui menjadi ' . $request->status,
        ]);
    }

    protected function authorizeOwner(\App\Models\Application $application)
    {
        if ($application->job->created_by !== auth()->id()) {
            abort(403);
        }
    }
}
