<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AccountFrontController;
use App\Http\Controllers\TopupFrontController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\TopupPackageController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/accounts', [AccountFrontController::class, 'index'])->name('accounts.index');
Route::get('/jastip', [AccountFrontController::class, 'jastip'])->name('accounts.jastip');
Route::get('/accounts/{slug}', [AccountFrontController::class, 'show'])->name('accounts.show');
Route::post('/accounts/{slug}/share', [AccountFrontController::class, 'share'])->name('accounts.share');
Route::get('/topup', [TopupFrontController::class, 'index'])->name('topup.index');

use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/update-web', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Website berhasil diupdate! Cache sudah dibersihkan dan database sudah dimigrasi.';
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('accounts', AccountController::class);
    Route::delete('accounts/images/{id}', [AccountController::class, 'deleteImage'])->name('accounts.images.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('topup-packages', TopupPackageController::class);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
    Route::get('backup/download', [\App\Http\Controllers\Admin\BackupController::class, 'backup'])->name('backup.download');
    Route::post('backup/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
    
    Route::get('security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('security.index');
    Route::post('security', [\App\Http\Controllers\Admin\SecurityController::class, 'update'])->name('security.update');
});
