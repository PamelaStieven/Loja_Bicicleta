
@extends('main')
@section('titulo', 'Listagem de Bicicletas')

@section('conteudo')
<div class="container my-4">

    <div class="row mb-3">
        <h3>Listagem de Bicicletas</h3>

        <form action="{{ route('bicicleta.search') }}" method="POST">
            @csrf

            <div class="row align-items-end g-2">
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="categoria" {{ request('tipo') == 'categoria' ? 'selected' : '' }}>Categoria</option>
                        <option value="marca" {{ request('tipo') == 'marca' ? 'selected' : '' }}>Marca</option>
                        <option value="modelo" {{ request('tipo', 'modelo') == 'modelo' ? 'selected' : '' }}>Modelo</option>
                        <option value="cor" {{ request('tipo') == 'cor' ? 'selected' : '' }}>Cor</option>
                        <option value="aro" {{ request('tipo') == 'aro' ? 'selected' : '' }}>Aro</option>
                        <option value="quantidade" {{ request('tipo') == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="valor" class="form-label">Valor</label>
                    <input
                        type="text"
                        name="valor"
                        id="valor"
                        class="form-control"
                        placeholder="Pesquisar..."
                        value="{{ request('valor') }}"
                    >
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="{{ route('bicicleta.index') }}" class="btn btn-secondary">Limpar</a>
                    <a href="{{ route('bicicleta.create') }}" class="btn btn-success ms-auto">Novo</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row mt-4">
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Categoria</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Preço</th>
                        <th>Cor</th>
                        <th>Aro</th>
                        <th>Qtd</th>
                        <th class="text-center">Ações</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dados as $item)
                        <tr>
                            <td>{{ $item->id }}</td>

                            <td style="min-width: 120px;">
                                @php
                                    $foto = trim($item->foto ?? '');
                                    $urlFoto = null;
                                    $fotoExiste = false;

                                    if ($foto !== '') {
                                        if (\Illuminate\Support\Str::startsWith($foto, ['https://', 'http://'])) {
                                            $urlFoto = $foto;
                                            $fotoExiste = true;
                                        } else {
                                            $foto = ltrim($foto, '/');

                                            if (\Illuminate\Support\Str::startsWith($foto, 'public/')) {
                                                $foto = substr($foto, 7);
                                            }

                                            if (\Illuminate\Support\Str::startsWith($foto, 'storage/')) {
                                                $urlFoto = asset($foto);
                                                $arquivo = public_path($foto);
                                            } else {
                                                $urlFoto = asset('storage/' . $foto);
                                                $arquivo = storage_path('app/public/' . $foto);
                                            }

                                            $fotoExiste = file_exists($arquivo);
                                        }
                                    }
                                @endphp

                                @if($fotoExiste)
                                    <img
                                        src="{{ $urlFoto }}"
                                        alt="Bicicleta {{ $item->modelo }}"
                                        class="img-thumbnail"
                                        style="width: 100px; height: 80px; object-fit: contain;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';"
                                    >
                                    <span class="text-muted" style="display:none;">
                                        Foto indisponível
                                    </span>
                                @else
                                    <span class="text-muted">Sem foto</span>
                                @endif
                            </td>

                            <td>{{ $item->categoria ?? '-' }}</td>
                            <td>{{ $item->marca }}</td>
                            <td>{{ $item->modelo }}</td>
                            <td>R$ {{ number_format($item->preco, 2, ',', '.') }}</td>
                            <td>{{ $item->cor ?? '-' }}</td>
                            <td>{{ $item->aro ?? '-' }}</td>
                            <td>{{ $item->quantidade ?? '-' }}</td>

                            <td class="text-center" style="width: 80px;">
                                <a
                                    href="{{ route('bicicleta.edit', $item->id) }}"
                                    class="btn btn-warning btn-sm"
                                    title="Editar"
                                >
                                    Editar
                                </a>
                            </td>

                            <td class="text-center" style="width: 80px;">
                                <form
                                    action="{{ route('bicicleta.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir esta bicicleta?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Excluir"
                                    >
                                        Deletar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                Nenhuma bicicleta encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
@stop
