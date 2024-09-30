<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('metas', function (Blueprint $table) {
            $table->id(); // Champ auto-incrementé
            $table->string('routename')->unique(); // Routename unique
            $table->string('uri')->nullable(); // URI de la route
            $table->string('title')->nullable(); // Titre associé à la route
            $table->text('description')->nullable(); // Description optionnelle
            $table->text('help')->nullable(); // Aide optionnelle
            $table->boolean('locked')->default(false); // Verrouillage optionnel

            $table->timestamps(); // created_at et updated_at
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('metas'); // Supprime la table en rollback
    }
}
