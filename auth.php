<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
// Debug: Verificar dados recebidos
error_log("Email recebido: " . $_POST['email']);
error_log("Senha recebida: " . $_POST['password']);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['password'];

    $sql = "SELECT id_user, nome, senha FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt === false) {
        error_log("Erro no prepare: " . $conn->error);
        $_SESSION['erro_login'] = "Erro no sistema. Tente novamente.";
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        error_log("Hash da senha no BD: " . $usuario['senha']);
        
        if(password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id_user'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['logado'] = true;
            
            header("Location: index.php");
            exit();
        } else {
            error_log("Senha incorreta para: " . $email);
            $_SESSION['erro_login'] = "Senha incorreta!";
        }
    } else {
        error_log("Usuário não encontrado: " . $email);
        $_SESSION['erro_login'] = "Usuário não encontrado!";
    }
    
    $stmt->close();
    header("Location: login.php");
    exit();
}

$conn->close();
?>