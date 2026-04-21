<?php

namespace App\Services\User;

use App\Models\JobPosting;
use App\Models\User;
use App\Enums\ApplicationStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class JobApplicationService
{
    public function getUserApplications(int $userId): LengthAwarePaginator
    {
        return User::findOrFail($userId)->applications()
            ->with('job')
            ->latest()
            ->paginate(10);
    }

    /**
     * @throws Exception
     */
    public function applyForJob(User $user, JobPosting $job): void
    {
        if ($user->applications()->where('job_id', $job->id)->exists()) {
            throw new Exception('Anda sudah melamar lowongan ini.');
        }

        if ($job->deadline && $job->deadline->isPast()) {
            throw new Exception('Lowongan telah ditutup.');
        }

        $missingDocs = [];
        if (empty($user->cv_path)) $missingDocs[] = 'CV';
        if (empty($user->diploma_path)) $missingDocs[] = 'ijazah';
        if (empty($user->photo_path)) $missingDocs[] = 'pas foto';

        if (!empty($missingDocs)) {
            throw new Exception('MISSING_DOCS:' . implode(', ', $missingDocs));
        }

        $user->applications()->create([
            'job_id' => $job->id,
            'cv_path' => $user->cv_path,
            'diploma_path' => $user->diploma_path,
            'photo_path' => $user->photo_path,
            'status' => ApplicationStatus::PENDING,
        ]);
    }
}
