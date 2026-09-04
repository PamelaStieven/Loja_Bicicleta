<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'venda';

    protected $fillable = [
        'usuario_id',
        'funcionario_id',
        'bicicleta_id',
        'acessorio_id',
        'quantidade',
        'valor_total',
        'data_venda',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Usuario::class, 'funcionario_id');
    }

    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id');
    }

    public function acessorio()
    {
        return $this->belongsTo(Acessorios::class, 'acessorio_id');
    }
}