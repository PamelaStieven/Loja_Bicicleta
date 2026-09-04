<?php

namespace Database\Factories;

use App\Models\Aluno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aluno>
 */
class AcessoriosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array {
        return [
            'nome' => $this->faker->word(),
            'categoria' => $this->faker->randomElement(['Capacete', 'Luva', 'Farol', 'Cadeado']),
            'preco' => $this->faker->randomFloat(2, 30, 500),
        ];
    }
}