<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('app');
});
Route::get('/pos', function () {
    return view('app');
});
Route::get('/food-categories', function () {
    return view('app');
});
Route::get('/pos/order/{tableId}', function () {
    return view('app');
});
Route::get('/foods', function () {
    return view('app');
});

Route::get('/receipt/{order}', [OrderController::class, 'show'])->name('receipt');
Route::get('/receipt/{order}/download', [OrderController::class, 'download'])->name('receipt.download');
