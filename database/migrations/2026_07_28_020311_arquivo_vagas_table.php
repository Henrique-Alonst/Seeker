<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arquivo_vagas', function(Blueprint $table){
            $table->id();

            $table->string('cargo');
            $table->string('empresa');
            $table->string('status')->default('aplicado'); // Caso nenhum status seja selecionado, aplicado fica como padrão.
            $table->string('link')->nullable();

            $table->text('notas')->nullable(); //anotações extras // text aceita textos maiores.
            $table->decimal('salario', 10, 2)->nullable();

            $table->date('data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arquivo_vagas');
    }
};
