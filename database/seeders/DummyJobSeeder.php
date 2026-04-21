<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\JobType;
use App\Enums\EducationLevel;
use App\Enums\ExperienceLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DummyJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ensure an HR user exists
        $hr = User::where('role', UserRole::HR)->first();
        if (!$hr) {
            $hr = User::create([
                'name' => 'Challora HR Specialist',
                'email' => 'hr@challora.com',
                'password' => Hash::make('password'),
                'role' => UserRole::HR,
                'phone' => '08123456789',
            ]);
        }

        // 2. Generate 20 jobs with staggered dates
        $jobTitles = [
            'Senior Laravel Developer', 'Frontend Engineer (React)', 'UI/UX Designer',
            'Fullstack Web Developer', 'Backend Developer (Node.js)', 'Mobile Developer (Flutter)',
            'QA Engineer', 'DevOps Specialist', 'Data Scientist', 'HR Manager',
            'Marketing Executive', 'Sales Consultant', 'Customer Success', 'Project Manager',
            'Technical Writer', 'Database Administrator', 'System Analyst', 'Cyber Security Lead',
            'AI Researcher', 'Product Owner'
        ];

        $locations = ['Jakarta, ID', 'Bandung, ID', 'Surabaya, ID', 'Remote', 'Singapore', 'Yogyakarta, ID'];
        
        foreach ($jobTitles as $index => $title) {
            // Stagger created_at randomly within the last 30 days
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            JobPosting::create([
                'title' => $title,
                'description' => $faker->paragraphs(3, true),
                'short_description' => $faker->sentence(12),
                'location' => $faker->randomElement($locations),
                'salary_range' => 'Rp ' . rand(5, 10) . 'jt - ' . rand(11, 20) . 'jt',
                'min_salary' => rand(5000000, 10000000),
                'max_salary' => rand(11000000, 25000000),
                'job_type' => $faker->randomElement(JobType::cases()),
                'min_education' => $faker->randomElement(EducationLevel::cases()),
                'experience_level' => $faker->randomElement(ExperienceLevel::cases()),
                'is_urgent' => rand(0, 1),
                'deadline' => Carbon::now()->addDays(rand(10, 60)),
                'skills_json' => [
                    $faker->word, $faker->word, $faker->word
                ],
                'benefits_json' => [
                    'Asuransi Kesehatan', 'Bonus Tahunan', 'Remote Working'
                ],
                'created_by' => $hr->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Successfully seeded 20 dummy jobs with staggered dates!');
    }
}
