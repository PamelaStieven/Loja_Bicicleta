<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('venda')->onDelete('cascade');
            $table->foreignId('bicicleta_id')->nullable()->constrained('bicicletas')->onDelete('set null');
            $table->foreignId('acessorio_id')->nullable()->constrained('acessorios')->onDelete('set null');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('valor_total', 10, 2)->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_vendas');
    }
};