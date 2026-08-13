<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('dashboard.index'));

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Semua role dapat melihat data
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/products/create', [ProductController::class, 'create'])->middleware('role:admin,manager')->name('products.create');
    Route::get('/products/import', [ProductController::class, 'showImport'])->middleware('role:admin')->name('products.import');
    Route::post('/products/import', [ProductController::class, 'import'])->middleware('role:admin')->name('products.import.process');
    Route::get('/products/import/template', [ProductController::class, 'importTemplate'])->middleware('role:admin')->name('products.import.template');
    Route::get('/products/export', [ProductController::class, 'export'])->middleware('role:admin')->name('products.export');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/transactions', [StockTransactionController::class, 'index'])->name('stock.transactions.index');
    Route::get('/stock/opname', [StockOpnameController::class, 'index'])->name('stock.opname.index');
});

// Hak akses penuh untuk Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/logo', [SettingsController::class, 'destroyLogo'])->name('settings.logo.delete');
});

// Semua role yang menangani operasional gudang dapat mencatat transaksi barang masuk/keluar
Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {
    Route::get('/stock/transactions/create/{type}', [StockTransactionController::class, 'create'])->name('stock.transactions.create');
    Route::post('/stock/transactions/create/{type}', [StockTransactionController::class, 'store'])->name('stock.transactions.store');
});

// Admin & Manajer Gudang
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::delete('/stock/transactions/{id}', [StockTransactionController::class, 'destroy'])->name('stock.transactions.destroy');

    Route::post('/stock/opname', [StockOpnameController::class, 'store'])->name('stock.opname.store');
    Route::delete('/stock/opname/{id}', [StockOpnameController::class, 'destroy'])->name('stock.opname.destroy');

    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/stock/export', [ReportController::class, 'exportStock'])->name('reports.stock.export');
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/transactions/export', [ReportController::class, 'exportTransactions'])->name('reports.transactions.export');
});

// Laporan aktivitas pengguna khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports/activities', [ReportController::class, 'activities'])->name('reports.activities');
    Route::get('/reports/activities/export', [ReportController::class, 'exportActivities'])->name('reports.activities.export');
});
