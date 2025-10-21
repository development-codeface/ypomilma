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
// Auth/Profile Controllers
use App\Http\Controllers\Auth\ChangePasswordController;

Route::redirect('/', '/login');

Auth::routes(['register' => false]);

// Admin Routes
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => [ 'auth', 'auth.gates']  // Add 'web' and your custom middleware alias here
], function () {
    Route::redirect('/', '/admin/report')->name('home');

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Report/Dashboard
    Route::resource('report', ReportController::class);

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
