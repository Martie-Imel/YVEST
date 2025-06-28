<?php
$servidor = "localhost";
$usuario = "root";
$senha = "root";
$banco = "yvest";

// Criar conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
session_start();
?>