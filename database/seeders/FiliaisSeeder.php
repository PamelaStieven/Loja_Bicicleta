<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\filiais;

class FiliaisSeeder extends Seeder
{
   
    public function run(): void
    {
        Filiais::factory()->count(5)->create();
    }
}