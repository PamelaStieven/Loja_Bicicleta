<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Bicicleta;
use App\Models\Acessorios;
use App\Models\Venda; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::factory(10)->create();
        Bicicleta::factory(10)->create();
        Acessorios::factory(10)->create();
        Venda::factory(10)->create(); 
    }
}