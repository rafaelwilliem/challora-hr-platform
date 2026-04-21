<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\User\SavedJobController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ApplicationController as UserApplicationController;
use App\Http\Controllers\Hr\JobController as HrJobController;
use App\Http\Controllers\Hr\ApplicationController as HrApplicationController;

Route::get('/', function () {
    $latestJobs = \App\Models\JobPosting::with('creator')->latest()->take(3)->get();
    return view('landing', [
        'pageTitle' => 'Rekrutmen Cerdas (Powered by Chally)',
        'latestJobs' => $latestJobs
    ]);
})->name('landing');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Public / Guest accessible Job Listing and Detail
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Download / Preview File
    Route::get('/download/{type}/{id}', [DownloadController::class, 'download'])->name('download.file');
    Route::get('/preview/{type}', [DownloadController::class, 'previewUserFile'])->name('preview.user_file');
    Route::get('/avatar', [DownloadController::class, 'avatar'])->name('avatar');

    // User / Candidate Routes
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
        Route::post('/settings', [ProfileController::class, 'update'])->name('settings.update');
        Route::post('/settings/avatar', [ProfileController::class, 'uploadAvatar'])->name('settings.avatar');
        Route::post('/settings/upload/{field}', [ProfileController::class, 'uploadDocument'])->name('settings.upload');
        
        Route::get('/applications', [UserApplicationController::class, 'index'])->name('applications.index');
        Route::post('/jobs/{job}/apply', [UserApplicationController::class, 'apply'])->name('jobs.apply');
        
        Route::get('/jobs/saved', [SavedJobController::class, 'index'])->name('jobs.saved');
        Route::post('/jobs/{job}/save', [SavedJobController::class, 'save'])->name('jobs.save');
        Route::post('/jobs/{job}/unsave', [SavedJobController::class, 'unsave'])->name('jobs.unsave');
    });

    // HR Routes
    Route::middleware('role:hr')->prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', \App\Http\Controllers\Hr\DashboardController::class)->name('dashboard');
        
        Route::resource('jobs', HrJobController::class);
        
        Route::get('/applications', [HrApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}/berkas', [HrApplicationController::class, 'berkas'])->name('applications.berkas');
        Route::post('/applications/{application}/status', [HrApplicationController::class, 'updateStatus'])->name('applications.status');
    });
});
