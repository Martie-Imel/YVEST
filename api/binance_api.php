<?php
/**
 * Classe para integração com a API da Binance - Versão Corrigida
 */
class BinanceAPI {
    private $base_url = 'https://api.binance.com/api/v3/';
    private $last_request = 0;
    private $min_interval = 200; // 200ms entre requisições
    private $cache_time = 300; // 5 minutos de cache
    
    /**
     * Obtém o preço atual de um par de negociação
     */
    public function getTickerPrice($symbol) {
        $url = $this->base_url . 'ticker/price?symbol=' . $symbol;
        return $this->makeRequest($url);
    }
    
    /**
     * Obtém dados históricos de candles
     */
    public function getKLines($symbol, $interval = '1d', $limit = 30) {
        // Intervalos permitidos
        $allowed_intervals = ['1m','3m','5m','15m','30m','1h','2h','4h','6h','8h','12h','1d','3d','1w','1M'];
        
        if (!in_array($interval, $allowed_intervals)) {
            return ['error' => 'Intervalo não suportado. Use: ' . implode(', ', $allowed_intervals)];
        }

        $url = $this->base_url . 'klines?symbol=' . $symbol . '&interval=' . $interval . '&limit=' . $limit;
        return $this->makeRequest($url);
    }
    
    /**
     * Faz a requisição à API com tratamento de cache e rate limiting
     */
    private function makeRequest($url) {
        // Verificar cache
        $cache_file = dirname(__DIR__) . '/cache/' . md5($url) . '.json';
        if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $this->cache_time) {
            return json_decode(file_get_contents($cache_file), true);
        }
        
        // Rate limiting
        $now = microtime(true) * 1000;
        $wait = $this->last_request + $this->min_interval - $now;
        if ($wait > 0) {
            usleep($wait * 1000);
        }
        
        // Configurar cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: YVEST/1.0'
            ]
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        $this->last_request = microtime(true) * 1000;
        
        // Tratar erros
        if ($error) {
            return ['error' => "Erro na conexão: $error"];
        }
        
        if ($http_code !== 200) {
            return ['error' => "API retornou código $http_code"];
        }
        
        $data = json_decode($response, true);
        
        // Salvar em cache se não houver erro
        if (!isset($data['error'])) {
            if (!file_exists(dirname($cache_file))) {
                mkdir(dirname($cache_file), 0755, true);
            }
            file_put_contents($cache_file, $response);
        }
        
        return $data;
    }
}
?>