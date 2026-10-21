<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedor';

    protected $fillable = [
        'nome_fantasia',
        'produto',
        'email',
        'telefone',
        'endereco',
        'prazo_entrega',
        'valor',
    ];
}
