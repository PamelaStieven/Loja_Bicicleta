<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estoque;

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

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'bicicleta_id');
    }
}
