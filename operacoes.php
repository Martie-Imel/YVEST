<?php
include 'config.php';
session_start();

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
    <title>Operações | YVEST</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="mobile-menu.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Manter os mesmos estilos do dashboard */
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

        .logo-dashboard {
            width: 80%;
            margin: 0 auto 30px;
            display: block;
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

        .operation-card {
            transition: all 0.3s ease;
        }

        .operation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .buy-badge {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .sell-badge {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
</head>
<body>
    <!-- Sidebar Atualizada -->
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
                <a class="nav-link" href="simulador.php">
                    <i class="fas fa-calculator"></i> Simulador
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="operacoes.php">
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
            <h1 class="mb-0">Minhas Operações</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newOperationModal">
                <i class="fas fa-plus me-2"></i> Nova Operação
            </button>
        </div>

        <div class="row">
            <!-- Resumo de Operações -->
            <div class="col-md-4">
                <div class="card operation-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total de Operações:</span>
                            <strong>24</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Compras Realizadas:</span>
                            <strong class="text-success">18</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Vendas Realizadas:</span>
                            <strong class="text-danger">6</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Taxas Pagas:</span>
                            <strong class="text-warning">R$ 120,50</strong>
                        </div>
                    </div>
                </div>

                <div class="card operation-card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Filtros</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="operationType" class="form-label">Tipo de Operação</label>
                            <select class="form-select" id="operationType">
                                <option value="all">Todas</option>
                                <option value="buy">Compras</option>
                                <option value="sell">Vendas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="assetType" class="form-label">Tipo de Ativo</label>
                            <select class="form-select" id="assetType">
                                <option value="all">Todos</option>
                                <option value="stock">Ações</option>
                                <option value="fii">FIIs</option>
                                <option value="fixed">Renda Fixa</option>
                                <option value="etf">ETFs</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateRange" class="form-label">Período</label>
                            <select class="form-select" id="dateRange">
                                <option value="all">Todo o período</option>
                                <option value="month">Último mês</option>
                                <option value="quarter">Últimos 3 meses</option>
                                <option value="year">Último ano</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-primary w-100">
                            <i class="fas fa-filter me-2"></i> Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lista de Operações -->
            <div class="col-md-8">
                <div class="card operation-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Histórico de Operações</h5>
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
                                        <th>Data</th>
                                        <th>Ativo</th>
                                        <th>Tipo</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>15/06/2023</td>
                                        <td><strong>BBAS3</strong></td>
                                        <td><span class="badge buy-badge">Compra</span></td>
                                        <td>100</td>
                                        <td>R$ 45,20</td>
                                        <td>R$ 4.520,00</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>10/06/2023</td>
                                        <td><strong>XPLG11</strong></td>
                                        <td><span class="badge buy-badge">Compra</span></td>
                                        <td>50</td>
                                        <td>R$ 90,15</td>
                                        <td>R$ 4.507,50</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>05/06/2023</td>
                                        <td><strong>BOVA11</strong></td>
                                        <td><span class="badge sell-badge">Venda</span></td>
                                        <td>30</td>
                                        <td>R$ 98,75</td>
                                        <td>R$ 2.962,50</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>01/06/2023</td>
                                        <td><strong>Tesouro IPCA+ 2026</strong></td>
                                        <td><span class="badge buy-badge">Compra</span></td>
                                        <td>1</td>
                                        <td>R$ 3.500,00</td>
                                        <td>R$ 3.500,00</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>25/05/2023</td>
                                        <td><strong>CDB Banco XYZ</strong></td>
                                        <td><span class="badge buy-badge">Compra</span></td>
                                        <td>1</td>
                                        <td>R$ 5.000,00</td>
                                        <td>R$ 5.000,00</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                    <tr>
                                        <td>20/05/2023</td>
                                        <td><strong>ITUB4</strong></td>
                                        <td><span class="badge sell-badge">Venda</span></td>
                                        <td>80</td>
                                        <td>R$ 22,40</td>
                                        <td>R$ 1.792,00</td>
                                        <td><span class="badge bg-success">Concluído</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Próxima</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nova Operação -->
    <div class="modal fade" id="newOperationModal" tabindex="-1" aria-labelledby="newOperationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newOperationModalLabel">Nova Operação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="operationForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="operationTypeModal" class="form-label">Tipo de Operação</label>
                                    <select class="form-select" id="operationTypeModal" required>
                                        <option value="buy">Compra</option>
                                        <option value="sell">Venda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assetTypeModal" class="form-label">Tipo de Ativo</label>
                                    <select class="form-select" id="assetTypeModal" required>
                                        <option value="stock">Ação</option>
                                        <option value="fii">FII</option>
                                        <option value="fixed">Renda Fixa</option>
                                        <option value="etf">ETF</option>
                                        <option value="other">Outro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assetCode" class="form-label">Código do Ativo</label>
                                    <input type="text" class="form-control" id="assetCode" placeholder="Ex: BBAS3, XPLG11" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="operationDate" class="form-label">Data da Operação</label>
                                    <input type="date" class="form-control" id="operationDate" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantidade</label>
                                    <input type="number" class="form-control" id="quantity" min="1" value="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="unitPrice" class="form-label">Preço Unitário (R$)</label>
                                    <input type="number" class="form-control" id="unitPrice" min="0.01" step="0.01" value="0.00" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="totalValue" class="form-label">Valor Total (R$)</label>
                                    <input type="text" class="form-control" id="totalValue" readonly value="0.00">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="broker" class="form-label">Corretora</label>
                            <select class="form-select" id="broker" required>
                                <option value="">Selecione</option>
                                <option value="xp">XP Investimentos</option>
                                <option value="rico">Rico</option>
                                <option value="clear">Clear</option>
                                <option value="modal">Modal</option>
                                <option value="other">Outra</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fees" class="form-label">Taxas (R$)</label>
                            <input type="number" class="form-control" id="fees" min="0" step="0.01" value="0.00" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="operationForm" class="btn btn-primary">Salvar Operação</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calcular valor total automaticamente
        document.getElementById('quantity').addEventListener('input', calculateTotal);
        document.getElementById('unitPrice').addEventListener('input', calculateTotal);
        
        function calculateTotal() {
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
            const total = quantity * unitPrice;
            document.getElementById('totalValue').value = total.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Definir data atual como padrão
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const formattedDate = today.toISOString().substr(0, 10);
            document.getElementById('operationDate').value = formattedDate;
            
            // Adicionar validação ao formulário
            document.getElementById('operationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                // Aqui você pode adicionar a lógica para salvar a operação
                alert('Operação salva com sucesso!');
                // Fechar o modal após salvar
                bootstrap.Modal.getInstance(document.getElementById('newOperationModal')).hide();
            });
        });
    </script>
</body>
</html>