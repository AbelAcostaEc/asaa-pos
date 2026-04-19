<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\UnitController;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\SupplierController;
use Modules\Inventory\Http\Controllers\CustomerController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\PosController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('inventories', InventoryController::class)->names('inventory');
});

Route::middleware(['auth', 'verified'])->prefix('inventory')->group(function() {
    // Units CRUD
    Route::resource('units', UnitController::class)->names('inventory.units');

    // Categories CRUD
    Route::resource('categories', CategoryController::class)->names('inventory.categories');

    // Suppliers CRUD
    Route::resource('suppliers', SupplierController::class)->names('inventory.suppliers');

    // Customers CRUD
    Route::resource('customers', CustomerController::class)->names('inventory.customers');

    // Products CRUD
    Route::resource('products', ProductController::class)->names('inventory.products');

    // Product Catalog
    Route::get('catalog', [ProductController::class, 'catalog'])->name('inventory.products.catalog');

    // Reports
    Route::get('reports/purchases', [InventoryController::class, 'reportPurchases'])->name('inventory.reports.purchases');
    Route::get('reports/sales', [InventoryController::class, 'reportSales'])->name('inventory.reports.sales');

    // POS
    Route::get('pos', [PosController::class, 'index'])->name('inventory.pos.index');
    Route::post('pos/add-product', [PosController::class, 'addProduct'])->name('inventory.pos.addProduct');
    Route::post('pos/add-service', [PosController::class, 'addService'])->name('inventory.pos.addService');
    Route::post('pos/remove-product', [PosController::class, 'removeProduct'])->name('inventory.pos.removeProduct');
    Route::post('pos/checkout', [PosController::class, 'checkout'])->name('inventory.pos.checkout');
});