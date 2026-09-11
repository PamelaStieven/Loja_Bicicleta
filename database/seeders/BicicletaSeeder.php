<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bicicleta;

class BicicletaSeeder extends Seeder
{
    
    public function run(): void
    {
        Bicicleta::factory()->count(5)->create();
    }
}
