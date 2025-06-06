<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::post('permissions/makeModulePermission', [PermissionController::class, 'makeModulePermission'])->name('permissions.makeModulePermission');
    Route::put('permissions/updateModulePermission/{permission}', [PermissionController::class, 'updateModulePermission'])->name('permissions.updateModulePermission');

    Route::resource('permissions', PermissionController::class);


Route::resource('categories',\App\Http\Controllers\Admin\CategoryController::class);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
