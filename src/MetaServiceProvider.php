<?php

namespace JoliMardi\Metas;

use Illuminate\Support\ServiceProvider;

class MetasServiceProvider extends ServiceProvider {
    public function boot() {

        // $this->loadViewsFrom(__DIR__ . '/views', 'menu');

        $this->publishes([
            __DIR__ . '/config/metas.yml' => config_path('menu.yml'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/views' => app_path('Services/MetasService.php'),
        ], 'service');
    }


    public function register() {
    }
}
