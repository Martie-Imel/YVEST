<?php
// Adicione estas constantes no seu config.php

// Configurações da API
define('BINANCE_API_URL', 'https://api.binance.com/api/v3/');
define('API_CACHE_TIME', 300); // 5 minutos em segundos

// Configurações de CORS
$allowedOrigins = [
    'https://seusite.com',
    'https://www.seusite.com',
    'http://localhost'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Methods: GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

// Conexão com o banco de dados (existente)
$servidor = "sql104.infinityfree.com";
$usuario = "if0_39341365";
$senha = "WgDuZ1y26eBRff";
$banco = "if0_39341365_yvest";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']));
define('BASE_PATH', realpath(dirname(__FILE__)));

// Criar diretório de cache se não existir
if (!file_exists(BASE_PATH . '/cache')) {
    mkdir(BASE_PATH . '/cache', 0755, true);
}

session_start();
?>