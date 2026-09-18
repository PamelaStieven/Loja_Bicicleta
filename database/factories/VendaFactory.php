<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios; // Ou Acessorio, conforme o nome no seu projeto

class VendaFactory extends Factory
{
    public function definition(): array
    {
        $quantidade = $this->faker->numberBetween(1, 3);
        $valorUnitario = $this->faker->randomFloat(2, 50, 2000);

        return [
            'usuario_id' => Usuario::factory()->state(['tipo' => 'usuario']),
            'funcionario_id' => Usuario::factory()->state(['tipo' => 'funcionario']),
            'bicicleta_id' => Bicicleta::factory(),
            'acessorio_id' => null, 
            'quantidade' => $quantidade,
            'valor_total' => $quantidade * $valorUnitario,
            'data_venda' => $this->faker->date(),
        ];
    }
}