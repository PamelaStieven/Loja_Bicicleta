<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcessoriosSeguranca;

class AcessoriosSegurancaSeeder extends Seeder
{
   
    public function run(): void
    {
        AcessoriosSeguranca::factory()->count(5)->create();
    }
}