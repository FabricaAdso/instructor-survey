<style>
    /* Encapsulamos todo bajo .header-container */
    .header-container .site-header {
        background-color: #fff;
        border-bottom: 1px solid #ccc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 0 1rem;
        height: 90px;
        position: relative;
    }

    .header-container .site-header .container {
        max-width: auto;
        margin: 0 auto;
        display: flex;
        align-items: center; /* Centrado vertical */
        justify-content: space-between;
        gap: 1rem;
        height: 100%;
        padding: 0px; /* Evitar conflicto con otras .container */
    }

    /* Sección Izquierda */
    .header-container .site-header .header-left {
        flex: 0 0 auto;
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
    }
    .header-container .site-header .header-nav ul li a {
        text-decoration: none;
        color: #1F2937;
        font-weight: 500;
        transition: color 0.3s ease;
        padding: 0.5rem;
    }

    /* Botón logout */
    .header-container .site-header .header-logout button,
    .header-container .site-header .header-logout div[type="submit"] {
        background: none;
        border: none;
        color: #388E3C;
        font-size: 0.875rem;
        cursor: pointer;
        transition: color 0.3s ease;
        padding: 0.5rem;
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
    .header-container .site-header .header-logout button:hover,
    .header-container .site-header .header-logout div[type="submit"]:hover {
        color: #1a4fb7;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .header-container .site-header .header-title {
            font-size: 1.2rem;
            padding-left: 0.5rem;
        }
        .header-container .site-header .header-nav ul {
            gap: 1rem;
        }
    }

    @media (max-width: 650px) {
        .container{
            width: 100%
        }
        .header-container .site-header .menu-toggle {
            display: block;
        }

        .header-container .site-header .header-right {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-direction: column;
            padding: 0.5rem; /* Reducido de 1rem */
            gap: 0.75rem;    /* Reducido de 1.5rem */
        }
        .header-container .site-header .header-right.show {
            display: flex;
        }

        .header-container .site-header .header-nav ul {
            flex-direction: column;
            align-items: center;
            width: 100%;
            gap: 0.5rem; /* Añadido para controlar espacio entre ítems */
        }
        .header-container .site-header .header-nav ul li {
            width: 100%;
            text-align: center;
            border-bottom: 1px solid #eee; /* Línea separadora sutil */
        }
        .header-container .site-header .header-nav ul li a {
            display: block;
            padding: 0.25rem 0.5rem; /* Reducido verticalmente */
        }

        /* Ajustes para el div[type="submit"] */
        .header-container .site-header #btnLogout {
            background: none;
            border: none;
            color: #2563eb;
            font-size: 0.875rem;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 0.5rem;
        }
        .header-container .site-header #btnLogout:hover {
            color: #1a4fb7;
        }
    }
    </style>

    <!-- ENCAPSULADO EN .header-container -->
    <div class="header-container">
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
                            <li><a href="{{ route('admin.dashboard') }}">Encuesta</a></li>
                            <li><a href="{{ route('reportsClose') }}">Reporte</a></li>
                        </ul>
                    </nav>

                    <!-- Ahora el logout está dentro de .header-right -->
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
