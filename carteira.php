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
    <title>Carteira | YVEST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="mobile-menu.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos mantidos do dashboard */
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

        .investment-card {
            transition: all 0.3s ease;
        }

        .investment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .logo-dashboard {
            width: 80%;
            margin: 0 auto 30px;
            display: block;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .progress-thin {
            height: 6px;
        }

        .positive-change {
            color: var(--success);
        }

        .negative-change {
            color: var(--danger);
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
                <a class="nav-link active" href="carteira.php">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Minha Carteira</h1>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Adicionar Investimento
            </button>
        </div>

        <div class="row">
            <!-- Resumo da Carteira -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Valor Total:</span>
                            <strong>R$ 30.000,00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Rendimento (mês):</span>
                            <strong class="positive-change">+ R$ 1.200,00 (4%)</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Taxas:</span>
                            <strong class="negative-change">- R$ 50,00</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Liquidez:</span>
                            <strong>R$ 5.000,00</strong>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Alocação por Tipo</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="allocationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Investimentos -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Meus Investimentos</h5>
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Pesquisar...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ativo</th>
                                        <th>Valor Investido</th>
                                        <th>Rendimento</th>
                                        <th>% Carteira</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Ações BBAS3</strong>
                                            <div class="text-muted small">Banco do Brasil</div>
                                        </td>
                                        <td>R$ 8.000,00</td>
                                        <td class="positive-change">+ 12%</td>
                                        <td>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-primary" style="width: 26.6%"></div>
                                            </div>
                                            <small>26.6%</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Tesouro IPCA+ 2026</strong>
                                            <div class="text-muted small">Tesouro Direto</div>
                                        </td>
                                        <td>R$ 6.000,00</td>
                                        <td class="positive-change">+ 6.5%</td>
                                        <td>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-success" style="width: 20%"></div>
                                            </div>
                                            <small>20%</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>CDB Banco XYZ</strong>
                                            <div class="text-muted small">CDB 110% CDI</div>
                                        </td>
                                        <td>R$ 5.000,00</td>
                                        <td class="positive-change">+ 8.2%</td>
                                        <td>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-warning" style="width: 16.6%"></div>
                                            </div>
                                            <small>16.6%</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Fundo Imobiliário XPLG11</strong>
                                            <div class="text-muted small">Shopping X</div>
                                        </td>
                                        <td>R$ 4.500,00</td>
                                        <td class="positive-change">+ 5.8%</td>
                                        <td>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-info" style="width: 15%"></div>
                                            </div>
                                            <small>15%</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ETF BOVA11</strong>
                                            <div class="text-muted small">Índice Bovespa</div>
                                        </td>
                                        <td>R$ 3.500,00</td>
                                        <td class="negative-change">- 2.1%</td>
                                        <td>
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-danger" style="width: 11.6%"></div>
                                            </div>
                                            <small>11.6%</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de Alocação
        const allocationCtx = document.getElementById('allocationChart').getContext('2d');
        const allocationChart = new Chart(allocationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Ações', 'Tesouro', 'CDB', 'FII', 'ETF'],
                datasets: [{
                    data: [8000, 6000, 5000, 4500, 3500],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: R$ ${value.toLocaleString('pt-BR')} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>