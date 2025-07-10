<?php
include 'config.php';

if(!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard | YVEST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
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

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        .greeting {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .positive {
            color: var(--success);
        }

        .negative {
            color: var(--danger);
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

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .timeframe-btn.active {
            background-color: var(--primary);
            color: white;
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
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="carteira.php">
                    <i class="fas fa-wallet"></i> Carteira
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="simulador.php">
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
        <h1 class="greeting">Bom dia, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
        <p class="text-muted mb-4">Aqui está um panorama da sua carteira hoje.</p>

        <div class="row">
            <!-- Saldo Atual -->
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Saldo Atual</h5>
                        <h2 class="card-text">R$ 30.000</h2>
                        <p class="positive"><i class="fas fa-arrow-up"></i> Crescente</p>
                        <p class="text-muted">+ R$ 1.000</p>
                    </div>
                </div>
            </div>

            <!-- Perda -->
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Perda</h5>
                        <h2 class="card-text">R$ 500</h2>
                        <p class="negative"><i class="fas fa-arrow-down"></i> Qtd. Movimentação</p>
                        <p class="text-muted">10 operações</p>
                    </div>
                </div>
            </div>

            <!-- Resumo -->
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Resumo</h5>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-1">Investimentos</p>
                                <p class="mb-1">Rendimentos</p>
                                <p class="mb-1">Taxas</p>
                            </div>
                            <div class="text-end">
                                <p class="mb-1 positive">5</p>
                                <p class="mb-1 positive">R$ 1.200</p>
                                <p class="mb-1 negative">R$ 50</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evolução da Carteira (Agora em colunas) -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Evolução da Carteira</span>
                        <div class="btn-group" id="timeframe-selector">
                            <button type="button" class="btn btn-sm btn-outline-primary active" data-timeframe="daily">Diário</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-timeframe="weekly">Semanal</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-timeframe="monthly">Mensal</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="evolutionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking de Investimentos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Ranking Investimentos
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>1. Ações BB</span>
                                <span class="badge bg-primary rounded-pill">80%</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>2. CDB</span>
                                <span class="badge bg-warning rounded-pill">30%</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>3. Ações Google</span>
                                <span class="badge bg-danger rounded-pill">30%</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>4. Tesouro Direto</span>
                                <span class="badge bg-success rounded-pill">15%</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dados para os gráficos (agora em formato de colunas)
        const chartData = {
            daily: {
                labels: ['8h', '10h', '12h', '14h', '16h', '18h', '20h'],
                datasets: [{
                    label: 'Valor da Carteira',
                    data: [28000, 28500, 29000, 29200, 29500, 30000, 30000],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(16, 185, 129, 0.7)'
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            weekly: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Valor da Carteira',
                    data: [25000, 26000, 27000, 27500, 28000, 29000, 30000],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)'
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            monthly: {
                labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
                datasets: [{
                    label: 'Valor da Carteira',
                    data: [20000, 22000, 25000, 30000],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)'
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(37, 99, 235, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        };

        // Configuração do gráfico de colunas
        const config = {
            type: 'bar',
            data: chartData.daily,
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
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                barPercentage: 0.6,
                categoryPercentage: 0.8
            }
        };

        // Inicializar gráfico
        const ctx = document.getElementById('evolutionChart').getContext('2d');
        const evolutionChart = new Chart(ctx, config);

        // Alternar entre períodos de tempo
        document.querySelectorAll('#timeframe-selector button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('#timeframe-selector button').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                const timeframe = this.dataset.timeframe;
                evolutionChart.data = chartData[timeframe];
                evolutionChart.update();
            });
        });
    </script>
</body>
</html>