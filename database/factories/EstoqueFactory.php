<?php

namespace Database\Factories;

use App\Models\Estoque;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstoqueFactory extends Factory
{
    protected $model = Estoque::class;

    public function definition(): array
    {   // Gera dados falsos para o modelo Estoque
        return [
            'produto' => $this->faker->randomElement(['Bicicleta Caloi Aro 29', 'Capacete de Ciclismo', 'Luva de Proteção', 'Bomba de Ar Portátil', 'Farol LED Recarregável', 'Kit Remendo Pneu']),
            'quantidade' => $this->faker->numberBetween(1, 50),
            'preco' => $this->faker->randomFloat(2, 30, 2500),
        ];
    }
}