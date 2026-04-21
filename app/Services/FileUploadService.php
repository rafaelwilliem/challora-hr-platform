<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file to a specific disk and directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $prefix
     * @return string Path to the uploaded file relative to the disk root
     */
    public function upload(UploadedFile $file, string $directory, string $prefix = ''): string
    {
        $filename = ($prefix ? $prefix . '_' : '') . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Delete an old file if it exists.
     *
     * @param string|null $path
     * @return void
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
