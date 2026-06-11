<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RestockController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // FIXED: Specific inventory routes before resource routes
    Route::post('/inventory/{inventory}/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    
    // FIXED: Use 'inventory' as the parameter name to match the route
    Route::resource('inventory', InventoryController::class)->except(['show'])->parameters([
        'inventory' => 'inventory'
    ]);
    
    // Supplier routes
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    
    // Restock routes
    Route::resource('restock', RestockController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::post('/restock/{order}/receive', [RestockController::class, 'receive'])->name('restock.receive');
});