<?php

namespace Cable8mm\ViewTransformer;

use Illuminate\Support\ServiceProvider;

class ViewTransformerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/view-transformer.php',
            'view-transformer'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/view-transformer.php' => config_path('view-transformer.php'),
        ], 'view-transformer-config');
    }
}
