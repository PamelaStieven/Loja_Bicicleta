<?php

namespace Database\Factories;

use App\Models\Filiais;
use Illuminate\Database\Eloquent\Factories\Factory;


class FiliaisFactory extends Factory
{
        public function definition(): array {
        return [
            'nome' => fake()->name(),
            'cnpj' => fake()->numerify('##.###.###/####-##'),
            'cidade' => fake()->city(),
            'bairro' => fake()->word(), 
            'numero' => fake()->buildingNumber(),
            'rua' => fake()->streetName(),
            'telefone' => fake()->phoneNumber(),
            'horario_funcionamento' => fake()->time('H:i') . ' às ' . fake()->time('H:i'),
        ];
    }
}