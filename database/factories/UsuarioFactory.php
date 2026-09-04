<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nome'            => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'telefone'        => $this->faker->phoneNumber(),
            'tipo'            => $this->faker->randomElement(['usuario', 'funcionario']),
            'cpf'             => $this->faker->numerify('###.###.###-##'),
            'data_nascimento' => $this->faker->date('Y-m-d', '-18 years'),
        ];
    }
}