<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

Passport::routes(); // Маршрути Passport повинні бути тут

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

