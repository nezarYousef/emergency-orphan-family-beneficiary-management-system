<?php

use App\Http\Controllers\AidDistributionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');
Route::get('/login', [AuthController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/families', [FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/create', [FamilyController::class, 'create'])->middleware('role:admin,data_entry')->name('families.create');
    Route::post('/families', [FamilyController::class, 'store'])->middleware('role:admin,data_entry')->name('families.store');
    Route::get('/families/{family}', [FamilyController::class, 'show'])->name('families.show');
    Route::get('/families/{family}/edit', [FamilyController::class, 'edit'])->middleware('role:admin,data_entry')->name('families.edit');
    Route::put('/families/{family}', [FamilyController::class, 'update'])->middleware('role:admin,data_entry')->name('families.update');
    Route::delete('/families/{family}', [FamilyController::class, 'destroy'])->middleware('role:admin')->name('families.destroy');

    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
    Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->middleware('role:admin,data_entry')->name('beneficiaries.create');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->middleware('role:admin,data_entry')->name('beneficiaries.store');
    Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])->name('beneficiaries.show');
    Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])->middleware('role:admin,data_entry')->name('beneficiaries.edit');
    Route::put('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])->middleware('role:admin,data_entry')->name('beneficiaries.update');
    Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])->middleware('role:admin')->name('beneficiaries.destroy');

    Route::get('/orphans', [OrphanController::class, 'index'])->name('orphans.index');
    Route::get('/orphans/create', [OrphanController::class, 'create'])->middleware('role:admin,data_entry')->name('orphans.create');
    Route::post('/orphans', [OrphanController::class, 'store'])->middleware('role:admin,data_entry')->name('orphans.store');
    Route::get('/orphans/{orphan}', [OrphanController::class, 'show'])->name('orphans.show');
    Route::get('/orphans/{orphan}/edit', [OrphanController::class, 'edit'])->middleware('role:admin,data_entry')->name('orphans.edit');
    Route::put('/orphans/{orphan}', [OrphanController::class, 'update'])->middleware('role:admin,data_entry')->name('orphans.update');
    Route::delete('/orphans/{orphan}', [OrphanController::class, 'destroy'])->middleware('role:admin')->name('orphans.destroy');

    Route::get('/aid-distributions', [AidDistributionController::class, 'index'])->name('aid.index');
    Route::get('/aid-distributions/create', [AidDistributionController::class, 'create'])->middleware('role:admin,data_entry')->name('aid.create');
    Route::post('/aid-distributions', [AidDistributionController::class, 'store'])->middleware('role:admin,data_entry')->name('aid.store');
    Route::get('/aid-distributions/{aidDistribution}', [AidDistributionController::class, 'show'])->name('aid.show');
    Route::get('/aid-distributions/{aidDistribution}/edit', [AidDistributionController::class, 'edit'])->middleware('role:admin,data_entry')->name('aid.edit');
    Route::put('/aid-distributions/{aidDistribution}', [AidDistributionController::class, 'update'])->middleware('role:admin,data_entry')->name('aid.update');
    Route::delete('/aid-distributions/{aidDistribution}', [AidDistributionController::class, 'destroy'])->middleware('role:admin')->name('aid.destroy');

    Route::get('/audit-logs', AuditLogController::class)->middleware('role:admin')->name('audit.index');
    Route::resource('users', UserController::class)->except('show')->middleware('role:admin');

    Route::get('/reports', ReportController::class)->name('reports');
    Route::get('/export/{type}', ExportController::class)->name('export');
});
