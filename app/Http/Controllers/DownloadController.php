<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    private const ALLOWED_TYPES = ['cv', 'diploma', 'photo'];

    /**
     * Resolve which disk ('local' private disk first, 'public' legacy fallback second) holds the given path.
     */
    private function resolveDisk(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return $disk;
            }
        }

        return null;
    }

    public function download($type, $id)
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            abort(404);
        }

        $application = \App\Models\Application::with(['job', 'user'])->findOrFail($id);
        
        if (auth()->user()->role === \App\Enums\UserRole::HR) {
            if ($application->job->created_by !== auth()->id()) {
                abort(403, 'You are not the owner of this job posting.');
            }
        } else {
            if ($application->user_id !== auth()->id()) {
                abort(403, 'This file does not belong to you.');
            }
        }

        $pathField = $type . '_path';
        // Prefer application snapshot if it exists on disk, otherwise use user's current file
        $appFilePath  = $application->$pathField;
        $userFilePath = $application->user->$pathField;

        $disk = $this->resolveDisk($appFilePath);
        $path = $disk ? $appFilePath : $userFilePath;
        $disk = $disk ?: $this->resolveDisk($userFilePath);

        if (!$path || !$disk) {
            return response(view('shared.document_not_found', [
                'type' => $type,
                'message' => 'File not found in storage.',
            ]), 200);
        }

        return Storage::disk($disk)->response($path);
    }

    public function previewUserFile($type)
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            abort(404);
        }

        $user = auth()->user();
        $pathField = $type . '_path';
        $path = $user->$pathField;
        $disk = $this->resolveDisk($path);

        if (!$path || !$disk) {
            return response(view('shared.document_not_found', [
                'type' => $type,
                'message' => 'You have not uploaded this document yet.',
            ]), 200);
        }

        return Storage::disk($disk)->response($path);
    }

    public function avatar()
    {
        $user = auth()->user();
        $path = $user->avatar_path;
        $disk = $this->resolveDisk($path);

        if (!$path || !$disk) {
            // Check if default avatar exists, otherwise abort 404
            $defaultPath = public_path('images/default-avatar.png');
            if (file_exists($defaultPath)) {
                return response()->file($defaultPath);
            }
            abort(404);
        }

        return Storage::disk($disk)->response($path);
    }

    public function viewDocument($type, $id = null)
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            abort(404);
        }

        if ($id) {
            $application = \App\Models\Application::with(['user', 'job'])->findOrFail($id);

            // Authorization: HR must own the job, candidate must own the application
            if (auth()->user()->role === \App\Enums\UserRole::HR) {
                if ($application->job->created_by !== auth()->id()) {
                    abort(403, 'You are not the owner of this job posting.');
                }
            } else {
                if ($application->user_id !== auth()->id()) {
                    abort(403, 'This file does not belong to you.');
                }
            }

            $pathField = $type . '_path';
            $appFilePath  = $application->$pathField;
            $userFilePath = $application->user->$pathField;

            $disk = $this->resolveDisk($appFilePath);
            $path = $disk ? $appFilePath : $userFilePath;
            $disk = $disk ?: $this->resolveDisk($userFilePath);

            $streamRoute = route('download.file', ['type' => $type, 'id' => $application->id]);
        } else {
            $user = auth()->user();
            $pathField = $type . '_path';
            $path = $user->$pathField;
            $disk = $this->resolveDisk($path);

            $streamRoute = route('preview.user_file', ['type' => $type]);
        }

        if (!$path || !$disk) {
            return response(view('shared.document_not_found', [
                'type' => $type,
                'message' => 'This file could not be found on the server.',
            ]), 200);
        }

        $fileUrl = $streamRoute . '#toolbar=0&navpanes=0&scrollbar=0';

        return view('shared.document_viewer', [
            'url' => $fileUrl,
            'type' => strtoupper($type)
        ]);
    }
}
