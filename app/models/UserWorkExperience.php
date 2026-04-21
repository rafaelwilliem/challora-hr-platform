<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkExperience extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'year_start',
        'year_end',
        'description',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
