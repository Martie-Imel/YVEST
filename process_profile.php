<?php
include 'config.php';
session_start();

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e sanitizar os dados
    $dados_usuario = [
        'nome' => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING),
        'cpf' => filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING),
        'data_nasc' => filter_input(INPUT_POST, 'data_nasc', FILTER_SANITIZE_STRING),
        'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        
        // Dados do perfil de investimento
        'experiencia' => filter_input(INPUT_POST, 'experiencia', FILTER_SANITIZE_STRING),
        'investimentos' => isset($_POST['investimentos']) ? implode(',', $_POST['investimentos']) : '',
        'objetivo' => filter_input(INPUT_POST, 'objetivo', FILTER_SANITIZE_STRING),
        'prazo' => filter_input(INPUT_POST, 'prazo', FILTER_SANITIZE_STRING),
        'reacao' => filter_input(INPUT_POST, 'reacao', FILTER_SANITIZE_STRING),
        'risco' => filter_input(INPUT_POST, 'risco', FILTER_SANITIZE_STRING),
        'renda' => filter_input(INPUT_POST, 'renda', FILTER_SANITIZE_STRING),
        'reserva' => filter_input(INPUT_POST, 'reserva', FILTER_SANITIZE_STRING)
    ];
    
    try {
        // Iniciar transação
        $conn->begin_transaction();
        
        // 1. Inserir na tabela usuario
        $sql_usuario = "INSERT INTO usuario (nome, cpf, data_nasc, email, senha) VALUES (?, ?, ?, ?, ?)";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("sssss", 
            $dados_usuario['nome'],
            $dados_usuario['cpf'],
            $dados_usuario['data_nasc'],
            $dados_usuario['email'],
            $dados_usuario['senha']
        );
        $stmt_usuario->execute();
        $user_id = $stmt_usuario->insert_id;
        $stmt_usuario->close();
        
        // 2. Inserir na tabela perfil_investidor
        $sql_perfil = "INSERT INTO perfil_investidor (
            id_usuario, experiencia, investimentos_conhecidos, objetivo, 
            prazo, reacao_queda, disposicao_risco, faixa_renda, 
            possui_reserva
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_perfil = $conn->prepare($sql_perfil);
        $stmt_perfil->bind_param("issssssss", 
            $user_id,
            $dados_usuario['experiencia'],
            $dados_usuario['investimentos'],
            $dados_usuario['objetivo'],
            $dados_usuario['prazo'],
            $dados_usuario['reacao'],
            $dados_usuario['risco'],
            $dados_usuario['renda'],
            $dados_usuario['reserva']
        );
        $stmt_perfil->execute();
        $stmt_perfil->close();
        
        // Confirmar transação
        $conn->commit();
        
        // Configurar sessão e redirecionar
        $_SESSION['usuario_id'] = $user_id;
        $_SESSION['usuario_nome'] = $dados_usuario['nome'];
        $_SESSION['logado'] = true;
        
        header("Location: index.php");
        exit();
        
    } catch (Exception $e) {
        // Em caso de erro, desfazer transação
        $conn->rollback();
        
        // Log do erro (em produção, usar um sistema de logs adequado)
        error_log("Erro ao cadastrar usuário: " . $e->getMessage());
        
        // Redirecionar com mensagem de erro
        $_SESSION['erro_cadastro'] = "Ocorreu um erro ao cadastrar. Por favor, tente novamente.";
        header("Location: signup.php");
        exit();
    }
} else {
    // Se alguém tentar acessar diretamente sem enviar o formulário
    header("Location: signup.php");
    exit();
}