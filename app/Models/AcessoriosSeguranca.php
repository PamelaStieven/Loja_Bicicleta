<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessoriosSeguranca extends Model
{
    use HasFactory;

    protected $table = 'acessoriosseguranca'; // Nome da tabela no banco de dados

    protected $fillable = [ //autoriza salvamento
        'nome',
        'marca',
        'tamanho',
        'preco',
        'quantidade',
        'estoque',
    ];
}