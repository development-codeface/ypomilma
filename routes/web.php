<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Admin Controllers
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\DairyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\FundAllocationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ExpenseItemController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\TransactionReportController;
use App\Http\Controllers\Admin\DashboardController;
// Auth/Profile Controllers
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\InvoiceListController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\AggencySaleController;

Route::redirect('/', '/login');

Auth::routes(['register' => false]);

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'auth.gates']  // Add 'web' and your custom middleware alias here
], function () {
    Route::redirect('/', '/admin/report')->name('home');

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    // Block a user
    Route::put('users/{user}/block', [UsersController::class, 'block'])->name('users.block');

    // Unblock a user
    Route::put('users/{user}/unblock', [UsersController::class, 'unblock'])->name('users.unblock');

    // Region
    Route::delete('regions/destroy', [RegionController::class, 'massDestroy'])->name('regions.massDestroy');
    Route::get('regions/get', [RegionController::class, 'get'])->name('regions.info');
    Route::resource('regions', RegionController::class);

    //Vendors
    Route::resource('vendors', VendorController::class);
    Route::put('vendors/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendors.toggleStatus');
    Route::resource('dairies', DairyController::class);
    Route::resource('products', ProductController::class);
    Route::resource('expense_categories', ExpenseCategoryController::class);
    Route::resource('fund_allocations', FundAllocationController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('expenseitems', ExpenseItemController::class);
    Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])
        ->name('invoices.cancel');

    // Invoice List
    Route::get('invoice-list', [InvoiceListController::class, 'index'])->name('invoice-list.index');
    Route::put('invoice/status/change/{id}', [InvoiceListController::class, 'statusChange'])->name('invoice.status.change');


    //Asset Management
    Route::get('asset-management', [AssetController::class, 'index'])->name('asset-management.index');
    Route::get('asset-management/create', [AssetController::class, 'create'])->name('asset-management.create');
    Route::get('get-asset-details/{assetId}', [AssetController::class, 'getAssetDetails'])->name('get-asset-details');
    Route::post('asset-management/invoice/store', [AssetController::class, 'store'])->name('asset-management.invoice.store');

    Route::get('aggency-sale', [AggencySaleController::class, 'index'])->name('aggency-sale.index');
    Route::get('aggency-sale/show/{id}', [AggencySaleController::class, 'show'])->name('aggency-sale.show');


    Route::resource('expenses', ExpenseController::class);
    Route::get('expenses/items/{categoryId}', [ExpenseController::class, 'getItemsByCategory'])->name('expenses.items');
    Route::get('/transactions', [TransactionReportController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [TransactionReportController::class, 'export'])->name('transactions.export');
});

// Profile / Change Password Routes
Route::group([
    'prefix' => 'profile',
    'as' => 'profile.',
    'middleware' => ['web', 'auth', 'auth.gates']  // Same here for profile routes
], function () {
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
    }
});
