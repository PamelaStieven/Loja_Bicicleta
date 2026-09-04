<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acessorios extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela no banco de dados
    protected $table = 'acessorios';

    protected $fillable = [
        'nome',
        'categoria',
        'preco',
    ];
}