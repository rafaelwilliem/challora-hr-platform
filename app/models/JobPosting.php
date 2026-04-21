<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'location',
        'salary_range',
        'min_salary',
        'max_salary',
        'job_type',
        'min_education',
        'is_urgent',
        'provinsi',
        'kota',
        'kecamatan',
        'deadline',
        'max_applicants',
        'skills_json',
        'benefits_json',
        'experience_level',
        'created_by',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'deadline' => 'datetime',
        'skills_json' => 'array',
        'benefits_json' => 'array',
        'job_type' => \App\Enums\JobType::class,
        'min_education' => \App\Enums\EducationLevel::class,
        'experience_level' => \App\Enums\ExperienceLevel::class,
    ];

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function savedByUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_jobs', 'job_id', 'user_id')->withTimestamps();
    }

    // Scopes for filtering
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
    }

    public function scopeLocation($query, $location)
    {
        if ($location) {
            return $query->where('location', 'like', "%{$location}%")
                         ->orWhere('provinsi', 'like', "%{$location}%")
                         ->orWhere('kota', 'like', "%{$location}%");
        }
    }

    public function scopeSalary($query, $min = null, $max = null)
    {
        if ($min) $query->where('max_salary', '>=', $min);
        if ($max) $query->where('min_salary', '<=', $max);
        return $query;
    }

    public function scopeJobType($query, $types)
    {
        if ($types) {
            $typesArray = is_array($types) ? $types : explode(',', $types);
            return $query->whereIn('job_type', $typesArray);
        }
    }
}
