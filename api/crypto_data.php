<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/binance_api.php';

header('Content-Type: application/json');

// Configuração de CORS
$allowed_origins = [
    'https://yvest.com.br', 
    'https://www.yvest.com.br',
    'http://localhost'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Methods: GET");
}

try {
    // Verificar autenticação
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        throw new Exception('Acesso não autorizado', 401);
    }

    $binance = new BinanceAPI();

    // Validar parâmetros
    if (!isset($_GET['symbol'])) {
        throw new Exception('Parâmetro "symbol" é obrigatório', 400);
    }

    $symbol = strtoupper($_GET['symbol']);
    if (!preg_match('/^[A-Z0-9]{5,12}$/', $symbol)) {
        throw new Exception('Formato de símbolo inválido', 400);
    }

    // Processar diferentes tipos de requisição
    if (isset($_GET['interval']) && isset($_GET['limit'])) {
        $allowed_intervals = ['1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M'];
        $interval = $_GET['interval'];
        
        if (!in_array($interval, $allowed_intervals)) {
            throw new Exception('Intervalo não suportado', 400);
        }

        $limit = min((int)$_GET['limit'], 1000);
        $data = $binance->getKLines($symbol, $interval, $limit);
        
        if (isset($data['error'])) {
            throw new Exception($data['error'], 500);
        }
        
        // Formatar dados históricos
        $formatted = array_map(function($item) {
            return [
                'timestamp' => $item[0],
                'open' => $item[1],
                'high' => $item[2],
                'low' => $item[3],
                'close' => $item[4],
                'volume' => $item[5]
            ];
        }, $data);
        
        echo json_encode($formatted);
    } else {
        // Requisição de preço atual
        $data = $binance->getTickerPrice($symbol);
        if (isset($data['error'])) {
            throw new Exception($data['error'], 500);
        }
        echo json_encode([
            'symbol' => $data['symbol'],
            'price' => $data['price'],
            'timestamp' => time()
        ]);
    }

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'error' => $e->getMessage(),
        'code' => $e->getCode() ?: 500
    ]);
}
?>