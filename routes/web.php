<?php

use App\Exports\SalesExport;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\SalesExportController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['authenticate'])->group(function () {
    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Item Route
    Route::resource('items', ItemController::class);

    // Sale Route
    // Route::get('/sales/{id}/invoice', [SaleController::class, 'showInvoice'])->name('sales.invoice');
    Route::get('/sales/{id}/invoice', [SaleController::class, 'showInvoice'])->name('sales.invoice');
    Route::resource('sales', SaleController::class);

    // Customer Route
    Route::resource('customers', CustomerController::class);

    // Admin Route
    Route::middleware(['admin'])->group(function () {
        // User Route
        Route::resource('user', UserController::class);

        Route::get('/sales/export', [SalesExportController::class, 'export'])->name('sales.export');
        Route::get('/sales/export/excel', function () {
            return Excel::download(new SalesExport, 'sales.xlsx');
        })->name('sales.export');        

        // Item Route
        Route::put('/items/{id}/update-stock', [ItemController::class, 'updateStock'])->name('items.updateStock');

        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
        Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    });
    
    // User Route
    Route::middleware(['user'])->group(function () {    
        
        Route::post('/confirm-sale', [SaleController::class, 'confirmationStore'])->name('sales.confirmationStore');
        // Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    });
});

