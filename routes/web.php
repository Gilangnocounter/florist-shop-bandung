<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Product;
use App\Http\Controllers\OrderController;

// Halaman Utama Katalog
Route::get('/', function () {
    $products = Product::latest()->get();
    return view('welcome', compact('products'));
});

// Dashboard User
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// LOGIN GOOGLE
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('google.callback');

// LOGOUT MANUAL
Route::get('/logout-manual', function () {
    auth()->logout();
    session()->flush();
    return redirect('/login');
});

// --- RUTE UNTUK SEMUA USER LOGIN ---
Route::middleware(['auth'])->group(function () {
    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaksi User
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::post('/orders/{transaction}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // RUTE BARU: Upload Bukti Pembayaran
    Route::post('/orders/{id}/upload-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{id}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

});

// --- ADMIN ROUTES (Hanya untuk Admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Manajemen Produk
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    
    // Manajemen Transaksi Admin
    Route::get('/transactions', [OrderController::class, 'index'])->name('admin.transactions');
    Route::patch('/transactions/{transaction}/status', [OrderController::class, 'updateStatus'])->name('admin.transactions.update');
});

require __DIR__.'/auth.php';