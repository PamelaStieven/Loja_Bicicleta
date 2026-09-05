<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BikeSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
        }
        .card-login {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container" style="max-width: 420px;">
    <div class="card card-login bg-white p-4">
        <div class="text-center mb-4">
            <div class="display-6 text-primary mb-2">
                <i class="bi bi-bicycle"></i>
            </div>
            <h4 class="fw-bold text-dark">BikeSystem</h4>
            <p class="text-muted small">Insira suas credenciais para acessar o sistema</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold text-secondary">E-mail ou Usuário</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0 bg-light" placeholder="nome@exemplo.com" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 bg-light" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
            </button>

            <hr class="my-3 text-muted">

            <!-- Botão / Link de Cadastro -->
            <div class="text-center">
                <span class="text-muted small">Ainda não tem conta?</span>
                <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm w-100 mt-2 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i> Criar Nova Conta
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>