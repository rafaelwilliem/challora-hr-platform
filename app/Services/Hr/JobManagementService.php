<?php

namespace App\Services\Hr;

use App\Models\JobPosting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JobManagementService
{
    public function getHrJobs(int $hrId): LengthAwarePaginator
    {
        return JobPosting::where('created_by', $hrId)
            ->withCount('applications')
            ->latest()
            ->paginate(10);
    }

    public function createJob(int $hrId, array $data): JobPosting
    {
        $data['skills_json'] = $this->parseList($data['skills'] ?? '');
        $data['benefits_json'] = $this->parseList($data['benefits'] ?? '');
        $data['created_by'] = $hrId;

        return JobPosting::create($data);
    }

    public function updateJob(JobPosting $job, array $data): bool
    {
        $data['skills_json'] = $this->parseList($data['skills'] ?? '');
        $data['benefits_json'] = $this->parseList($data['benefits'] ?? '');

        return $job->update($data);
    }

    public function deleteJob(JobPosting $job): bool|null
    {
        return $job->delete();
    }

    private function parseList(string $input): array
    {
        return array_filter(array_map('trim', explode(',', $input)));
    }
}
