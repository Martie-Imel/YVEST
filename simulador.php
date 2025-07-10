<?php
include 'config.php';
include 'api/binance_api.php';

if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

$binance = new BinanceAPI();
$topCryptos = [
    'BTCUSDT' => 'Bitcoin',
    'ETHUSDT' => 'Ethereum',
    'BNBUSDT' => 'Binance Coin',
    'ADAUSDT' => 'Cardano',
    'SOLUSDT' => 'Solana'
];

// Obter preços iniciais
$initialPrices = [];
foreach ($topCryptos as $symbol => $name) {
    $data = $binance->getTickerPrice($symbol);
    if (!isset($data['error'])) {
        $initialPrices[$symbol] = $data;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Simulador | YVEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .sidebar {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding: 20px 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .crypto-card {
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }

        .crypto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .positive {
            color: var(--success);
        }

        .negative {
            color: var(--danger);
        }

        .logo-dashboard {
            width: 80%;
            margin: 0 auto 30px;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="<?php echo BASE_URL; ?>/img/logo2.png" alt="YVEST Logo" class="logo-dashboard">
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="carteira.php">
                    <i class="fas fa-wallet"></i> Carteira
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="simulador.php">
                    <i class="fas fa-calculator"></i> Simulador
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="operacoes.php">
                    <i class="fas fa-exchange-alt"></i> Operações
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="mb-4">Simulador de Investimentos</h1>
        
        <div id="apiError" class="alert alert-danger d-none"></div>
        
        <div class="row">
            <!-- Formulário de Simulação -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Parâmetros da Simulação</h5>
                    </div>
                    <div class="card-body">
                        <form id="simulationForm">
                            <div class="mb-3">
                                <label for="initialAmount" class="form-label">Valor Inicial (R$)</label>
                                <input type="number" class="form-control" id="initialAmount" value="1000" min="100" step="100" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="monthlyAmount" class="form-label">Aporte Mensal (R$)</label>
                                <input type="number" class="form-control" id="monthlyAmount" value="500" min="0" step="50" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="timePeriod" class="form-label">Período (anos)</label>
                                <input type="range" class="form-range" id="timePeriod" min="1" max="10" value="5">
                                <div class="d-flex justify-content-between">
                                    <span>1 ano</span>
                                    <span id="timePeriodValue">5 anos</span>
                                    <span>10 anos</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="cryptoSelect" class="form-label">Criptomoeda</label>
                                <select class="form-select" id="cryptoSelect" required>
                                    <?php foreach ($topCryptos as $symbol => $name): ?>
                                        <option value="<?php echo $symbol; ?>"><?php echo "$name ($symbol)"; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                <i class="fas fa-calculator me-2"></i> Simular
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Cotações em Tempo Real -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cotações em Tempo Real</h5>
                        <small class="text-muted" id="lastUpdate">Atualizado em: <?php echo date('H:i:s'); ?></small>
                    </div>
                    <div class="card-body">
                        <?php foreach ($initialPrices as $symbol => $data): ?>
                            <div class="crypto-card p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0"><?php echo $topCryptos[$symbol]; ?></h6>
                                        <small class="text-muted"><?php echo $symbol; ?></small>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-0" id="price-<?php echo $symbol; ?>">
                                            $<?php echo number_format($data['price'], 2); ?>
                                        </h5>
                                        <small class="text-muted">24h: <span class="positive">+2.5%</span></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Resultados da Simulação -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resultado da Simulação</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3>Projeção de Valor Futuro</h3>
                            <h2 class="text-success" id="finalAmount">R$ 0,00</h2>
                        </div>
                        
                        <div class="chart-container">
                            <canvas id="simulationChart"></canvas>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Investido</h6>
                                        <p class="card-text" id="totalInvested">R$ 0,00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Juros Acumulados</h6>
                                        <p class="card-text text-success" id="totalInterest">R$ 0,00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Retorno Anualizado</h6>
                                        <p class="card-text text-primary" id="annualReturn">0%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inicializar gráfico
        const simulationCtx = document.getElementById('simulationChart').getContext('2d');
        const simulationChart = new Chart(simulationCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Valor do Investimento',
                    data: [],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });

        // Atualizar valores dos sliders
        document.getElementById('timePeriod').addEventListener('input', function() {
            document.getElementById('timePeriodValue').textContent = this.value + ' anos';
        });

        // Atualizar cotações em tempo real
        function updateCryptoPrices() {
            const symbols = <?php echo json_encode(array_keys($topCryptos)); ?>;
            
            symbols.forEach(symbol => {
                fetch(`api/crypto_data.php?symbol=${symbol}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }
                        
                        const priceElement = document.getElementById(`price-${symbol}`);
                        if (priceElement) {
                            priceElement.textContent = `$${parseFloat(data.price).toFixed(2)}`;
                        }
                        
                        document.getElementById('lastUpdate').textContent = `Atualizado em: ${new Date().toLocaleTimeString()}`;
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        // Atualizar a cada 30 segundos
        updateCryptoPrices();
        setInterval(updateCryptoPrices, 30000);

        // Simulação de investimento
        // Atualize a função que faz a chamada à API
document.getElementById('simulationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const symbol = document.getElementById('cryptoSelect').value;
    const initialAmount = parseFloat(document.getElementById('initialAmount').value);
    const monthlyAmount = parseFloat(document.getElementById('monthlyAmount').value);
    const years = parseInt(document.getElementById('timePeriod').value);
    const months = years * 12;
    
    // Mostrar loading
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Calculando...';
    submitBtn.disabled = true;
    
    // Limpar erros anteriores
    document.getElementById('apiError').classList.add('d-none');
    
    // Resetar gráfico durante o carregamento
    simulationChart.data.labels = ['Carregando dados...'];
    simulationChart.data.datasets[0].data = [0];
    simulationChart.update();
    
    fetch(`api/crypto_data.php?symbol=${symbol}&interval=1M&limit=${months}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.status);
            }
            return response.json();
        })
        .then(historicalData => {
            if (historicalData.error) {
                throw new Error(historicalData.error);
            }
            
            if (!historicalData.length || historicalData.length < months) {
                throw new Error('Dados históricos insuficientes para o período selecionado');
            }
            
            let balance = initialAmount;
            let totalInvested = initialAmount;
            const dataPoints = [{month: 0, value: balance}];
            let monthlyReturns = [];
            
            for (let i = 0; i < historicalData.length; i++) {
                const candle = historicalData[i];
                const openPrice = parseFloat(candle.open);
                const closePrice = parseFloat(candle.close);
                
                if (isNaN(openPrice) || isNaN(closePrice) || openPrice <= 0) {
                    console.warn(`Dados inválidos para o mês ${i+1}`, candle);
                    continue;
                }
                
                const monthlyReturn = (closePrice / openPrice) - 1;
                monthlyReturns.push(monthlyReturn);
                balance = balance * (1 + monthlyReturn) + monthlyAmount;
                totalInvested += monthlyAmount;
                
                dataPoints.push({
                    month: i+1,
                    value: balance
                });
            }
            
            // Calcular retorno anualizado
            const annualizedReturn = calculateAnnualizedReturn(monthlyReturns);
            
            updateSimulationChart(dataPoints, years);
            updateResults(balance, totalInvested, annualizedReturn);
        })
        .catch(error => {
            console.error('Erro na simulação:', error);
            document.getElementById('apiError').textContent = 'Erro na simulação: ' + error.message;
            document.getElementById('apiError').classList.remove('d-none');
            
            // Resetar gráfico em caso de erro
            simulationChart.data.labels = ['Erro ao carregar dados'];
            simulationChart.data.datasets[0].data = [0];
            simulationChart.update();
        })
        .finally(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
});
        function calculateAnnualizedReturn(monthlyReturns) {
            if (monthlyReturns.length === 0) return 0;
            
            const product = monthlyReturns.reduce((acc, ret) => acc * (1 + ret), 1);
            const years = monthlyReturns.length / 12;
            const annualized = Math.pow(product, 1/years) - 1;
            
            return annualized;
        }

        function updateSimulationChart(dataPoints, years) {
            const labels = ['Início'];
            for (let i = 1; i <= years; i++) {
                labels.push(`Ano ${i}`);
            }
            
            simulationChart.data.labels = labels;
            simulationChart.data.datasets[0].data = dataPoints.map(p => p.value);
            simulationChart.update();
        }

        function updateResults(finalAmount, totalInvested, annualizedReturn) {
            const totalInterest = finalAmount - totalInvested;
            
            document.getElementById('finalAmount').textContent = 'R$ ' + finalAmount.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('totalInvested').textContent = 'R$ ' + totalInvested.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('totalInterest').textContent = 'R$ ' + totalInterest.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('annualReturn').textContent = (annualizedReturn * 100).toFixed(2) + '%';
        }
    </script>
</body>
</html>