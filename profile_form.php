<?php
include 'config.php';

// Verificar se o formulário de cadastro foi submetido
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email'])) {
    header("Location: signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Perfil de Investidor | YVEST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
        }

        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .profile-subtitle {
            color: var(--text-light);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid var(--border);
        }

        .form-control:focus, .form-select:focus {
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

        .checkbox-group {
            margin-bottom: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .checkbox-input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1 class="profile-title">TRAÇANDO SEU PERFIL</h1>
            <p class="profile-subtitle">Deixe-nos descobrir os melhores investimentos para você</p>
        </div>
        
        <form action="process_profile.php" method="POST">
            <!-- Dados do usuário (hidden) -->
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>">
            <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($_POST['data_nasc'] ?? ''); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <input type="hidden" name="senha" value="<?php echo htmlspecialchars($_POST['senha'] ?? ''); ?>">
            
            <div class="mb-3">
                <label for="experiencia" class="form-label">Você já investiu em produtos financeiros antes?</label>
                <select id="experiencia" name="experiencia" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Quais os tipos de investimento você já conhece ou já utilizou?</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="poupanca" name="investimentos[]" value="poupanca">
                        Poupança
                    </label>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="renda_fixa" name="investimentos[]" value="renda_fixa">
                        CDB/LCI/LCA
                    </label>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="fundos" name="investimentos[]" value="fundos">
                        Fundos de Investimento
                    </label>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="acoes" name="investimentos[]" value="acoes">
                        Ações
                    </label>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="cripto" name="investimentos[]" value="cripto">
                        Criptoativos
                    </label>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" class="checkbox-input" id="outros" name="investimentos[]" value="outros">
                        Outros
                    </label>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="objetivo" class="form-label">Qual é o seu principal objetivo ao investir?</label>
                <select id="objetivo" name="objetivo" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="aposentadoria">Aposentadoria</option>
                    <option value="reserva">Formar uma reserva</option>
                    <option value="patrimonio">Aumentar meu patrimônio</option>
                    <option value="especifico">Realizar um objetivo específico (comprar casa, carro, etc.)</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="prazo" class="form-label">Em quanto tempo pretende usar o dinheiro investido?</label>
                <select id="prazo" name="prazo" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="curto">Menos de 1 ano</option>
                    <option value="medio">De 1 a 3 anos</option>
                    <option value="longo">De 3 a 5 anos</option>
                    <option value="muito_longo">Mais de 5 anos</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="reacao" class="form-label">Como você reagiria se seu investimento caisse 10% em uma semana?</label>
                <select id="reacao" name="reacao" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="venderia">Venderia tudo imediatamente</option>
                    <option value="venderia_parte">Venderia parte para limitar perdas</option>
                    <option value="manteria">Manteria o investimento</option>
                    <option value="compraria_mais">Compraria mais, aproveitando a oportunidade</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="risco" class="form-label">Você aceitaria correr mais riscos para tentar ganhar mais?</label>
                <select id="risco" name="risco" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="pouco">Não, prefiro segurança</option>
                    <option value="moderado">Sim, mas apenas um pouco mais de risco</option>
                    <option value="alto">Sim, estou disposto a correr riscos maiores</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="renda" class="form-label">Sua renda mensal é:</label>
                <select id="renda" name="renda" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="ate_3">Até 3 salários mínimos</option>
                    <option value="3_a_5">De 3 a 5 salários mínimos</option>
                    <option value="5_a_10">De 5 a 10 salários mínimos</option>
                    <option value="acima_10">Acima de 10 salários mínimos</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="reserva" class="form-label">Você possui uma reserva de emergência?</label>
                <select id="reserva" name="reserva" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Concluir</button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>