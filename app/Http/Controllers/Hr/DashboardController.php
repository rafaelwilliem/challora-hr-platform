<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Services\Hr\DashboardService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function __invoke()
    {
        $data = $this->dashboardService->getDashboardData(auth()->id());
        $data['hrInviteCode'] = RegisterController::getActiveHrInviteCode();

        return view('hr.dashboard', array_merge(['pageTitle' => 'HR Dashboard'], $data));
    }

    public function regenerateInviteCode()
    {
        $newCode = 'CHALLORA-HR-' . Str::upper(Str::random(6));

        try {
            Cache::store('database')->forever('hr_invite_code', $newCode);
        } catch (\Throwable $e) {
            // Ignore if DB cache store fails and save to default store below
        }
        Cache::forever('hr_invite_code', $newCode);

        return back()->with('flash_toast', [
            'message' => 'HR Invite Code regenerated: ' . $newCode,
        ]);
    }
}
