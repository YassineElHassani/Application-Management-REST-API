<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 
Route::post('/register', [AuthController::class, 'signup'])->name('signup.post');
Route::post('/login', [AuthController::class, 'signin'])->middleware(['role'])->name('signin.post');

Route::post('/user', [AuthController::class, 'user'])->name('user');



