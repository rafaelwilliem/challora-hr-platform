<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SavedJobService
{
    public function getSavedJobs(int $userId): LengthAwarePaginator
    {
        return User::findOrFail($userId)->savedJobs()->latest('saved_jobs.created_at')->paginate(10);
    }

    public function getAppliedJobIds(int $userId): array
    {
        return User::findOrFail($userId)->applications()->pluck('job_id')->toArray();
    }

    public function saveJob(User $user, int $jobId): void
    {
        $user->savedJobs()->syncWithoutDetaching([$jobId]);
    }

    public function unsaveJob(User $user, int $jobId): void
    {
        $user->savedJobs()->detach($jobId);
    }
}
