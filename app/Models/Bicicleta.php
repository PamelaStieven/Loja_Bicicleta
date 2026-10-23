<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',
        'categoria',
        'marca',
        'modelo',
        'preco',
        'cor',
        'aro',
        'quantidade',
    ];
}