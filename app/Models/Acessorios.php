<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acessorios extends Model
{
    use HasFactory;

    protected $table = 'acessorios';

    protected $fillable = [ //autoriza salvamento
        'nome',
        'preco',
        'quantidade',
    ];
}