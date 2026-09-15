<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AidDistributionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrphanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/login', [AuthController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

if (app()->hasDebugModeEnabled()) {
    Route::get('/__runtime', function () {
        $hosts = [];
        foreach (['DATABASE_URL', 'DATABASE_URL_UNPOOLED', 'DB_URL', 'POSTGRES_URL'] as $name) {
            $value = env($name);
            $hosts[$name] = $value ? (parse_url($value, PHP_URL_HOST) ?: 'present') : 'empty';
        }

        return response()->json($hosts);
    });
}

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/families', [FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/create', [FamilyController::class, 'create'])->middleware('role:admin,data_entry');
    Route::post('/families', [FamilyController::class, 'store'])->middleware('role:admin,data_entry');
    Route::get('/families/{family}', [FamilyController::class, 'show'])->name('families.show');
    Route::get('/families/{family}/edit', [FamilyController::class, 'edit'])->middleware('role:admin,data_entry');
    Route::put('/families/{family}', [FamilyController::class, 'update'])->middleware('role:admin,data_entry');
    Route::delete('/families/{family}', [FamilyController::class, 'destroy'])->middleware('role:admin');

    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
    Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->middleware('role:admin,data_entry');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->middleware('role:admin,data_entry');
    Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])->name('beneficiaries.show');
    Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])->middleware('role:admin,data_entry');
    Route::put('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])->middleware('role:admin,data_entry');
    Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])->middleware('role:admin');

    Route::get('/orphans', [OrphanController::class, 'index'])->name('orphans.index');
    Route::get('/orphans/create', [OrphanController::class, 'create'])->middleware('role:admin,data_entry');
    Route::post('/orphans', [OrphanController::class, 'store'])->middleware('role:admin,data_entry');
    Route::get('/orphans/{orphan}', [OrphanController::class, 'show'])->name('orphans.show');
    Route::get('/orphans/{orphan}/edit', [OrphanController::class, 'edit'])->middleware('role:admin,data_entry');
    Route::put('/orphans/{orphan}', [OrphanController::class, 'update'])->middleware('role:admin,data_entry');
    Route::delete('/orphans/{orphan}', [OrphanController::class, 'destroy'])->middleware('role:admin');

    Route::get('/aid-distributions', [AidDistributionController::class, 'index'])->name('aid.index');
    Route::get('/aid-distributions/create', [AidDistributionController::class, 'create'])->middleware('role:admin,data_entry');
    Route::post('/aid-distributions', [AidDistributionController::class, 'store'])->middleware('role:admin,data_entry');
    Route::get('/aid-distributions/{aidDistribution}', [AidDistributionController::class, 'show'])->name('aid.show');
    Route::get('/aid-distributions/{aidDistribution}/edit', [AidDistributionController::class, 'edit'])->middleware('role:admin,data_entry');
    Route::put('/aid-distributions/{aidDistribution}', [AidDistributionController::class, 'update'])->middleware('role:admin,data_entry');

    Route::get('/audit-logs', AuditLogController::class)->middleware('role:admin')->name('audit.index');

    Route::get('/reports', ReportController::class)->name('reports');
    Route::get('/export/{type}', ExportController::class)->middleware('role:admin')->name('export');
});
