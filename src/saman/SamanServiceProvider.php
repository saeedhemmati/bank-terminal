<?php

namespace BankTerminal\Saman;

use Illuminate\Support\ServiceProvider;

class SamanServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('saman', function() {
            return new Saman();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/terminals.php', 'terminals');
    }
}
