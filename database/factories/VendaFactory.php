<?php

namespace Database\Factories;

use App\Models\Venda;
use App\Models\Usuario;
use App\Models\Funcionario;
use App\Models\Bicicleta;
use App\Models\Acessorio;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendaFactory extends Factory
{
    protected $model = Venda::class;

    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'funcionario_id' => Funcionario::factory(),
            'bicicleta_id' => Bicicleta::factory(),
            'acessorio_id' => Acessorio::factory(),
            'quantidade' => $this->faker->numberBetween(1, 5),
            'data_venda' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

