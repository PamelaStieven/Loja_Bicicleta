<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;
class FornecedorSeeder extends Seeder
{
    public function run(): void
    {
        Fornecedor::create([
            'nome' => 'Bicicletas Caloi Ltda',
            'produto' => 'Acessórios de segurança  para Bicicletas',
            'email' => 'contato@caloi.com',
            'telefone' => '(11) 98888-1111',
            'endereco' => 'São Paulo, SP',
            'cnpj' => '12.345.678/0001-90',
            'prazo_entrega' => 7,
            'valor' => 50.00,
            'status' => 'Ativo',
            'link_site' => 'https://www.caloi.com.br',
            'redes_sociais' => '@caloi',
        ]);

        Fornecedor::create([
            'nome' => 'Specialized Brasil',
            'produto' => 'Acessórios',
            'email' => 'suporte@specialized.com',
            'telefone' => '(19) 97777-2222',
            'endereco' => 'Campinas, SP',
            'cnpj' => '23.456.789/0001-01',
            'prazo_entrega' => 5,
            'valor' => 100.00,
            'status' => 'Ativo',
            'link_site' => 'https://www.specialized.com',
            'redes_sociais' => '@specialized',
        ]);

        Fornecedor::create([
            'nome' => 'Trek Bicycle Corp',
            'produto' => 'Bicicletas e Acessórios',
            'email' => 'vendas@trekbikes.com',
            'telefone' => '(41) 96666-3333',
            'endereco' => 'Curitiba, PR',
            'cnpj' => '98.765.432/0001-12',
            'prazo_entrega' => 10,
            'valor' => 200.00,
            'status' => 'Ativo',
            'link_site' => 'https://www.trekbikes.com',
            'redes_sociais' => '@trekbikes',
        ]);

        Fornecedor::create([
            'nome' => 'Sense Bike Distribuidora',
            'produto' => 'Peças para Bicicletas',
            'email' => 'comercial@sensebike.com.br',
            'telefone' => '(31) 95555-4444',
            'endereco' => 'Belo Horizonte, MG',
            'cnpj' => '54.321.678/0001-34',
            'prazo_entrega' => 8,
            'valor' => 150.00,
            'status' => 'Ativo',
            'link_site' => 'https://www.sensebike.com.br',
            'redes_sociais' => '@sensebike',
        ]);
    }
}