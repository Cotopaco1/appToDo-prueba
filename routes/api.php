<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\NoteController;

Route::middleware('guest')->group(function(){

    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

    Route::post('/login', [AuthenticatedTokenController::class, 'store'])->name('login');
});

Route::middleware(['auth:api'])->group(function () {

    Route::post('/logout', [AuthenticatedTokenController::class, 'destroy'])->name('logout');

    Route::resource('/notes', NoteController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});



