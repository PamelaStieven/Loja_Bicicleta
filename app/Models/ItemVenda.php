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
        'valor_total',
    ];

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
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