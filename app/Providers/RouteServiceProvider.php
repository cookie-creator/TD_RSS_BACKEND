<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::prefix('api/v1/auth')
                ->middleware('api')
                ->name('api.v1.auth.')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/auth.php'));

            Route::prefix('api/v1/feed')
                ->middleware('api')
                ->name('api.v1.feed.')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/feed.php'));

            Route::prefix('api/v1')
                ->middleware(['api', 'auth:sanctum'])
                ->name('api.v1.post.')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/posts.php'));

            Route::prefix('api/v1')
                ->middleware(['api', 'auth:sanctum'])
                ->name('api.v1.category.')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/categories.php'));

            Route::prefix('api/v1/notifications')
                ->middleware(['api', 'auth:sanctum'])
                ->name('api.v1.notifications')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/notification.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
