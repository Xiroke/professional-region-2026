<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('/course-admin')->group(function () {
    Route::get('/', [UserController::class, 'login_page']);
    Route::post('/login', [UserController::class, 'login'])->name('user.login');

    Route::middleware('can:isAdmin\App\Models\User')->group(function() {
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
        Route::get('/students', [UserController::class, 'index'])->name('students');
        Route::get('/print_page/{course}', [CertificateController::class, 'print_page'])->name('certificate.print');

        Route::resource('courses', CourseController::class);
        Route::resource('courses.lessons', LessonController::class);
    });
});
