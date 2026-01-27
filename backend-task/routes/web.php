<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/course-admin')->group(function () {
    Route::get('/login', [UserController::class, 'login_page']);
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
});
