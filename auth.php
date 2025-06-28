<?php
include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['password'];

    // Consulta SQL para verificar o usuário
    $sql = "SELECT id_user, nome, senha FROM usuario WHERE email = ?";
    
    // Prepared statement
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        
        // Verifica a senha
        if(password_verify($senha, $usuario['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['usuario_id'] = $usuario['id_user'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['logado'] = true;
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['erro_login'] = "Senha incorreta!";
        }
    } else {
        $_SESSION['erro_login'] = "Usuário não encontrado!";
    }
    
    $stmt->close();
    header("Location: login.php");
    exit();
}

$conexao->close();
?>