<?php

namespace Database\Factories;

use App\Models\Bicicleta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bicicleta>
 */
class BicicletaFactory extends Factory
{
    /**
     * O model correspondente a este factory.
     *
     * @var string
     */
    protected $model = Bicicleta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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