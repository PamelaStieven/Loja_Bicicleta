<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;
class FornecedorSeeder extends Seeder
{
    public function run(): void
    {
        Fornecedor::create([
            'nome_fantasia' => 'Bicicletas Caloi Ltda',
            'produto' => 'Acessórios de segurança  para Bicicletas',
            'email' => 'contato@caloi.com',
            'telefone' => '(11) 98888-1111',
            'endereco' => 'São Paulo, SP',
            'prazo_entrega' => 7,
            'valor' => 50.00,
        ]);

        Fornecedor::create([
            'nome_fantasia' => 'Specialized Brasil',
            'produto' => 'Acessórios',
            'email' => 'suporte@specialized.com',
            'telefone' => '(19) 97777-2222',
            'endereco' => 'Campinas, SP',
            'prazo_entrega' => 5,
            'valor' => 100.00,
        ]);

        Fornecedor::create([
            'nome_fantasia' => 'Trek Bicycle Corp',
            'produto' => 'Bicicletas e Acessórios',
            'email' => 'vendas@trekbikes.com',
            'telefone' => '(41) 96666-3333',
            'endereco' => 'Curitiba, PR',
            'prazo_entrega' => 10,
            'valor' => 200.00,
        ]);

        Fornecedor::create([
            'nome_fantasia' => 'Sense Bike Distribuidora',
            'produto' => 'Peças para Bicicletas',
            'email' => 'comercial@sensebike.com.br',
            'telefone' => '(31) 95555-4444',
            'endereco' => 'Belo Horizonte, MG',
            'prazo_entrega' => 8,
            'valor' => 150.00,
        ]);
    }
}