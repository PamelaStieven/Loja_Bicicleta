<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiais extends Model
{
    use HasFactory;

    protected $table = 'filiais'; // Nome da tabela no banco de dados

    protected $fillable = [ //autoriza salvamento
        'nome',
        'cnpj',
        'cidade',
        'bairro',
        'numero',
        'rua',
        'telefone',
        'horario_funcionamento',
    ];
}