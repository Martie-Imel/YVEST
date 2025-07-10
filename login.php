<?php
    require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>YVEST | Plataforma de Investimentos Inteligentes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="mobile-menu.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #93c5fd;
            --accent: #3b82f6;
            --success: #10b981;
            --text: #111827;
            --text-light: #6b7280;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Efeito de partículas no fundo */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .login-container {
            min-height: 100vh;
            overflow: hidden;
        }

        .left-section {
            background-color: var(--card-bg);
            padding: 3rem;
            position: relative;
            box-shadow: var(--shadow);
            border-radius: 0 16px 16px 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-section {
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .right-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            animation: pulse 15s infinite alternate;
            z-index: -1;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0.8; }
        }

        .welcome-text {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            background: linear-gradient(90deg, var(--text), var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
        }

        .welcome-text::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .yvest-blue {
            color: var(--primary);
            font-weight: 900;
        }

        .subtitle {
            color: var(--text-light);
            margin-bottom: 2rem;
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            max-width: 100%;
        }

        .form-label {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .form-control {
            border-radius: 12px;
            padding: 1rem 3rem 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid var(--border);
            transition: var(--transition);
            font-size: 1rem;
            box-shadow: none;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
            background: none;
            border: none;
            padding: 0;
            font-size: 1rem;
            height: 1rem;
            line-height: 1;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            padding: 1rem;
            width: 100%;
            border-radius: 12px;
            border: none;
            transition: var(--transition);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .create-account {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .create-account-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .create-account-link:hover {
            text-decoration: underline;
        }

        .testimonial-title {
            font-size: clamp(1.5rem, 3vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 2rem;
            line-height: 1.3;
        }

        .testimonial-text {
            font-style: italic;
            font-size: clamp(1rem, 2vw, 1.3rem);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .testimonial-author {
            font-weight: 600;
            font-size: 1rem;
        }

        .features-list {
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .feature-icon {
            font-size: 1.2rem;
            color: white;
            margin-top: 0.2rem;
        }

        /* Responsividade aprimorada */
        @media (max-width: 992px) {
            .left-section {
                border-radius: 0;
                padding: 2rem 1.5rem;
            }
            
            .right-section {
                display: none;
            }
            
            .form-control {
                padding: 0.875rem 2.5rem 0.875rem 1rem;
            }
            
            .toggle-password {
                right: 1rem;
            }
            
            .btn-login {
                padding: 0.875rem;
            }
        }

        @media (max-width: 576px) {
            .left-section {
                padding: 1.5rem 1rem;
            }
            
            .welcome-text {
                font-size: 1.8rem;
            }
            
            .create-account {
                margin-bottom: 1.5rem;
                font-size: 0.9rem;
            }
            
            .form-control {
                font-size: 0.9rem;
            }
            
            .btn-login {
                font-size: 0.85rem;
            }
        }
        #homem_
        {
            width: 60px;
            height: 60px;
            top: 701px;
            left: 965px;
            border-radius: 50%;
        }

        .logo-responsive
        {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Efeito de partículas -->
    <div id="particles-js"></div>
    
    <div class="container-fluid login-container">
        <div class="row g-0 min-vh-100">
            <!-- Seção Esquerda (Login) -->
            <div class="col-lg-6 left-section animate__animated animate__fadeIn">
                <img src="<?php echo BASE_URL; ?>/img/logo.png" 
     alt="YVEST Logo" 
     class="logo-responsive"
     loading="lazy"
     width="300" 
     height="100">
                <div class="create-account">
                    <span>Não tem uma conta? <a href="signup.php" class="create-account-link">Crie uma! <i class="fas fa-arrow-right"></i></a></span>
                </div>
                
                <h1 class="welcome-text">BEM VINDO AO <span class="yvest-blue">Y</span>VEST</h1>
                <p class="subtitle">Faça login e aproveite o que o YVEST tem a oferecer.</p>
                <?php if(isset($_SESSION['erro_login'])): ?>
                    <div class="error"><?php echo $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></div>
                <?php endif; ?>
                <form method="POST" action="auth.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope" style="color: var(--text-light);"></i>
                            Digite seu e-mail
                        </label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="seu@email.com" required>
                    </div>
                    
                    <div class="mb-3 password-container">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock" style="color: var(--text-light);"></i>
                            Digite sua senha
                        </label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Manter conectado</label>
                        </div>
                        <a href="#" class="text-decoration-none" style="color: var(--primary);">Esqueceu a senha?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Entrar
                    </button>
                </form>
            </div>
            
            <!-- Seção Direita (Depoimento) -->
            <div class="col-lg-6 right-section animate__animated animate__fadeIn">
                <h2 class="testimonial-title">Acompanhe seus investimentos.</h2>
                <p class="testimonial-text">"O sistema é incrível. Autorretribui meus investimentos com inteligência e trouxe muito mais tranquilidade no dia a dia."</p>
                <p class="testimonial-author"><img src="<?php echo BASE_URL; ?>/img/homem_.png" id="homem_" alt="Carlos">  Carlos M., Investidor desde 2021</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <i class="fas fa-chart-line feature-icon"></i>
                        <div>
                            <h5 class="feature-title">Relatórios de desempenho</h5>
                            <p class="feature-desc">Análises detalhadas do seus investimentos</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <div>
                            <h5 class="feature-title">Segurança certificada</h5>
                            <p class="feature-desc">Proteção bancária para seus dados</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-lightbulb feature-icon"></i>
                        <div>
                            <h5 class="feature-title">Recomendações inteligentes</h5>
                            <p class="feature-desc">Sugestões baseadas em IA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Inicializa particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: "#ffffff" },
                    shape: { type: "circle" },
                    opacity: { value: 0.3, random: true },
                    size: { value: 3, random: true },
                    line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.2, width: 1 },
                    move: { enable: true, speed: 2, direction: "none", random: true, straight: false, out_mode: "out" }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "grab" },
                        onclick: { enable: true, mode: "push" }
                    }
                }
            });
            
            // Toggle password visibility - CORRIGIDO
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash');
                eyeIcon.classList.toggle('fa-eye');
            });
        });
    </script>
</body>
</html>