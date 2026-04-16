<?php

use Illuminate\Support\Facades\Route;
use Modules\Administration\Http\Controllers\AdministrationController;
use Modules\Administration\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->prefix('administration')->name('administration.')->group(function () {
    Route::resource('administrations', AdministrationController::class)->names('administration');
    
    Route::get('dashboard', [AdministrationController::class, 'dashboard'])->name('dashboard');
    // User CRUD
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
});
