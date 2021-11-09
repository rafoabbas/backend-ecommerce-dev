<?php

namespace Modules\Ecommerce\App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = null;

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->configureRateLimiting();

        $this->routes(function (){


            Route::domain(config('app.api_domain'))
                ->middleware('api')
                ->as('api.')
                ->namespace($this->namespace)
                ->group(module_path('Ecommerce', '/routes/api.php'));

            Route::domain(config('app.domain'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(module_path('Ecommerce', '/routes/web.php'));

            Route::domain(config('app.vendor_vendor'))
                ->as('vendor.')
                ->middleware('web')
                ->group(module_path('Ecommerce', '/routes/vendor.php'));

            Route::domain(config('app.admin_vendor'))
                ->as('admin.')
                ->middleware('web')
                ->group(module_path('Ecommerce', '/routes/admin.php'));



        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
