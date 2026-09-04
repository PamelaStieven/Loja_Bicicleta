<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::query();

        if ($request->filled('tipo') && $request->filled('valor')) {
            $query->where($request->tipo, 'like', '%' . $request->valor . '%');
        }

        $dados = $query->get();

        return view('usuario.list', compact('dados'));
    }

    public function create()
    {
        // Ao criar, criamos uma instância vazia da model para evitar erro na view
        $dado = new Usuario();

        return view('usuario.form', compact('dado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuario,email',
            'tipo'  => 'required|in:usuario,funcionario',
        ], [
            'nome.required'  => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique'   => 'Este e-mail já está cadastrado.',
            'tipo.required'  => 'Selecione o tipo de usuário.',
        ]);

        Usuario::create($request->all());

        return redirect()->route('usuario.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit($id)
    {
        // Busca o usuário pelo ID para carregar as informações na tela de edição
        $dado = Usuario::findOrFail($id);

        return view('usuario.form', compact('dado'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuario,email,' . $id,
            'tipo'  => 'required|in:usuario,funcionario',
        ]);

        $usuario->update($request->all());

        return redirect()->route('usuario.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return redirect()->route('usuario.index')->with('success', 'Usuário removido com sucesso!');
    }
}