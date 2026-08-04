<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppgController;
use App\Http\Controllers\SasaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaSebaranController;

/*
|--------------------------------------------------------------------------
| ROUTE USER / PUBLIC
|--------------------------------------------------------------------------
| Tidak perlu login
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'userDashboard'])
    ->name('user.dashboard');

Route::get('/peta-sebaran', [PetaSebaranController::class, 'userIndex'])
    ->name('user.peta');

Route::get('/data-sppg', [SppgController::class, 'userIndex'])
    ->name('user.sppg.index');

Route::get('/data-sppg/{id}', [SppgController::class, 'userShow'])
    ->name('user.sppg.show');

Route::get('/data-sppg/{id}/sasaran', [SasaranController::class, 'userIndex'])
    ->name('user.sasaran.index');

Route::get('/statistik', [DashboardController::class, 'userStatistik'])
    ->name('user.statistik');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
| Wajib Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Peta Sebaran
        |--------------------------------------------------------------------------
        */

        Route::get('/peta-sebaran', [PetaSebaranController::class, 'index'])
            ->name('peta.index');

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        Route::get('/statistik', [DashboardController::class, 'statistik'])
            ->name('admin.statistik');

        /*
        |--------------------------------------------------------------------------
        | CRUD SPPG
        |--------------------------------------------------------------------------
        */

        Route::get('/sppg', [SppgController::class, 'index'])
            ->name('sppg.index');

        Route::get('/sppg/create', [SppgController::class, 'create'])
            ->name('sppg.create');

        Route::post('/sppg/store', [SppgController::class, 'store'])
            ->name('sppg.store');

        Route::get('/sppg/{id}', [SppgController::class, 'show'])
            ->name('sppg.show');

        Route::get('/sppg/{id}/edit', [SppgController::class, 'edit'])
            ->name('sppg.edit');

        Route::post('/sppg/{id}/update', [SppgController::class, 'update'])
            ->name('sppg.update');

        Route::post('/sppg/{id}/delete', [SppgController::class, 'destroy'])
            ->name('sppg.destroy');

        /*
        |--------------------------------------------------------------------------
        | CRUD SASARAN
        |--------------------------------------------------------------------------
        */

        Route::get('/sppg/{id}/sasaran', [SasaranController::class, 'index'])
            ->name('sasaran.index');

        Route::get('/sppg/{id}/sasaran/create', [SasaranController::class, 'create'])
            ->name('sasaran.create');

        Route::post('/sppg/{id}/sasaran', [SasaranController::class, 'store'])
            ->name('sasaran.store');

        Route::get('/sasaran/{id}/edit', [SasaranController::class, 'edit'])
            ->name('sasaran.edit');

        Route::post('/sasaran/{id}/update', [SasaranController::class, 'update'])
            ->name('sasaran.update');

        Route::post('/sasaran/{id}/delete', [SasaranController::class, 'destroy'])
            ->name('sasaran.destroy');

    });