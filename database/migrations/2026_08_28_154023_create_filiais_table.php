<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('filiais', function (Blueprint $table) {
           $table->id();
            $table->string('nome');
            $table->string('cnpj');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('numero');
            $table->string('rua');
            $table->string('telefone');
            $table->string('horario_funcionamento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filiais');
    }
};
