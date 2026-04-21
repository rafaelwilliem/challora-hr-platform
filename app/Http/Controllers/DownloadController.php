<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function download($type, $id)
    {
        $application = \App\Models\Application::with('job')->findOrFail($id);
        
        // Authorization check: Only owner of the job (HR) or the candidate themselves
        if (auth()->user()->role === \App\Enums\UserRole::HR) {
            if ($application->job->created_by !== auth()->id()) {
                abort(403);
            }
        } else {
            if ($application->user_id !== auth()->id()) {
                abort(403);
            }
        }

        $pathField = $type . '_path'; // cv_path, diploma_path, photo_path
        $path = $application->$pathField;

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
    }

    public function previewUserFile($type)
    {
        $user = auth()->user();
        $pathField = $type . '_path';
        $path = $user->$pathField;

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404, 'Berkas belum diunggah.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
    }

    public function avatar()
    {
        $user = auth()->user();
        $path = $user->avatar_path;

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            // Return default avatar if needed or 404
            return response()->file(public_path('images/default-avatar.png')); // Hypothetical default
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
    }
}
