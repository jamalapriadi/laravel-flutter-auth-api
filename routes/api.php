<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;


Route::controller(GuestController::class)->group(function(){
    Route::post('registrasi', 'registrasi');
    Route::post('login', 'login');
    Route::post('verifikasi/{id}','verifikasi');
    Route::post('verifikasi-otp','verifikasi_otp');
    Route::post('password/forgot', 'forgot_password');
});

Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {

    Route::controller(AuthController::class)->group(function(){
        Route::get('/me', 'me');
        Route::post('logout', 'logout');
        Route::post('logoutall', 'logoutall');
        Route::post('change-password', 'change_password');
        Route::post('update-foto', 'update_foto');
        Route::post('update-info', 'update_info');
    });

    Route::controller(PostController::class)->group(function(){
        Route::get('posts','index');
        Route::get('post/{slug}','show');
    });

});