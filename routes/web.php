<?php
use Illuminate\Support\Facades\Route;
//  use App\Http\Controllers\JewelryController;

//  Route::get('/jewelry', [JewelryController::class, 'index'])->name('jewelry.index');
//  Route::get('/jewelry/{item}', [JewelryController::class, 'index'])
//       ->name('jewelry.detail')
//       ->where('item', '[0-9]+');

// use App\Http\Controllers\DailySaleController;

// Route::get('/', [DailySaleController::class, 'index'])->name('sales.index');
// Route::get('/create', [DailySaleController::class, 'create'])->name('sales.create');
// Route::post('/sales', [DailySaleController::class, 'store'])->name('sales.store');
// Route::get('/sales/today-summary', [DailySaleController::class, 'todaySummary'])->name('sales.today_summary');
// Route::delete('/sales/{dailySale}', [DailySaleController::class, 'destroy'])->name('sales.destroy');








// use App\Http\Controllers\DailySaleController;

// Route::get('/', [DailySaleController::class, 'index'])->name('sales.index');
// Route::get('/create', [DailySaleController::class, 'create'])->name('sales.create');
// Route::post('/store', [DailySaleController::class, 'store'])->name('sales.store');
// Route::get('/today-summary', [DailySaleController::class, 'todaySummary'])->name('sales.today_summary');

// Add this to your routes file
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

// use App\Http\Controllers\DailySaleController;

// // Existing routes
// Route::get('/sales', [DailySaleController::class, 'index'])->name('sales.index');
// Route::get('/sales/create', [DailySaleController::class, 'create'])->name('sales.create');
// Route::post('/sales', [DailySaleController::class, 'store'])->name('sales.store');
// Route::get('/sales/today', [DailySaleController::class, 'todaySummary'])->name('sales.today');

// // New route for chart data
// Route::get('/sales/chart-data', [DailySaleController::class, 'chartData'])->name('sales.chart-data');
