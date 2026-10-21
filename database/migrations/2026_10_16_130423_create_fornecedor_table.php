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
            $table->string('nome_fantasia');
            $table->string('produto');
            $table->string('email');
            $table->string('telefone');
            $table->string('endereco');
            $table->integer('prazo_entrega')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor');
    }
};