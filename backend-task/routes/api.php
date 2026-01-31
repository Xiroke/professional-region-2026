<?php

use App\Http\Controllers\Api\CertificateApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\LessonApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [UserApiController::class, 'login']);
Route::post('/registr', [UserApiController::class, 'store']);
Route::post('/payment-webhook', [CourseApiController::class, 'pay']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses', [CourseApiController::class, 'index']);
    Route::post('/courses/{course}/buy', [CourseApiController::class, 'buy']);
    Route::get('/courses/{course}', [LessonApiController::class, 'index']);
    Route::get('/orders', [CourseApiController::class, 'indexCurrentUser']);
    Route::get('/orders/{course}', [CourseApiController::class, 'reject']);
    Route::post('/check-sertificate', [CertificateApiController::class, 'checkCertificate']);
    Route::post('/create-sertificate', [CertificateApiController::class, 'createCertificate']);
});