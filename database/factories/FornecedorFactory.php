<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;


class FornecedorFactory extends Factory
{
        public function definition(): array {
        return [
            'nome' => $this->faker->word(),
            'produto' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'endereco' => $this->faker->address(),
            'cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'prazo_entrega' => $this->faker->numberBetween(1, 30),
            'valor' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['Ativo', 'Inativo']),
            'link_site' => $this->faker->url(),
            'redes_sociais' => $this->faker->word(),
        ];
    }
}