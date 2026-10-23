<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Bicicleta;

class VendaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        ItemVenda::truncate();
        Venda::truncate();

        for ($i = 1; $i <= 10; $i++) {
            $quantidade = rand(1, 3);
            $bicicletaId = rand(1, 5);
            
            $bicicleta = Bicicleta::find($bicicletaId);
            $precoUnitario = $bicicleta ? ($bicicleta->preco ?? 1500) : 1500;
            $valorTotal = $quantidade * $precoUnitario;

            $venda = Venda::create([
                'usuario_id'     => rand(1, 5),
                'funcionario_id' => rand(1, 5),
                'valor_total'    => $valorTotal,
                'data_venda'     => now()->subDays(rand(1, 30)),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            ItemVenda::create([
                'venda_id'     => $venda->id,
                'bicicleta_id' => $bicicletaId,
                'acessorio_id' => null,
                'quantidade'   => $quantidade,
                'preco_unitario' => $precoUnitario,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}