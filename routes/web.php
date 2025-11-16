<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [dashboardController::class, 'index'])->name('dashboard');
    Route::get('admin/categories', [CategoryController::class, 'categoryPage'])->name('category');
    Route::get('admin/products', [ProductController::class, 'productPage'])->name('product');
});


Route::get('/user', function () {
    return response()->json(auth()->user());
})->middleware('role:user');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
