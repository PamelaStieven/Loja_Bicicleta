<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedor', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('produto', 255);
            $table->string('email', 255);
            $table->string('telefone', 20);
            $table->string('endereco', 255);
            $table->string('cnpj', 18)->unique();
            $table->integer('prazo_entrega')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('link_site', 255)->nullable();
            $table->string('redes_sociais', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor');
    }
};