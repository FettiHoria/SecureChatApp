<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

Route::get('/users', function () {
    return ['users' => ['Alice', 'Bob']];
});

Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::post('/logout', [AuthController::class, 'logout'])->name("logout")->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register'])->name("register");


Route::middleware('auth:sanctum')->group(function () {

    // Creează o conversație privată
    Route::post('/conversations', [ConversationController::class, 'create']);

    // Listează conversațiile utilizatorului
    Route::get('/conversations', [ConversationController::class, 'index']);

    // Listează mesajele unei conversații
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);

    // Trimite un mesaj într-o conversație
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'send']);

});