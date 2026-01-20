<?php

namespace JoliMardi\Metas;

use Illuminate\Support\ServiceProvider;

class MetasServiceProvider extends ServiceProvider {
    public function boot() {


        // Migration
        $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations'),
        ], 'migrations');

        // Model
        $this->publishes([
            __DIR__ . '/Models' => app_path('Models'),
        ], 'model');

		// Filament
	    $this->publishes([
		    __DIR__ . '/Filament' => app_path('Filament'),
	    ], 'filament');

    }


    public function register() {

        // Ajout de la commande pour mettre à jour la table
        $this->commands([
            \JoliMardi\Metas\Console\Commands\UpdateMetaTable::class,
        ]);
    }
}
