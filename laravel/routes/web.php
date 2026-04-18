<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\StudentController;

// ── Auth ──────────────────────────────────────────────────────
Route::get('/',       fn() => redirect()->route('login'));
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Super Admin ───────────────────────────────────────────────
Route::prefix('admin')->name('super_admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard',                    [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Supervisors
    Route::get('/supervisors',                  [SuperAdminController::class, 'supervisors'])->name('supervisors');
    Route::get('/supervisors/create',           [SuperAdminController::class, 'createSupervisor'])->name('supervisors.create');
    Route::post('/supervisors',                 [SuperAdminController::class, 'storeSupervisor'])->name('supervisors.store');
    Route::get('/supervisors/{user}/edit',      [SuperAdminController::class, 'editSupervisor'])->name('supervisors.edit');
    Route::put('/supervisors/{user}',           [SuperAdminController::class, 'updateSupervisor'])->name('supervisors.update');
    Route::patch('/supervisors/{user}/toggle',  [SuperAdminController::class, 'toggleSupervisor'])->name('supervisors.toggle');

    // Students (read-only overview)
    Route::get('/students',                     [SuperAdminController::class, 'students'])->name('students');
});

// ── Supervisor ────────────────────────────────────────────────
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/dashboard',                        [SupervisorController::class, 'dashboard'])->name('dashboard');

    // Students
    Route::get('/students',                         [SupervisorController::class, 'students'])->name('students');
    Route::get('/students/create',                  [SupervisorController::class, 'createStudent'])->name('students.create');
    Route::post('/students',                        [SupervisorController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/edit',          [SupervisorController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{student}',               [SupervisorController::class, 'updateStudent'])->name('students.update');

    // Reports
    Route::get('/reports',                          [SupervisorController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}',                 [SupervisorController::class, 'showReport'])->name('reports.show');
    Route::patch('/reports/{report}/status',        [SupervisorController::class, 'updateStatus'])->name('reports.status');
    Route::post('/reports/{report}/notes',          [SupervisorController::class, 'addNote'])->name('reports.notes.store');
});

// ── Student ───────────────────────────────────────────────────
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard',                        [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/report/generate',                  [StudentController::class, 'generateReport'])->name('report.generate');

    // Daily Reports
    Route::get('/reports',                          [StudentController::class, 'reports'])->name('reports');
    Route::get('/reports/create',                   [StudentController::class, 'createReport'])->name('reports.create');
    Route::post('/reports',                         [StudentController::class, 'storeReport'])->name('reports.store');
    Route::get('/reports/{report}',                 [StudentController::class, 'showReport'])->name('reports.show');
    Route::get('/reports/{report}/edit',            [StudentController::class, 'editReport'])->name('reports.edit');
    Route::put('/reports/{report}',                 [StudentController::class, 'updateReport'])->name('reports.update');
    Route::post('/reports/{report}/notes',          [StudentController::class, 'addNote'])->name('reports.notes.store');
    Route::delete('/attachments/{attachment}',      [StudentController::class, 'deleteAttachment'])->name('attachments.destroy');
});
