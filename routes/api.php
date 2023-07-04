<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    // Rutas de login y logout
    Route::post('auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('jwt.auth');
    
    // Ruta de registro de usuarios sin protección del middleware
    Route::post('users', [UserController::class, 'store'])->name('users.store');

    // Rutas protegidas por el middleware jwt.auth
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::resource('users', UserController::class)->except(['create', 'edit', 'store']);
        Route::resource('certificates', CertificateController::class)->except(['create', 'edit']);
    });
});
