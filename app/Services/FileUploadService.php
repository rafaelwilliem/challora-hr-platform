<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file to the private local disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $prefix
     * @return string Path to the uploaded file relative to the disk root
     */
    public function upload(UploadedFile $file, string $directory, string $prefix = ''): string
    {
        $extension = $file->extension() ?: $file->getClientOriginalExtension();
        $filename = ($prefix ? $prefix . '_' : '') . time() . '_' . Str::random(16) . '.' . $extension;

        return $file->storeAs($directory, $filename, 'local');
    }

    /**
     * Delete an old file if it exists and is not referenced by any submitted job application.
     *
     * @param string|null $path
     * @return void
     */
    public function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        // Preserve the file if any job application snapshot still references it
        $isReferencedByApplication = Application::where('cv_path', $path)
            ->orWhere('diploma_path', $path)
            ->orWhere('photo_path', $path)
            ->exists();

        if ($isReferencedByApplication) {
            return;
        }

        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        }
    }
}
