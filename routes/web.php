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
    Route::post('permissions/makeModulePermission', [PermissionController::class, 'makeModulePermission'])
    ->name('permissions.makeModulePermission');
    Route::resource('permissions', PermissionController::class);


});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
