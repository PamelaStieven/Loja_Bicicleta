<?php

namespace Database\Factories;

use App\Models\Bicicleta;
use Illuminate\Database\Eloquent\Factories\Factory;


class BicicletaFactory extends Factory
{
    protected $model = Bicicleta::class;

    public function definition(): array
    {
        return [
            'marca'  => $this->faker->randomElement(['Caloi', 'Oggi', 'Sense', 'Specialized']),
            'modelo' => ucfirst($this->faker->word()),
            'preco'  => $this->faker->randomFloat(2, 1200, 15000),
            'cor' => $this->faker->randomElement(['Vermelha', 'Azul', 'Preta', 'Branca']),
            'aro' => $this->faker->randomElement([26, 27, 28]),
            'quantidade' => $this->faker->numberBetween(1, 100),
        ];
    }
}