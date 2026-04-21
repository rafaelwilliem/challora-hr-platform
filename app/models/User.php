<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'father_name',
        'mother_name',
        'marital_status',
        'education_level',
        'graduation_year',
        'education_major',
        'education_university',
        'gender',
        'religion',
        'social_media',
        'birth_place',
        'birth_date',
        'father_job',
        'mother_job',
        'father_education',
        'mother_education',
        'father_phone',
        'mother_phone',
        'address_type',
        'address_family',
        'emergency_name',
        'emergency_phone',
        'user_summary',
        'avatar_path',
        'cv_path',
        'diploma_path',
        'photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'role' => \App\Enums\UserRole::class,
        ];
    }

    public function jobPostings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(JobPosting::class, 'created_by');
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(JobPosting::class, 'saved_jobs', 'user_id', 'job_id')->withTimestamps();
    }

    public function workExperiences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserWorkExperience::class);
    }

    public function achievements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === \App\Enums\UserRole::HR;
    }
}
