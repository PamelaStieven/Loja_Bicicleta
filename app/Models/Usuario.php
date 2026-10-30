<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'telefone',
        'login',
        'senha',
        'tipo',
    ];

    protected $hidden = [
        'senha',
    ];

    // Verifica se o usuário é cliente
    public function isCliente()
    {
        return $this->tipo === 'usuario';
    }

    // Verifica se o usuário é funcionário
    public function isFuncionario()
    {
        return $this->tipo === 'funcionario';
    }

    // Vendas realizadas para este cliente
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'usuario_id');
    }

    // Vendas atendidas por este funcionário
    public function vendasComoFuncionario()
    {
        return $this->hasMany(Venda::class, 'funcionario_id');
    }
}
