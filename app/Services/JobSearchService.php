<?php

namespace App\Services;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobSearchService
{
    public function search(Request $request, int $perPage = 10)
    {
        $query = JobPosting::query();

        // Basic Search (title, description)
        $query->search($request->get('q'));

        // Filter by Location
        $query->location($request->get('location'));

        // Filter by Salary
        $query->salary($request->get('min_salary'), $request->get('max_salary'));

        // Filter by Job Type
        $query->jobType($request->get('job_type'));

        // Filter by Education
        if ($education = $request->get('min_education')) {
            $eduArray = is_array($education) ? $education : explode(',', $education);
            $query->whereIn('min_education', $eduArray);
        }

        // Filter by Experience Level
        if ($experience = $request->get('experience_level')) {
            $expArray = is_array($experience) ? $experience : explode(',', $experience);
            $query->whereIn('experience_level', $expArray);
        }

        // Sorting
        $query->latest();

        return $query->paginate($perPage)->withQueryString();
    }
}
