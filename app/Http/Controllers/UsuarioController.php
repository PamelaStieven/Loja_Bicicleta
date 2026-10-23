<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Lê os valores vindo de GET ou POST
        $valor = $request->input('valor');
        $tipo  = $request->input('tipo', 'nome');

        $dados = Usuario::when(!empty($valor), function ($query) use ($tipo, $valor) {
            $query->where($tipo, 'like', '%' . $valor . '%');
        })->get();

        return view('usuario.list', [
            'dados' => $dados,
            'tipo'  => $tipo,
            'valor' => $valor
        ]);
    }

    public function search(Request $request)
    {
        // Executa diretamente a busca processando os dados via POST
        return $this->index($request);
    }

    public function create()
    {
        $dado = new Usuario();
        return view('usuario.form', compact('dado'));
    }

    private function validateForm(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'telefone'        => 'required|string|max:20',
            'tipo'            => 'required|string|max:50',
            'cpf'             => 'required|string|max:14',
            'data_nascimento' => 'required|date',
        ], [
            'nome.required'            => 'O campo nome é obrigatório.',
            'email.required'           => 'O campo e-mail é obrigatório.',
            'email.email'              => 'Informe um e-mail válido.',
            'telefone.required'       => 'O campo telefone é obrigatório.',
            'tipo.required'           => 'O campo tipo é obrigatório.',
            'cpf.required'            => 'O campo CPF é obrigatório.',
            'data_nascimento.required' => 'O campo data de nascimento é obrigatório.',
            'data_nascimento.date'     => 'Informe uma data válida.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Usuario::create($request->all());

        return redirect()->route('usuario.index')->with('success', 'Registro salvo com sucesso!');
    }

    public function edit($id)
    {
        $dado = Usuario::findOrFail($id);
        return view('usuario.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());

        return redirect()->route('usuario.index')->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return redirect()->route('usuario.index')->with('success', 'Registro removido com sucesso!');
    }
}