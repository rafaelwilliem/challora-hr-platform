<?php

namespace App\Services\Hr;

use App\Models\Application;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(int $hrId): array
    {
        $jobs = JobPosting::where('created_by', $hrId)->pluck('id');
        $totalJobs = $jobs->count();
        
        $stats = Application::whereIn('job_id', $jobs)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            ")
            ->first();

        // 2. Top Regions (Calculated from user addresses)
        $topRegions = User::whereHas('applications', function($q) use ($jobs) {
                $q->whereIn('job_id', $jobs);
            })
            ->select('address as region', DB::raw('count(*) as total'))
            ->groupBy('address')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Monthly Trend (Applications over last 6 months)
        $monthlyTrend = Application::whereIn('job_id', $jobs)
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month_label, COUNT(*) as total, SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month_label', DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

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
    }
}
