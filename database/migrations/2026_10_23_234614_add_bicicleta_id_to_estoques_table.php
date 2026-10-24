
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->foreignId('bicicleta_id')
                ->nullable()
                ->unique()
                ->constrained('bicicletas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('estoques', function (Blueprint $table) {
            $table->dropForeign(['bicicleta_id']);
            $table->dropUnique(['bicicleta_id']);
            $table->dropColumn('bicicleta_id');
        });
    }
};
