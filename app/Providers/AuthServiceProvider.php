<?php

namespace App\Providers;

use App\Routing\RouteRegistrar;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        self::routes();

        Passport::tokensExpireIn(now()->addDays(15));
    }

    /**
     * This method overwrites Passport::routes() to fit our requirements
     * which also means this is might be a breaking change for future updates
     * unless we do not find a better way to add custom middlewares to the passport routes
     * without extending the Passport architecture we have to keep this updated if needed
     * when passport will release new versions which we need
     *
     * @param null $callback
     * @param array $options
     */
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function ($router) {
            $router->customAll();
        };

        $defaultOptions = [
            'prefix' => 'oauth',
            'namespace' => '\Laravel\Passport\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}
