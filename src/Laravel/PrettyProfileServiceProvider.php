<?php

namespace Cable8mm\PrettyProfile\Laravel;

use Illuminate\Support\ServiceProvider;

class PrettyProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/pretty-profile.php',
            'pretty-profile'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/pretty-profile.php' => config_path('pretty-profile.php'),
        ], 'pretty-profile-config');
    }
}
