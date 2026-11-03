<?php

namespace App\Services\Hr;

use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(int $hrId): array
    {
        return cache()->remember("hr_stats_dashboard_{$hrId}", now()->addMinutes(2), function () use ($hrId) {
            $driver = DB::connection()->getDriverName();
            $jobs = JobPosting::where('created_by', $hrId)->pluck('id');
            $totalJobs = $jobs->count();

            if ($jobs->isEmpty()) {
                $stats = (object) [
                    'total' => 0,
                    'accepted' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                    'reviewed' => 0,
                ];
            } else {
                $rawStats = Application::whereIn('job_id', $jobs)
                    ->selectRaw("
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                        SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed
                    ")
                    ->first();

                $stats = (object) [
                    'total' => (int) ($rawStats->total ?? 0),
                    'accepted' => (int) ($rawStats->accepted ?? 0),
                    'pending' => (int) ($rawStats->pending ?? 0),
                    'rejected' => (int) ($rawStats->rejected ?? 0),
                    'reviewed' => (int) ($rawStats->reviewed ?? 0),
                ];
            }

            // Group by job location to avoid fragmented free-text user addresses.
            $topRegions = JobPosting::query()
                ->whereIn('job_postings.id', $jobs)
                ->join('applications', 'applications.job_id', '=', 'job_postings.id')
                ->selectRaw("COALESCE(NULLIF(job_postings.location, ''), 'Unknown') as region")
                ->selectRaw('COUNT(applications.id) as total')
                ->groupBy('region')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            // Monthly trend currently relies on MySQL functions for grouped month labels.
            if ($driver === 'mysql' && $jobs->isNotEmpty()) {
                $monthlyTrend = Application::whereIn('job_id', $jobs)
                    ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month_label, COUNT(*) as total, SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted")
                    ->where('created_at', '>=', now()->subMonths(6))
                    ->groupBy('month_label', DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                    ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                    ->get();
            } else {
                $monthlyTrend = collect();
            }

            // 4. Jobs by Applicants
            $jobsWithStats = JobPosting::where('created_by', $hrId)
                ->withCount(['applications as applicant_count', 'applications as accepted_count' => function($q) {
                    $q->where('status', 'accepted');
                }])
                ->orderByDesc('applicant_count')
                ->limit(5)
                ->get();

            return [
                'totalJobs' => $totalJobs,
                'stats' => $stats,
                'topRegions' => $topRegions,
                'monthlyTrend' => $monthlyTrend,
                'jobsByApplicants' => $jobsWithStats,
            ];
        });
    }
}
