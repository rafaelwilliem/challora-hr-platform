<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\Ai\GenerateProfileSuggestionJob;
use App\Models\AiUserProfileSuggestion;
use App\Services\FileUploadService;
use App\Services\User\ProfileSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $fileService;
    protected $profileSyncService;

    public function __construct(FileUploadService $fileService, ProfileSyncService $profileSyncService)
    {
        $this->fileService = $fileService;
        $this->profileSyncService = $profileSyncService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['workExperiences', 'achievements']);
        $latestSuggestion = AiUserProfileSuggestion::where('user_id', $user->id)
            ->latest('generated_at')
            ->first();
        
        return view('user.settings.index', [
            'user' => $user,
            'latestSuggestion' => $latestSuggestion,
            'pageTitle' => 'My Profile',
        ]);
    }

    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load(['workExperiences', 'achievements']);
        
        return view('user.settings.edit', [
            'user' => $user,
            'workExperiences' => $user->workExperiences,
            'achievements' => $user->achievements,
            'pageTitle' => 'Profile Settings',
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
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
            'skills' => ['nullable', 'string'],
        ]);

        $this->profileSyncService->updateProfile(
            $user,
            $validated,
            $request->only(['work_title', 'work_company', 'work_year_start', 'work_year_end', 'work_description']),
            $request->only(['ach_title', 'ach_type', 'ach_description', 'ach_organizer', 'ach_year', 'ach_rank', 'ach_level', 'ach_certificate_link']),
            $request->only(['org_name', 'org_position', 'org_year_start', 'org_year_end', 'org_description'])
        );

        return redirect()->route('user.settings.index')->with('flash_toast', ['message' => 'Profile updated successfully.']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], [
            'avatar.max' => 'The photo must not be larger than 1MB.',
            'avatar.mimes' => 'Only JPG, JPEG, and PNG files are allowed.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $this->fileService->delete($user->avatar_path);
        $path = $this->fileService->upload($request->file('avatar'), 'photos', 'avatar');
        
        $user->update(['avatar_path' => $path]);

        return back()->with('flash_toast', ['message' => 'Profile photo updated successfully.']);
    }

    public function uploadDocument(Request $request, string $field)
    {
        $allowedFields = ['cv', 'diploma', 'photo'];
        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        $rules = match($field) {
            'cv', 'diploma' => ['required', 'file', 'mimes:pdf', 'max:1024'],
            'photo'         => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        };

        $messages = [
            "{$field}.max"   => 'The file must not be larger than 1MB.',
            "{$field}.mimes" => match($field) {
                'cv', 'diploma' => 'Only PDF files are allowed.',
                'photo'         => 'Only JPG, JPEG, and PNG files are allowed.',
            },
        ];

        $request->validate([$field => $rules], $messages);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $dbField = $field . '_path';
        
        $this->fileService->delete($user->$dbField);
        $dir = $field === 'photo' ? 'photos' : ($field === 'cv' ? 'cv' : 'diplomas');
        $path = $this->fileService->upload($request->file($field), $dir, $field);
        
        $user->update([$dbField => $path]);

        $labels = ['cv' => 'CV', 'diploma' => 'Diploma', 'photo' => 'Photo'];
        return back()->with('flash_toast', ['message' => $labels[$field] . ' updated successfully.']);
    }

    public function requestAiSuggestion(Request $request)
    {
        $request->validate([
            'target_role' => ['nullable', 'string', 'max:120'],
        ]);

        GenerateProfileSuggestionJob::dispatch((int) Auth::id(), $request->input('target_role'));

        return back()->with('flash_toast', [
            'message' => 'AI profile suggestions are being generated.',
        ]);
    }

    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filePaths = array_filter([
            $user->avatar_path,
            $user->cv_path,
            $user->diploma_path,
            $user->photo_path,
        ]);

        Auth::logout();
        $user->delete();

        foreach ($filePaths as $path) {
            $this->fileService->delete($path);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('flash_toast', [
            'message' => 'Your account and personal documents have been permanently deleted.',
        ]);
    }
}
