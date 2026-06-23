<?php

namespace App\Providers;

use App\Auth\PlaintextUserProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Auth::provider('accounts_plaintext', function ($app, array $config) {
            return new PlaintextUserProvider($app['hash'], $config['model']);
        });
    }
}
