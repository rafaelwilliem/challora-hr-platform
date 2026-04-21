<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $jobs = $user->savedJobs()->latest('saved_jobs.created_at')->get();
        
        $appliedJobIds = $user->applications()->pluck('job_id')->toArray();

        return view('user.jobs.saved', [
            'jobs' => $jobs,
            'appliedJobIds' => $appliedJobIds,
            'pageTitle' => 'Lowongan Tersimpan',
        ]);
    }

    public function save(\App\Models\JobPosting $job)
    {
        auth()->user()->savedJobs()->syncWithoutDetaching([$job->id]);

        return back()->with('flash_toast', [
            'message' => 'Lowongan berhasil disimpan.',
        ]);
    }

    public function unsave(\App\Models\JobPosting $job)
    {
        auth()->user()->savedJobs()->detach($job->id);

        return back()->with('flash_toast', [
            'message' => 'Lowongan dihapus dari daftar tersimpan.',
        ]);
    }
}
