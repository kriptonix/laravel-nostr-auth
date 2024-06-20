<?php

namespace Kriptonix\LaravelNostrAuth\App\Traits;

use Illuminate\Support\Facades\Route;

trait AddRoutesToWeb
{
    public static function addNostrAuthRoutes()
    {
        Route::middleware('web')
            ->namespace('Kriptonix\LaravelNostrAuth\Http\Controllers')
            ->group(function () {
                Route::post('/nostr-login', 'NostrAuthController@nostrLogin')->name('nostr-login');
            });
    }
}
