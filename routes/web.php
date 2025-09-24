<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\CoursesController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');

    Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
});
