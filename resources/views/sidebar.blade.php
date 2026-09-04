<div class="list-group shadow-sm">
    <a href="{{ route('usuario.index') }}" 
       class="list-group-item list-group-item-action {{ request()->routeIs('usuario.*') ? 'active' : '' }}">
        Usuários
    </a>
    <a href="{{ route('bicicleta.index') }}" 
       class="list-group-item list-group-item-action {{ request()->routeIs('bicicleta.*') ? 'active' : '' }}">
        Bicicletas
    </a>
    <a href="{{ route('acessorios.index') }}" 
       class="list-group-item list-group-item-action {{ request()->routeIs('acessorios.*') ? 'active' : '' }}">
        Acessórios
    </a>
    <a href="{{ route('venda.index') }}" 
       class="list-group-item list-group-item-action {{ request()->routeIs('venda.*') ? 'active' : '' }}">
        Vendas
    </a>
</div>