<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $fileService;

    public function __construct(\App\Services\FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function edit()
    {
        $user = auth()->user()->load(['workExperiences', 'achievements']);
        
        return view('user.settings.edit', [
            'user' => $user,
            'workExperiences' => $user->workExperiences,
            'achievements' => $user->achievements,
            'pageTitle' => 'Pengaturan Profil',
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'string'],
            'religion' => ['nullable', 'string'],
            'social_media' => ['nullable', 'string'],
            'birth_place' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date'],
            'father_name' => ['nullable', 'string'],
            'mother_name' => ['nullable', 'string'],
            'marital_status' => ['nullable', 'string'],
            'father_job' => ['nullable', 'string'],
            'mother_job' => ['nullable', 'string'],
            'father_education' => ['nullable', 'string'],
            'mother_education' => ['nullable', 'string'],
            'father_phone' => ['nullable', 'string'],
            'mother_phone' => ['nullable', 'string'],
            'address_type' => ['nullable', 'string'],
            'address_family' => ['nullable', 'string'],
            'emergency_name' => ['nullable', 'string'],
            'emergency_phone' => ['nullable', 'string'],
            'education_level' => ['nullable', 'string'],
            'graduation_year' => ['nullable', 'string'],
            'education_major' => ['nullable', 'string'],
            'education_university' => ['nullable', 'string'],
            'user_summary' => ['nullable', 'string'],
        ]);

        $user->update($validated);

        // Update Work Experiences
        $user->workExperiences()->delete();
        if ($request->has('work_title')) {
            foreach ($request->work_title as $i => $title) {
                if ($title) {
                    $user->workExperiences()->create([
                        'title' => $title,
                        'company_name' => $request->work_company[$i] ?? '',
                        'year_start' => $request->work_year_start[$i] ?? '',
                        'year_end' => $request->work_year_end[$i] ?? '',
                        'description' => $request->work_description[$i] ?? '',
                    ]);
                }
            }
        }

        // Update Achievements
        $user->achievements()->delete();
        if ($request->has('ach_title')) {
            foreach ($request->ach_title as $i => $title) {
                if ($title) {
                    $user->achievements()->create([
                        'type' => $request->ach_type[$i] ?? '',
                        'title' => $title,
                        'description' => $request->ach_description[$i] ?? '',
                        'organizer' => $request->ach_organizer[$i] ?? '',
                        'year' => $request->ach_year[$i] ?? '',
                        'rank' => $request->ach_rank[$i] ?? '',
                        'level' => $request->ach_level[$i] ?? '',
                        'certificate_link' => $request->ach_certificate_link[$i] ?? '',
                    ]);
                }
            }
        }

        return back()->with('flash_toast', ['message' => 'Profil berhasil diperbarui.']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:1024'],
        ]);

        $user = auth()->user();
        
        $this->fileService->delete($user->avatar_path);
        $path = $this->fileService->upload($request->file('avatar'), 'photos', 'avatar');
        
        $user->update(['avatar_path' => $path]);

        return back()->with('flash_toast', ['message' => 'Foto profil berhasil diperbarui.']);
    }

    public function uploadDocument(Request $request, string $field)
    {
        $allowedFields = ['cv', 'diploma', 'photo'];
        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        $request->validate([
            $field => ['required', 'file', 'max:2048'],
        ]);

        $user = auth()->user();
        $dbField = $field . '_path';
        
        $this->fileService->delete($user->$dbField);
        $dir = $field === 'photo' ? 'photos' : ($field === 'cv' ? 'cv' : 'diplomas');
        $path = $this->fileService->upload($request->file($field), $dir, $field);
        
        $user->update([$dbField => $path]);

        return back()->with('flash_toast', ['message' => ucfirst($field) . ' berhasil diperbarui.']);
    }
}
