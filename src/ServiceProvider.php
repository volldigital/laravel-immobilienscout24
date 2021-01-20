<?php

namespace VOLLdigital\LaravelImmobilienscout24;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use VOLLdigital\LaravelImmobilienscout24\Server\ImmobilienscoutServer;

class ServiceProvider extends BaseServiceProvider 
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/immobilienscout.php' => config_path('immobilienscout.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
    }

    public function register()
    {
        $this->app->singleton(ImmobilienscoutServer::class, function ($app) {
            return new ImmobilienscoutServer(
                config('immobilienscout.service')
            );
        });
    }

}