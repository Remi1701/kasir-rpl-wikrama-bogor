    <?php

    use App\Http\Controllers\ItemsController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\SalesController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\CustomersController;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    Route::get('/', function () {
        return redirect('/login');
    });

    Auth::routes();

    Route::middleware(['authenticate'])->group(function () {
        // Home Route
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Item Route
        Route::resource('items', ItemsController::class);

        // Sale Route
        Route::get('/sales/{id}/invoice', [SalesController::class, 'showInvoice'])->name('sales.invoice');
        Route::resource('sales', SalesController::class);

        // Customer Route
        Route::resource('customers', CustomersController::class);

        // Admin Route
        Route::middleware(['admin'])->group(function () {
            // User Route
            Route::resource('user', UserController::class);

            Route::get('/sales/export', [SalesController::class, 'exportAll'])->name('sales.exportAll');

            // Item Route
            Route::put('/items/{id}/update-stock', [ItemsController::class, 'updateStock'])->name('items.updateStock');

            Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
            Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        });

        // User Route
        Route::middleware(['user'])->group(function () {

            Route::post('/confirm-sale', [SalesController::class, 'confirmationStore'])->name('sales.confirmationStore');
            // Route::get('/items', [ItemsController::class, 'index'])->name('items.index');
        });
    });

