<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;

    protected $table = 'item_vendas';

    protected $fillable = [
        'venda_id',
        'bicicleta_id',
        'acessorio_id',
        'quantidade',
        'preco_unitario',
        'valor_total', // <--- Adicionado
    ];

    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id');
    }

    public function acessorio()
    {
        return $this->belongsTo(Acessorios::class, 'acessorio_id');
    }

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
}