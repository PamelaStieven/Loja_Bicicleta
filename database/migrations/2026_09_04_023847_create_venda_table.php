<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos com a tabela usuario
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('funcionario_id')->constrained('usuario')->onDelete('cascade');

            // Relacionamentos com Bicicleta ou Acessório
            $table->foreignId('bicicleta_id')->nullable()->constrained('bicicletas')->onDelete('set null');
            $table->foreignId('acessorio_id')->nullable()->constrained('acessorios')->onDelete('set null');

            $table->integer('quantidade')->default(1);
            $table->decimal('valor_total', 10, 2);
            $table->date('data_venda');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda');
    }
};