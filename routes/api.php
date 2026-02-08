<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\FoodCategoryController;

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('foods', FoodController::class)->except('update');
    Route::post('foods/{id}', [FoodController::class, 'update'])->name('foods.update');
    
    Route::apiResource('food-categories', FoodCategoryController::class);
});