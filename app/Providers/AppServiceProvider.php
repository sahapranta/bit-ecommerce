<?php

namespace App\Providers;

use App\Services\AppHelper;
use App\Services\AppSettings;
use App\Services\BlockService;
use App\Services\PaymentService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('block-service', BlockService::class);
        $this->app->singleton('payment-service', PaymentService::class);
        $this->app->singleton('app-settings', AppSettings::class);
        $this->app->singleton('app-helper', AppHelper::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('backend.partials.sidebar', 'App\View\Composers\AdminMenuComposer');

        Blade::directive('settings', function ($key) {
            return "<?php  if(AppSettings::get($key)): ?>";
        });

        Blade::directive('endsettings', function () {
            return "<?php endif; ?>";
        });
    }
}
