<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasetController;
use App\Http\Controllers\SewaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::middleware('auth:sanctum')->group(function () {
//         Route::post('logout', [AuthController::class, 'logout']);
//         Route::post('auth/login', [AuthController::class, 'login']);
//         Route::post('register', [AuthController::class, 'register']);
//     });

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
      Route::get('kaset', [SewaController::class, 'getListKaset']);
    //   Route::post('sewa', [SewaController::class, 'buktiSewa']);
      Route::post('/sewa/{id}', [SewaController::class, 'buktiSewa']);
    //   Route::post('/sewa1/{id}', [SewaController::class, 'buktiSewa1']);
      Route::put('/kaset/{id}', [SewaController::class, 'updateStatKaset']);
      Route::post('/upload/{id}', [SewaController::class, 'uploadSewa']);

    });
});


    // Grouping API routes for better organization
    Route::prefix('v1')->group(function () {

    // Kaset Routes
    // Route::get('kaset', [KasetController::class, 'index']);
    // Route::get('kaset/{id}', [KasetController::class, 'show']);
    // Route::get('kaset-available', [KasetController::class, 'getAvailableKaset']);
    // Route::get('kaset-available-user/{userId}', [KasetController::class, 'getAvailableKasetForUser']);

    // Uncomment and modify these routes if needed
    // Route::post('kaset', [KasetController::class, 'store']);
    // Route::put('kaset/{id}', [KasetController::class, 'update']);
    // Route::delete('kaset/{id}', [KasetController::class, 'destroy']);
});

