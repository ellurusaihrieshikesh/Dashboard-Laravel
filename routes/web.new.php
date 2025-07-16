<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\ProfileController;

// Main routes
Route::get('/', function () {
    return view('sales.landing');
});

// Dashboard routes
Route::get('/dashboard', [DailySaleController::class, 'index'])->name('sales.index');
Route::post('/store', [DailySaleController::class, 'store'])->name('sales.store');
Route::get('/today-summary', [DailySaleController::class, 'todaySummary'])->name('sales.today_summary');
Route::delete('/delete/{id}', [DailySaleController::class, 'destroy'])->name('sales.destroy');
Route::get('/pdf', [DailySaleController::class, 'generatePdf'])->name('sales.pdf');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); 