<?php

namespace Digikraaft\PaystackWebhooks;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PaystackWebhooksServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();

        $this->registerRoutes();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paystackwebhooks.php', 'paystackwebhooks');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/paystackwebhooks.php' => config_path('paystackwebhooks.php'),
        ], 'config');
    }

    protected function registerRoutes()
    {
        //load routes for webhooks
        Route::group([
            'prefix' => config('paystackwebhooks.webhook_path'),
            'namespace' => 'Digikraaft\PaystackWebhooks\Http\Controllers',
            'as' => 'paystack.',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }
}
