<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::define('manage-users', fn ($user): bool => $user->canManageUsers());
        Gate::define('view-audit-logs', fn ($user): bool => $user->canViewAuditLogs());
        Gate::define('create-records', fn ($user): bool => $user->canCreateRecords());
        Gate::define('edit-records', fn ($user): bool => $user->canEditRecords());
        Gate::define('delete-records', fn ($user): bool => $user->canDeleteRecords());
        Gate::define('export-data', fn ($user): bool => $user->canExportData());

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by(strtolower((string) $request->input('email')).'|'.$request->ip());
        });
    }
}
