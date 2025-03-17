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

Route::post('/register', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'signin'])->middleware(['role']);

Route::get('/job-offers', [JobOfferController::class, 'index']);
Route::get('/job-offers/{jobOffer}', [JobOfferController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    
    // CVs
    Route::get('/cvs', [CVController::class, 'index']);
    Route::post('/cvs', [CVController::class, 'store']);
    Route::get('/cvs/{cv}', [CVController::class, 'show']);
    Route::delete('/cvs/{cv}', [CVController::class, 'destroy']);
    
    // Job Offers (for recruiters)
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::post('/job-offers/{jobOffer}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']);
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::post('/applications/{application}', [ApplicationController::class, 'update']);
    Route::post('/apply-multiple', [ApplicationController::class, 'applyMultiple']);
});



