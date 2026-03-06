<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

// ダッシュボードは認証とメール認証が必要（Breezeデフォルト）
Route::get('/dashboard', [AttendanceController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// 認証が必要なグループ
Route::middleware('auth')->group(function () {
    // 勤怠管理
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');

    // プロフィール管理
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
