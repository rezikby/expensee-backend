<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Page\homeDashbordController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// dashbor transaksi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/transaksi', [homeDashbordController::class, 'index']);
    Route::post('/transaksi', [homeDashbordController::class, 'store']);
    Route::get('/summary', [homeDashbordController::class, 'summary']);
    Route::get('/transaksi/{id}', [homeDashbordController::class, 'show']);
    Route::delete('/transaksi/{id}', [homeDashbordController::class, 'destroy']);
    Route::get('/kategori', [homeDashbordController::class, 'getKategori']);
    Route::get('/kategori/{id}', [homeDashbordController::class, 'getKategoriById']);
});



//profile
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'create']);
    Route::match(['post', 'put'], '/profile/{id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{id}', [ProfileController::class, 'delete']);
    // status profile
    Route::get('/status-profile/{id}', [StatusProfileController::class, 'show']);

});
