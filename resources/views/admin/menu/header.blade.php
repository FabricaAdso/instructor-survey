<style>
    /* Header con altura fija */
    .site-header {
      background-color: #fff;
      border-bottom: 1px solid #ccc;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 0 1rem;
      height: 90px;
      position: relative;
    }


    .site-header .container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center; /* Centrado vertical */
      justify-content: space-between;
      gap: 1rem;
      height: 100%;
    }

    .container{
        padding: 0px;
    }

    .site-header .header-left {
      flex: 0 0 auto;
      display: flex;
      align-items: center;
    }

    .site-header .logo {
      width: 48px;
      height: 48px;
    }

    .site-header .header-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin: 0;
      padding-left: 1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Elementos a la derecha */
    .header-right {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .header-nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
    }

    .header-nav ul li a {
      text-decoration: none;
      color: #1F2937;
      font-weight: 500;
      transition: color 0.3s ease;
      padding: 0.5rem;
    }

    .header-logout button {
      background: none;
      border: none;
      color: #2563eb;
      margin-top: 24px;
      font-size: 0.875rem;
      cursor: pointer;
      transition: color 0.3s ease;
      padding: 0.5rem;
    }

    .menu-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #1F2937;
      cursor: pointer;
      padding: 0.5rem;
    }

    @media (max-width: 768px) {
      .site-header .header-title {
        font-size: 1.2rem;
        padding-left: 0.5rem;
      }

      .header-nav ul {
        gap: 1rem;
      }
    }

    @media (max-width: 600px) {
    .menu-toggle {
        display: block;
    }

    .header-right {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border-bottom: 1px solid #ccc;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex-direction: column;
        padding: 0.5rem; /* Reducido de 1rem */
        gap: 0.75rem; /* Reducido de 1.5rem */
    }

    .header-right.show {
        display: flex;
    }

    .header-nav ul {
        flex-direction: column;
        align-items: center;
        width: 100%;
        gap: 0.5rem; /* Añadido para controlar espacio entre ítems */
    }

    .header-nav ul li {
        width: 100%;
        text-align: center;
        border-bottom: 1px solid #eee; /* Línea separadora sutil */
    }

    .header-nav ul li a {
        display: block;
        padding: 0.25rem 0.5rem; /* Reducido verticalmente */
    }

    .header-logout button {
        width: 100%;
        padding: 0.25rem 0.5rem; /* Reducido verticalmente */
        margin-top: 0.5rem; /* Espacio superior reducido */
    }
}
  </style>

  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="header-left">
        <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="logo">
        <h1 class="header-title">Encuesta de Acompañamiento</h1>
      </div>
      <button class="menu-toggle" id="menu-toggle" aria-expanded="false">☰</button>
      <div class="header-right" id="header-right">
        <nav class="header-nav" aria-label="Menú principal">
          <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('reportsClose') }}">Reporte Total</a></li>
          </ul>
        </nav>
        <div class="header-logout">
          <form action="{{ route('logout.admin') }}" method="POST">
            @csrf
            <button type="submit">Cerrar sesión</button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const headerRight = document.getElementById('header-right');

    menuToggle.addEventListener('click', () => {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      menuToggle.textContent = isExpanded ? '☰' : '×';
      headerRight.classList.toggle('show');
    });

    // Cerrar menú al hacer click fuera
    document.addEventListener('click', (e) => {
      if (!menuToggle.contains(e.target) && !headerRight.contains(e.target)) {
        headerRight.classList.remove('show');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.textContent = '☰';
      }
    });
  </script>


