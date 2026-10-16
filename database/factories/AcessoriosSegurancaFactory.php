<?php

namespace Database\Factories;

use App\Models\AcessoriosSeguranca;
use Illuminate\Database\Eloquent\Factories\Factory;


class AcessoriosSegurancaFactory extends Factory
{
        public function definition(): array {
        return [
            'nome' => $this->faker->word(),
            'marca' => $this->faker->word(),
            'tamanho' => $this->faker->word(),
            'preco' => $this->faker->randomFloat(2, 30, 500),
            'quantidade' => $this->faker->numberBetween(1, 100),
            'estoque' => $this->faker->numberBetween(1, 100),
        ];
    }
}