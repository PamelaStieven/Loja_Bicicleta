<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acessorios;

class AcessoriosSeeder extends Seeder
{
   
    public function run(): void
    {
        Acessorios::factory()->count(5)->create();
    }
}