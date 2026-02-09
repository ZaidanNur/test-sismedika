<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\FoodCategoryController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\OrderController;

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/tables', [TableController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('foods', FoodController::class)->except('update');
    Route::post('foods/{id}', [FoodController::class, 'update'])->name('foods.update');
    
    Route::apiResource('food-categories', FoodCategoryController::class);
    Route::apiResource('tables', TableController::class)->only(['update']);
    Route::apiResource('orders', OrderController::class);
    Route::put('orders/{orderId}/details/{detailId}', [OrderController::class, 'updateDetail'])->name('orders.details.update');
    Route::get('orders/{order}/download', [OrderController::class, 'download'])->name('orders.download');
});