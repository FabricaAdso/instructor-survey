<!-- Header (incluido en tu include) -->
<style>
    /* Estilos generales del header */
    .header-container .site-header {
        background-color: #fff;
        border-bottom: 1px solid #ccc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 0 1rem;
        height: 90px;
        position: relative;
    }

    .header-container .site-header .container {
        margin: 0 auto;
        display: flex;
        align-items: center; /* Centrado vertical */
        justify-content: space-between;
        gap: 1rem;
        height: 100%;
        padding: 0; /* Evita conflicto con otros .container */
        max-width: auto; /* Puedes ajustar el ancho máximo */
    }

    /* Sección Izquierda */
    .header-container .site-header .header-left {
        display: flex;
        align-items: center;
    }
    .header-container .site-header .logo {
        width: 48px;
        height: 48px;
    }
    .header-container .site-header .header-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        padding-left: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Sección Derecha */
    .header-container .site-header .header-right {
        display: flex;
        align-items: center;
        gap: 3.5rem;
    }

    /* Menú de navegación */
    .header-container .site-header .header-nav ul {
        list-style: none;
        display: flex;
        gap: 1.5rem;
        margin: 0;
        padding: 0;
        flex-direction: row;
    }
    .header-container .site-header .header-nav ul li a {
        text-decoration: none;
        color: #1F2937;
        font-weight: 500;
        transition: color 0.3s ease;
        padding: 0.5rem;
    }

    /* Botón logout */
    .header-container .site-header .header-logout button {
        background: none;
        border: none;
        color: #388E3C;
        font-size: 0.875rem;
        cursor: pointer;
        transition: color 0.3s ease;
        padding: 0.5rem;
    }
    .header-container .site-header .header-logout button:hover {
        color: #1a4fb7;
    }


/* Botón menú (hamburguesa) */
.header-container .site-header .menu-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #1F2937;
  cursor: pointer;
  padding: 0.5rem;
}

/* Hover States */
.header-container .site-header .header-nav ul li a:hover {
  color: #388E3C;
}

    /* Hover States */
    .header-container .site-header .header-nav ul li a:hover {
        color: #388E3C;
    }

    /* Responsivo: para anchos menores a 700px */
    @media (max-width: 700px) {
        .header-container .site-header .container {
            flex-wrap: nowrap;
            height: 90px;
        }

        .header-container .site-header .header-title {
    font-size: 1.25rem; /* Tamaño de fuente similar al h3 */
    font-weight: 500;   /* Ajusta según tu diseño */
    /* Puedes agregar otros estilos que normalmente tenga un h3 */
  }

        .header-container .site-header .header-left {
            flex: 1;
        }
        /* En lugar de ocultar la sección derecha, la mostramos en una nueva línea en forma de columna */

        /* Mostrar el botón de menú (hamburguesa) solo si lo necesitas, aquí lo dejamos visible pero opcional */
        .header-container .site-header .menu-toggle {
            display: block;
        }


        .header-container .site-header .header-right {
            display: none;
          position: absolute;
          top: 90px; /* Debajo del header */
          right: 15px; /* Posicionado a la derecha */
          width: 100px;  /* Ancho fijo, ajustable según necesidad */
          background-color: #fff;
          flex-direction: column;
          align-items: flex-start;
          gap: 0.75rem;
          padding: 0.5rem;
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
          border-top-left-radius: 0;
  border-top-right-radius: 0;
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
  z-index: 99;
  }

   /* Menú de navegación */
   .header-container .site-header .header-nav ul {
        display: flex;
        flex-direction: column
    }
    }
  </style>

  <div class="header-container">
      <header class="site-header">
          <div class="container">
              <div class="header-left">
                  <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="logo">
                  <h1 class="header-title">Encuesta de Satisfaccion</h1>
                  <title></title>
              </div>
              <!-- Botón menú (hamburguesa) para dispositivos pequeños -->
              <button class="menu-toggle" id="menu-toggle" aria-expanded="false">☰</button>
              <div class="header-right" id="header-right">
                  <nav class="header-nav" aria-label="Menú principal">
                      <ul>
                          <li><a href="{{ route('admin.dashboard') }}">Encuesta</a></li>
                          <li><a href="{{ route('reportsClose') }}">Reporte</a></li>
                      </ul>
                  </nav>
                  <div class="header-logout">
                      <form action="{{ route('logout.admin') }}" method="POST">
                          @csrf
                          <button type="submit" id="btnLogout">Salir</button>
                      </form>
                  </div>
              </div>
          </div>
      </header>
  </div>

  <script>
  const menuToggle = document.getElementById('menu-toggle');
const headerRight = document.getElementById('header-right');

menuToggle.addEventListener('click', () => {
  const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
  menuToggle.setAttribute('aria-expanded', !isExpanded);
  // Alterna entre el icono de hamburguesa y X
  menuToggle.textContent = isExpanded ? '☰' : '×';
  if (headerRight.style.display === 'flex') {
    headerRight.style.display = 'none';
  } else {
    headerRight.style.display = 'flex';
    headerRight.style.flexDirection = 'column';
  }
});

// Opcional: cerrar el menú si se hace clic fuera del header
document.addEventListener('click', (e) => {
  if (window.innerWidth <= 700) {  // Solo se aplica en dispositivos pequeños
    if (!menuToggle.contains(e.target) && !headerRight.contains(e.target)) {
      headerRight.style.display = 'none';
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.textContent = '☰';
    }
  }
});


  </script>
