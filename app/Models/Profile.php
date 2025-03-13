<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Profile extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}