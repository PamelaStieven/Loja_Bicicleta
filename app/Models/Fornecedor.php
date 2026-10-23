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

    /**
     * Relacionamento com o Funcionário/Usuário responsável.
     */
    public function funcionario()
    {
        // Se a relação for com a model User/Funcionario apontando para a chave funcionario_id:
        return $this->belongsTo(User::class, 'funcionario_id');

        // Se o seu sistema tiver uma model específica Funcionario.php, altere para:
        // return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }
}