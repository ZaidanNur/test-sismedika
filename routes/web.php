<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    return view('app');
});

Route::get('/receipt/{order}', [OrderController::class, 'show'])->name('receipt');
Route::get('/receipt/{order}/download', [OrderController::class, 'download'])->name('receipt.download');
