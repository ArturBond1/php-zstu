<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'testMethod'])->name('test');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/{product}', [ProductController::class, 'partialUpdate'])->name('products.partialUpdate');

});
