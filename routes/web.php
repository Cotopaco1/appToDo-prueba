<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '^(?!api).*$');

