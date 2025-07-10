// mobile-menu.js
document.addEventListener('DOMContentLoaded', function() {
  // Criar botão de toggle
  const menuToggle = document.createElement('button');
  menuToggle.className = 'mobile-menu-toggle';
  menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
  menuToggle.setAttribute('aria-label', 'Abrir menu');
  document.body.appendChild(menuToggle);
  
  // Alternar menu
  const sidebar = document.querySelector('.sidebar');
  
  menuToggle.addEventListener('click', function() {
    sidebar.classList.toggle('active');
    const icon = menuToggle.querySelector('i');
    
    if (sidebar.classList.contains('active')) {
      icon.classList.remove('fa-bars');
      icon.classList.add('fa-times');
      menuToggle.setAttribute('aria-label', 'Fechar menu');
    } else {
      icon.classList.remove('fa-times');
      icon.classList.add('fa-bars');
      menuToggle.setAttribute('aria-label', 'Abrir menu');
    }
  });
  
  // Fechar menu ao clicar em um item (mobile)
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth < 768) {
        sidebar.classList.remove('active');
        const icon = menuToggle.querySelector('i');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
        menuToggle.setAttribute('aria-label', 'Abrir menu');
      }
    });
  });
  
  // Ajustar menu ao redimensionar a tela
  window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
      sidebar.classList.add('active');
    } else {
      sidebar.classList.remove('active');
      const icon = menuToggle.querySelector('i');
      icon.classList.remove('fa-times');
      icon.classList.add('fa-bars');
      menuToggle.setAttribute('aria-label', 'Abrir menu');
    }
  });
});

// Melhorias para mobile
document.addEventListener('DOMContentLoaded', function() {
  // Atualizar valor do range no simulador
  const rangeInput = document.querySelector('.form-range');
  if (rangeInput) {
    const periodValue = document.getElementById('periodValue');
    rangeInput.addEventListener('input', function() {
      periodValue.textContent = this.value + ' anos';
    });
  }
  
  // Alternar entre abas
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      // Remove active de todos os botões
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active');
      });
      
      // Adiciona active no clicado
      this.classList.add('active');
      
      // Aqui você pode adicionar lógica para mostrar/conteúdo correspondente
    });
  });
  
  // Evitar zoom em inputs no mobile
  document.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('focus', function() {
      window.scrollTo(0, 0);
      document.body.style.zoom = "1.0";
    });
  });
});