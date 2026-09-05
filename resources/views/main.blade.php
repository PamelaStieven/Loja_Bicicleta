<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'BikeSystem') - Gestão de Bicicletas</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-custom {
            background-color: #1e293b;
        }
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background-color: #ffffff;
        }
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        .table-custom thead {
            background-color: #0f172a;
            color: #ffffff;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('bicicleta.index') }}">
                <i class="bi bi-bicycle fs-4 text-warning"></i> BikeSystem
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bicicleta.index') }}"><i class="bi bi-bicycle"></i> Bicicletas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('acessorios.index') }}"><i class="bi bi-tools"></i> Acessórios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('venda.index') }}"><i class="bi bi-cart-check"></i> Vendas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuario.index') }}"><i class="bi bi-people"></i> Usuários</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-light small"><i class="bi bi-person-circle"></i> Operador</span>
                    <!-- Localize na Navbar e substitua o botão Sair por este: -->
<a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-box-arrow-right me-1"></i> Sair
</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container flex-grow-1">
        @yield('conteudo')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-3 mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} BikeSystem — Sistema de Gerenciamento de Vendas e Estoque.
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>