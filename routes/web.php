<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentsController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\LessonsController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/courses', [CoursesController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CoursesController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CoursesController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CoursesController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CoursesController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CoursesController::class, 'destroy'])->name('courses.destroy');

    Route::get('/lessons', [LessonsController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/create', [LessonsController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [LessonsController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonsController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonsController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonsController::class, 'destroy'])->name('lessons.destroy');
    Route::delete('/lessons/{lesson}/materials/{locale}/{index}', [LessonsController::class, 'deleteMaterial'])->name('lessons.materials.delete');

    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentsController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentsController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentsController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');
});
