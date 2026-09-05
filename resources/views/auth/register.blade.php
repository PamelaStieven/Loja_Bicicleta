<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - BikeSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            height: 100vh;
        }
        .card-register {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container" style="max-width: 420px;">
    <div class="card card-register bg-white p-4">
        <div class="text-center mb-4">
            <div class="display-6 text-primary mb-2">
                <i class="bi bi-person-plus"></i>
            </div>
            <h4 class="fw-bold text-dark">Criar Conta</h4>
            <p class="text-muted small">Preencha os dados abaixo para se cadastrar</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label small fw-semibold text-secondary">Nome Completo</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="name" id="name" class="form-control border-start-0 bg-light" placeholder="Seu Nome" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold text-secondary">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0 bg-light" placeholder="nome@exemplo.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold text-secondary">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 bg-light" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirmar Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 bg-light" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold mb-3">
                <i class="bi bi-check-circle me-1"></i> Cadastrar
            </button>

            <div class="text-center">
                <span class="text-muted small">Já tem uma conta?</span>
                <a href="{{ route('login') }}" class="small fw-bold text-decoration-none text-primary ms-1">Fazer Login</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>