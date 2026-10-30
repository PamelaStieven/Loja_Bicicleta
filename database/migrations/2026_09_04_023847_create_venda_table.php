<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; //criar e modificar tabelas no banco de dados
use Illuminate\Support\Facades\Schema; //criar e excluir tabelas no banco de dados

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venda', function (Blueprint $table) {
            $table->id();
            // Define as chaves estrangeiras para as tabelas relacionadas
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('funcionario_id')->constrained('usuario')->onDelete('cascade');

            $table->foreignId('bicicleta_id')->nullable()->constrained('bicicletas')->onDelete('set null');
            $table->foreignId('acessorio_id')->nullable()->constrained('acessorios')->onDelete('set null');

            $table->integer('quantidade')->default(1);
            $table->decimal('valor_total', 10, 2);
            $table->date('data_venda');

            $table->timestamps();
        });
    }

    public function down(): void //permite desfazer a migration.
    {
        Schema::dropIfExists('venda'); // Exclui a tabela 'venda' caso ela exista
    }
};