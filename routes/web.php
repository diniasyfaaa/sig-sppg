<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SppgController;
use App\Http\Controllers\SasaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaSebaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KepalaController;
use App\Http\Controllers\AdminPermohonanController;
use App\Http\Controllers\MenuHarianController;

/*
|--------------------------------------------------------------------------
| ROUTE USER / PUBLIC
|--------------------------------------------------------------------------
| Tidak perlu login
|--------------------------------------------------------------------------
*/

Route::middleware('restrict.kepala')->group(function () {
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
});


/*
|--------------------------------------------------------------------------
| LOGIN & AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE KEPALA SPPG
|--------------------------------------------------------------------------
| Wajib Login & Role kepala_sppg
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kepala_sppg'])
    ->prefix('kepala-sppg')
    ->group(function () {

        Route::get('/dashboard', [KepalaController::class, 'dashboard'])
            ->name('kepala.dashboard');

        Route::get('/permohonan', [KepalaController::class, 'permohonanIndex'])
            ->name('kepala.permohonan.index');

        Route::get('/permohonan/create', [KepalaController::class, 'permohonanCreate'])
            ->name('kepala.permohonan.create');

        Route::post('/permohonan/store', [KepalaController::class, 'permohonanStore'])
            ->name('kepala.permohonan.store');

        Route::get('/permohonan/{id}', [KepalaController::class, 'permohonanShow'])
            ->name('kepala.permohonan.show');

        Route::get('/ganti-password', [ProfileController::class, 'editPassword'])
            ->name('kepala.password');

        /*
        |--------------------------------------------------------------------------
        | Data Sasaran Kepala SPPG
        |--------------------------------------------------------------------------
        */
        Route::get('/sasaran', [KepalaController::class, 'sasaranIndex'])
            ->name('kepala.sasaran.index');

        /*
        |--------------------------------------------------------------------------
        | Menu Harian Kepala SPPG
        |--------------------------------------------------------------------------
        */
        Route::get('/menu-makanan', [MenuHarianController::class, 'kepalaIndex'])
            ->name('kepala.menu.index');

        Route::get('/menu-makanan/create', [MenuHarianController::class, 'kepalaCreate'])
            ->name('kepala.menu.create');

        Route::post('/menu-makanan/store', [MenuHarianController::class, 'kepalaStore'])
            ->name('kepala.menu.store');

        Route::get('/menu-makanan/{id}/edit', [MenuHarianController::class, 'kepalaEdit'])
            ->name('kepala.menu.edit');

        Route::post('/menu-makanan/{id}/update', [MenuHarianController::class, 'kepalaUpdate'])
            ->name('kepala.menu.update');

        Route::post('/menu-makanan/{id}/delete', [MenuHarianController::class, 'kepalaDestroy'])
            ->name('kepala.menu.destroy');

    });


/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
| Wajib Login & Role admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
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
        | Permohonan Update SPPG
        |--------------------------------------------------------------------------
        */

        Route::get('/permohonan', [AdminPermohonanController::class, 'index'])
            ->name('admin.permohonan.index');

        Route::get('/permohonan/{id}', [AdminPermohonanController::class, 'show'])
            ->name('admin.permohonan.show');

        Route::post('/permohonan/{id}/approve', [AdminPermohonanController::class, 'approve'])
            ->name('admin.permohonan.approve');

        Route::post('/permohonan/{id}/reject', [AdminPermohonanController::class, 'reject'])
            ->name('admin.permohonan.reject');

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

        /*
        |--------------------------------------------------------------------------
        | KELOLA USER & PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users/store', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::post('/users/{id}/update', [UserController::class, 'update'])
            ->name('users.update');

        Route::post('/users/{id}/delete', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::get('/ganti-password', [ProfileController::class, 'editPassword'])
            ->name('profile.password');

        Route::post('/ganti-password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password.update');

        /*
        |--------------------------------------------------------------------------
        | ADMIN KELOLA MENU HARIAN SPPG
        |--------------------------------------------------------------------------
        */
        Route::get('/menu-makanan/{sppg_id?}', [MenuHarianController::class, 'adminIndex'])
            ->name('admin.menu.index');

        Route::get('/menu-makanan-create', [MenuHarianController::class, 'adminCreate'])
            ->name('admin.menu.create');

        Route::post('/menu-makanan-store', [MenuHarianController::class, 'adminStore'])
            ->name('admin.menu.store');

        Route::get('/menu-makanan/{id}/edit', [MenuHarianController::class, 'adminEdit'])
            ->name('admin.menu.edit');

        Route::post('/menu-makanan/{id}/update', [MenuHarianController::class, 'adminUpdate'])
            ->name('admin.menu.update');

        Route::post('/menu-makanan/{id}/delete', [MenuHarianController::class, 'adminDestroy'])
            ->name('admin.menu.destroy');

        Route::post('/menu-makanan/{id}/approve', [MenuHarianController::class, 'adminApprove'])
            ->name('admin.menu.approve');

        Route::post('/menu-makanan/{id}/reject', [MenuHarianController::class, 'adminReject'])
            ->name('admin.menu.reject');

    });