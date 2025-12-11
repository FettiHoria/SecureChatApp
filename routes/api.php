<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;

Route::get('/users', function () {
    return ['users' => ['Alice', 'Bob']];
});

Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::post('/logout', [AuthController::class, 'logout'])->name("logout")->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->name("register");