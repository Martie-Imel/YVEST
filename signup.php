<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cadastro | YVEST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="mobile-menu.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #93c5fd;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text: #111827;
            --text-light: #6b7280;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
        }

        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .auth-subtitle {
            color: var(--text-light);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid var(--border);
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1 class="auth-title">Crie sua conta</h1>
            <p class="auth-subtitle">Preencha seus dados para começar</p>
        </div>
        
        <form method="POST" action="profile_form.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo</label>
                <input type="text" class="form-control" name="nome" id="nome" required>
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" name="cpf" id="cpf" required>
            </div>
            
            <div class="mb-3">
                <label for="data_nasc" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" name="data_nasc" id="data_nasc" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha" required>
            </div>
            
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" name="confirmar_senha" id="confirmar_senha" required>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">Li e aceito os termos de uso</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Criar conta</button>
        </form>
        
        <div class="login-link">
            Já tem uma conta? <a href="login.php">Faça login</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3) value = value.replace(/^(\d{3})/, '$1.');
            if (value.length > 7) value = value.replace(/^(\d{3})\.(\d{3})/, '$1.$2.');
            if (value.length > 11) value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})/, '$1.$2.$3-');
            e.target.value = value.substring(0, 14);
        });
    </script>
</body>
</html>