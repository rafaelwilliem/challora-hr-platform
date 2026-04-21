<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = auth()->user()->jobPostings()
            ->withCount('applications')
            ->latest()
            ->get();
            
        return view('hr.jobs.index', [
            'jobs' => $jobs,
            'pageTitle' => 'Kelola Lowongan',
        ]);
    }

    public function create()
    {
        return view('hr.jobs.create', [
            'pageTitle' => 'Buat Lowongan Baru',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        // Process lists to JSON
        $validated['skills_json'] = array_filter(array_map('trim', explode(',', $request->skills ?? '')));
        $validated['benefits_json'] = array_filter(array_map('trim', explode(',', $request->benefits ?? '')));
        
        auth()->user()->jobPostings()->create($validated);

        return redirect()->route('hr.dashboard')
            ->with('flash_toast', ['message' => 'Lowongan berhasil dipublikasikan.']);
    }

    public function edit(\App\Models\JobPosting $job)
    {
        $this->authorizeOwner($job);
        
        return view('hr.jobs.edit', [
            'job' => $job,
            'pageTitle' => 'Edit Lowongan',
        ]);
    }

    public function update(Request $request, \App\Models\JobPosting $job)
    {
        $this->authorizeOwner($job);

        $validated = $request->validate([
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

        $validated['skills_json'] = array_filter(array_map('trim', explode(',', $request->skills ?? '')));
        $validated['benefits_json'] = array_filter(array_map('trim', explode(',', $request->benefits ?? '')));

        $job->update($validated);

        return redirect()->route('hr.dashboard')
            ->with('flash_toast', ['message' => 'Lowongan berhasil diperbarui.']);
    }

    public function destroy(\App\Models\JobPosting $job)
    {
        $this->authorizeOwner($job);
        $job->delete();

        return back()->with('flash_toast', ['message' => 'Lowongan berhasil dihapus.']);
    }

    protected function authorizeOwner(\App\Models\JobPosting $job)
    {
        if ($job->created_by !== auth()->id()) {
            abort(403);
        }
    }
}
