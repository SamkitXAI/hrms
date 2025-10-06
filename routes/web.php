<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionController;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth'])->group(function () {
    Route::resource('employees', EmployeeController::class)->only(['index','create','store','show']);
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/check-in/{employee}', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('attendance/check-out/{employee}', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
     Route::get('my/attendance', [AttendanceController::class, 'my'])->name('attendance.my');
    Route::post('attendance/corrections', [AttendanceCorrectionController::class, 'store'])->name('attendance.corrections.store');
    Route::get('attendance/corrections', [AttendanceCorrectionController::class, 'index'])->name('attendance.corrections.index'); // HR inbox
    Route::post('attendance/corrections/{correction}/approve', [AttendanceCorrectionController::class, 'approve'])->name('attendance.corrections.approve');
    Route::post('attendance/corrections/{correction}/reject', [AttendanceCorrectionController::class, 'reject'])->name('attendance.corrections.reject');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
