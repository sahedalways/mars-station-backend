<?php

use App\Http\Controllers\Api\ComplaintApiController;
use App\Http\Controllers\Api\GetServiceApiController;
use App\Http\Controllers\Api\QueryApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ServiceApiController;
use Illuminate\Support\Facades\Route;

// Services - public read
Route::get('/services', [ServiceApiController::class, 'index']);
Route::get('/services/active', [ServiceApiController::class, 'active']);
Route::get('/services/active-flat', [ServiceApiController::class, 'activeFlat']);
Route::get('/services/{id}', [ServiceApiController::class, 'show']);

// Reviews - public read, public submit
Route::get('/reviews', [ReviewApiController::class, 'approved']);
Route::get('/reviews/all', [ReviewApiController::class, 'index']);
Route::post('/reviews', [ReviewApiController::class, 'store']);
Route::get('/reviews/{id}', [ReviewApiController::class, 'show']);

// Get Service Requests - public submit
Route::post('/get-services', [GetServiceApiController::class, 'store']);
Route::get('/get-services', [GetServiceApiController::class, 'index']);
Route::get('/get-services/{id}', [GetServiceApiController::class, 'show']);

// Complaints - public submit
Route::post('/complaints', [ComplaintApiController::class, 'store']);
Route::get('/complaints', [ComplaintApiController::class, 'index']);
Route::get('/complaints/{id}', [ComplaintApiController::class, 'show']);

// Queries - public submit
Route::post('/queries', [QueryApiController::class, 'store']);
Route::get('/queries', [QueryApiController::class, 'index']);
Route::get('/queries/{id}', [QueryApiController::class, 'show']);
