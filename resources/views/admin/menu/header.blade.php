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
        align-items: center;
        /* Centrado vertical */
        justify-content: space-between;
        gap: 1rem;
        height: 100%;
        padding: 0;
        /* Evita conflicto con otros .container */
        max-width: auto;
        /* Puedes ajustar el ancho máximo */
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


    .header-container .site-header .menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #1F2937;
        cursor: pointer;
        padding: 0.5rem;
    }

    .header-container .site-header .header-nav ul li a:hover {
        color: #388E3C;
    }

    .header-container .site-header .header-nav ul li a:hover {
        color: #388E3C;
    }

    @media (max-width: 700px) {
        .header-container .site-header .container {
            flex-wrap: nowrap;
            height: 90px;
        }

        .header-container .site-header .header-title {
            font-size: 1.25rem;
            font-weight: 500;
        }

        .header-container .site-header .header-left {
            flex: 1;
        }

        .header-container .site-header .menu-toggle {
            display: block;
        }


        .header-container .site-header .header-right {
            display: none;
            position: absolute;
            top: 90px;
            right: 15px;
            width: 100px;
            background-color: #fff;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            z-index: 99;
        }

        .header-container .site-header .header-nav ul {
            display: flex;
            flex-direction: column
        }
    }

    header nav ul li a {
        display: inline-block;

        text-decoration: none;
        color: #333;
        padding: 10px 15px;
        font-weight: 500;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 4px;
    }

    header nav ul li a:hover,
    header nav ul li a.active {
        transform: translateY(-5px);
    }
</style>

<div class="header-container">
    <header class="site-header">
        <div class="container">
            <div class="header-left">
                <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="logo">
                <h1 class="header-title">Encuesta de Satisfacción</h1>
                <title></title>
            </div>
            <button class="menu-toggle" id="menu-toggle" aria-expanded="false">☰</button>
            <div class="header-right" id="header-right">
                <nav class="header-nav" aria-label="Menú principal">
                    <ul>
                        <li style="font-size: 1.2rem"><a href="{{ route('admin.dashboard') }}">Encuesta</a></li>
                        <li style="font-size: 1.2rem"><a href="{{ route('reportsClose') }}">Reporte</a></li>
                    </ul>
                </nav>
                <div class="header-logout">
                    <form action="{{ route('logout.admin') }}" method="POST">
                        @csrf
                        <button style="font-size: 1.2rem" type="submit" id="btnLogout">Cerrar sesión</button>
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
        if (headerRight.style.display === 'flex') {
            headerRight.style.display = 'none';
        } else {
            headerRight.style.display = 'flex';
            headerRight.style.flexDirection = 'column';
        }
    });

    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 700) {
            if (!menuToggle.contains(e.target) && !headerRight.contains(e.target)) {
                headerRight.style.display = 'none';
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.textContent = '☰';
            }
        }
    });
</script>
