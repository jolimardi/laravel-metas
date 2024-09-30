<?php

namespace JoliMardi\Metas;

use Illuminate\Support\ServiceProvider;

class MetasServiceProvider extends ServiceProvider {
    public function boot() {


        // migrations
        $this->publishes([
            __DIR__ . '/database/migrations/2024_09_30_074449_create_metas_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_metas_table.php'),
        ], 'migrations');

        // Nova + models
        $this->publishes([
            __DIR__ . '/Nova' => app_path('Nova'),
            __DIR__ . '/Models' => app_path('Models'),
        ], 'nova');

    }


    public function register() {

        // Ajout de la commande pour mettre à jour la table
        $this->commands([
            \JoliMardi\Metas\Console\Commands\UpdateMetaTable::class,
        ]);
    }
}
