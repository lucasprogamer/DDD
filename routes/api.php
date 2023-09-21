<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/refresh-token', [AuthController::class, 'refresh'])->name('refresh');


    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('schedule', ScheduleController::class);
    });
});
