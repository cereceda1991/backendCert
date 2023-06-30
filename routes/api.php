<?php

use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::resource('certificates', CertificateController::class)->except(['create','edit']);
    Route::resource('users', UserController::class)->except(['create','edit']);
});
