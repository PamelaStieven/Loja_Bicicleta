<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fornecedor;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedor';

    protected $fillable = [
        'nome',
        'produto',
        'email',
        'telefone',
        'cnpj',
        'endereco',
        'prazo_entrega',
        'valor',
        'funcionario_id', 
        'status',
        'link_site',
        'redes_sociais',
    ];

   
    public function funcionario()
    {
        return $this->belongsTo(User::class, 'funcionario_id');

    }
}