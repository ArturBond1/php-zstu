<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;


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

Route::resource('patients', PatientController::class);
Route::resource('doctors', DoctorController::class);
Route::resource('diagnoses', DiagnosisController::class);
Route::resource('appointments', AppointmentController::class);
Route::resource('treatments', TreatmentController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
