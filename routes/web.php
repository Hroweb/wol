<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('admin.dashboard')->name('aa');
});*/
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
