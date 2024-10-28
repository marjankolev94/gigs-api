<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\GigController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    // Company Routes
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);
    // Gigs Routes
    Route::post('/gigs', [GigController::class, 'store']);
    Route::put('/gigs/{gig}', [GigController::class, 'update']);
    Route::get('/gigs', [GigController::class, 'index']);
    Route::delete('/gigs/{gig}', [GigController::class, 'destroy']);
});