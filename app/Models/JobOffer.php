<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class JobOffer extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'contract_type',
        'posted_at',
        'recruiter_id',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'salary' => 'decimal:2',
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}