<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function() {
    return view('auth.dashboard');
})->middleware(['auth','verified']);
