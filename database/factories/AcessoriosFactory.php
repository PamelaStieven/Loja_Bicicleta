<?php

namespace Database\Factories;

use App\Models\Aluno;
use Illuminate\Database\Eloquent\Factories\Factory;


class AcessoriosFactory extends Factory
{
        public function definition(): array {
        return [
            'nome' => $this->faker->word(),
            'preco' => $this->faker->randomFloat(2, 30, 500),
            'quantidade' => $this->faker->numberBetween(1, 100),
        ];
    }
}