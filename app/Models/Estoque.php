<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bicicleta;

class Estoque extends Model
{
    use HasFactory;
    //permite preenchimento
    protected $fillable = [
        'produto',
        'quantidade',
        'preco',
        'bicicleta_id',
    ];

    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id');
    }
}
