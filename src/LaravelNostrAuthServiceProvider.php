<?php

namespace Kriptonix\LaravelNostrAuth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Kriptonix\LaravelNostrAuth\App\Traits\AddRoutesToWeb;

class LaravelNostrAuthServiceProvider extends ServiceProvider
{
    use AddRoutesToWeb;

    public function register()
    {

    }

    public function boot()
    {
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'nostrauth');

        // Add the Nostr Auth routes
        $this->addNostrAuthRoutes();

        if ($this->app->runningInConsole()) {
            /**
             * Publish config php artisan vendor:publish --provider="Kriptonix\LaravelNostrAuth\LaravelNostrAuthServiceProvider" --tag="config"
             */
            $this->publishes([
            __DIR__.'/../config/nostr-auth.php' => config_path('nostr-auth.php'),
            ], 'config');

            /**
             * Publish view component php artisan vendor:publish --provider="Kriptonix\LaravelNostrAuth\LaravelNostrAuthServiceProvider" --tag="components"
             */
            $this->publishes([
                //__DIR__.'/../resources/views/' => resource_path('views/vendor/kriptonix/laravel-nostr-auth/'),
                __DIR__.'/../resources/views/components' => resource_path('views/vendor/kriptonix/laravel-nostr-auth/components'),
            ], 'components');

            // Export the migration with php artisan vendor:publish --provider="Kriptonix\LaravelNostrAuth\LaravelNostrAuthServiceProvider" --tag="migrations"
            if (! class_exists('AddNostrPubKeyToUsersTable')) {
                $this->publishes([
                __DIR__ . '/../database/migrations/add_nostr_pubkey_to_users_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_add_nostr_pubkey_to_users_table.php'),
                // you can add any number of migrations here
                ], 'migrations');
            }
        }
    }
}
