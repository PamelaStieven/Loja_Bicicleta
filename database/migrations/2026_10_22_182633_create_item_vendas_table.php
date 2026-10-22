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
        $table->unsignedBigInteger('venda_id');
        $table->unsignedBigInteger('bicicleta_id')->nullable();
        $table->unsignedBigInteger('acessorio_id')->nullable();
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->timestamps();

        $table->foreign('venda_id')->references('id')->on('venda')->onDelete('cascade');
        $table->foreign('bicicleta_id')->references('id')->on('bicicleta')->onDelete('set null');
        $table->foreign('acessorio_id')->references('id')->on('acessorios')->onDelete('set null');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('item_vendas');
    }
};
