<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileSyncService
{
    public function updateProfile(User $user, array $validatedData, array $workData, array $achievementData, array $orgData = []): void
    {
        DB::transaction(function () use ($user, $validatedData, $workData, $achievementData, $orgData) {
            $user->update($validatedData);
            $this->syncWorkExperiences($user, $workData);
            $this->syncAchievements($user, $achievementData);
            $this->syncOrganizationalExperiences($user, $orgData);
        });
    }

    private function syncOrganizationalExperiences(User $user, array $orgData): void
    {
        if ($user->organizationalExperiences()) {
            $user->organizationalExperiences()->delete();
        }
        
        if (isset($orgData['org_name']) && is_array($orgData['org_name'])) {
            foreach ($orgData['org_name'] as $i => $name) {
                if (!empty($name)) {
                    $user->organizationalExperiences()->create([
                        'organization_name' => $name,
                        'position' => $orgData['org_position'][$i] ?? '',
                        'start_year' => $orgData['org_year_start'][$i] ?? '',
                        'year_end' => $orgData['org_year_end'][$i] ?? '',
                        'description' => $orgData['org_description'][$i] ?? '',
                    ]);
                }
            }
        }
    }

    private function syncWorkExperiences(User $user, array $workData): void
    {
        $user->workExperiences()->delete();
        
        if (isset($workData['work_title']) && is_array($workData['work_title'])) {
            foreach ($workData['work_title'] as $i => $title) {
                if (!empty($title)) {
                    $user->workExperiences()->create([
                        'title' => $title,
                        'company_name' => $workData['work_company'][$i] ?? '',
                        'year_start' => $workData['work_year_start'][$i] ?? '',
                        'year_end' => $workData['work_year_end'][$i] ?? '',
                        'description' => $workData['work_description'][$i] ?? '',
                    ]);
                }
            }
        }
    }

    private function syncAchievements(User $user, array $achievementData): void
    {
        $user->achievements()->delete();
        
        if (isset($achievementData['ach_title']) && is_array($achievementData['ach_title'])) {
            foreach ($achievementData['ach_title'] as $i => $title) {
                if (!empty($title)) {
                    $user->achievements()->create([
                        'type' => $achievementData['ach_type'][$i] ?? '',
                        'title' => $title,
                        'description' => $achievementData['ach_description'][$i] ?? '',
                        'organizer' => $achievementData['ach_organizer'][$i] ?? '',
                        'year' => $achievementData['ach_year'][$i] ?? '',
                        'rank' => $achievementData['ach_rank'][$i] ?? '',
                        'level' => $achievementData['ach_level'][$i] ?? '',
                        'certificate_link' => $achievementData['ach_certificate_link'][$i] ?? '',
                    ]);
                }
            }
        }
    }
}
