<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->applications()
            ->with('job')
            ->latest()
            ->get();
            
        return view('user.applications.index', [
            'applications' => $applications,
            'pageTitle' => 'Status Lamaran',
        ]);
    }

    public function apply(Request $request, \App\Models\JobPosting $job)
    {
        $user = auth()->user();

        // 1. Check if already applied
        if ($user->applications()->where('job_id', $job->id)->exists()) {
            return back()->withErrors(['message' => 'Anda sudah melamar lowongan ini.']);
        }

        // 2. Check deadline
        if ($job->deadline && $job->deadline->isPast()) {
            return back()->withErrors(['message' => 'Lowongan telah ditutup.']);
        }

        // 3. Check document readiness
        $missingDocs = [];
        if (empty($user->cv_path)) $missingDocs[] = 'CV';
        if (empty($user->diploma_path)) $missingDocs[] = 'ijazah';
        if (empty($user->photo_path)) $missingDocs[] = 'pas foto';

        if (!empty($missingDocs)) {
            return redirect()->route('user.settings.edit')
                ->withErrors(['message' => 'Lengkapi dokumen berikut: ' . implode(', ', $missingDocs)]);
        }

        // 4. Create application
        $user->applications()->create([
            'job_id' => $job->id,
            'cv_path' => $user->cv_path,
            'diploma_path' => $user->diploma_path,
            'photo_path' => $user->photo_path,
            'status' => \App\Enums\ApplicationStatus::PENDING,
        ]);

        return redirect()->route('jobs.show', $job->id)
            ->with('flash_toast', ['message' => 'Lamaran berhasil dikirim.']);
    }
}
