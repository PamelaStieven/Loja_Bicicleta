<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;


class FornecedorFactory extends Factory
{
        public function definition(): array {
        return [
            'nome_fantasia' => $this->faker->word(),
            'produto' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'endereco' => $this->faker->address(),
            'prazo_entrega' => $this->faker->numberBetween(1, 30),
            'valor' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}