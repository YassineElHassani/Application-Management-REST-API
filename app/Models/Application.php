<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Application extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'job_offer_id',
        'cv_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function cv()
    {
        return $this->belongsTo(CV::class);
    }
}