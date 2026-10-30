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

    protected $casts = [
        'data_venda' => 'datetime',
        'valor_total' => 'decimal:2',
    ];

    // Uma venda pertence a um cliente.
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Uma venda pertence a um funcionário.
    public function funcionario()
    {
        return $this->belongsTo(Usuario::class, 'funcionario_id');
    }

    // Uma venda pode ter vários itens.
    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }

    // Relação mantida para compatibilidade com o código existente.
    public function bicicleta()
    {
        return $this->belongsTo(Bicicleta::class, 'bicicleta_id');
    }

    // Relação mantida para compatibilidade com o código existente.
    public function acessorio()
    {
        return $this->belongsTo(Acessorios::class, 'acessorio_id');
    }
}