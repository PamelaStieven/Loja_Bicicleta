<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendaSeeder extends Seeder
{
    public function run(): void
    {
        // Desativa checagem de chave estrangeira temporariamente
        Schema::disableForeignKeyConstraints();

        $vendas = [];

        for ($i = 1; $i <= 10; $i++) {
            $quantidade = rand(1, 3);
            $valorUnitario = rand(500, 2500);

            $vendas[] = [
                'usuario_id'   => rand(1, 5),
                'bicicleta_id' => rand(1, 5),
                'quantidade'   => $quantidade,
                'valor_total'  => $quantidade * $valorUnitario,
                'data_venda'   => now()->subDays(rand(1, 30)),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        DB::table('vendas')->insert($vendas);

        Schema::enableForeignKeyConstraints();
    }
}