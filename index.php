<?php
include 'config.php';

// Verifica se o usuário está logado
if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .welcome { font-size: 24px; margin-bottom: 20px; }
        .logout { color: #d9534f; text-decoration: none; }
        .logout:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</div>
        <p>Você está na área restrita do sistema.</p>
        <a href="logout.php" class="logout">Sair</a>
    </div>
</body>
</html>