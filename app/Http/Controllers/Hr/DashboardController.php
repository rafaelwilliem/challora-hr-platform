<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $hrId = auth()->id();
        
        // 1. Basic Stats
        $jobs = \App\Models\JobPosting::where('created_by', $hrId)->pluck('id');
        $totalJobs = $jobs->count();
        
        $stats = \App\Models\Application::whereIn('job_id', $jobs)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            ")
            ->first();

        // 2. Top Regions (Calculated from user addresses)
        $topRegions = \App\Models\User::whereHas('applications', function($q) use ($jobs) {
                $q->whereIn('job_id', $jobs);
            })
            ->select('address as region', \DB::raw('count(*) as total'))
            ->groupBy('address')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Monthly Trend (Applications over last 6 months)
        $monthlyTrend = \App\Models\Application::whereIn('job_id', $jobs)
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month_label, COUNT(*) as total, SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month_label', \DB::raw('MONTH(created_at)'))
            ->orderBy(\DB::raw('MONTH(created_at)'))
            ->get();

        // 4. Jobs by Applicants
        $jobsWithStats = \App\Models\JobPosting::where('created_by', $hrId)
            ->withCount(['applications as applicant_count', 'applications as accepted_count' => function($q) {
                $q->where('status', 'accepted');
            }])
            ->orderByDesc('applicant_count')
            ->limit(5)
            ->get();

        return view('hr.dashboard', [
            'pageTitle' => 'HR Dashboard',
            'totalJobs' => $totalJobs,
            'stats' => $stats,
            'topRegions' => $topRegions,
            'monthlyTrend' => $monthlyTrend,
            'jobsByApplicants' => $jobsWithStats,
        ]);
    }
}
