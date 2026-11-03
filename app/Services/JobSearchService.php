<?php

namespace App\Services;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobSearchService
{
    public function search(Request $request, int $perPage = 10, ?array $topIds = null)
    {
        $query = JobPosting::query();

        // Top Choice Filter
        if ($request->get('top_choice') === '1' && auth()->check()) {
            $topIds ??= \App\Models\AiUserJobRecommendation::where('user_id', auth()->id())
                ->pluck('job_id')
                ->toArray();

            if (!empty($topIds)) {
                $query->whereIn('id', $topIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Basic Search (title, description)
        if ($q = $request->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            });
        }

        // Filter by Location — must be a separate AND clause
        if ($location = $request->get('location')) {
            $query->where(function ($sub) use ($location) {
                $sub->where('location', 'like', "%{$location}%")
                    ->orWhere('provinsi', 'like', "%{$location}%")
                    ->orWhere('kota', 'like', "%{$location}%");
            });
        }

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
