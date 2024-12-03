<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Completada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <header class="bg-white text-gray-800 border-b border-gray-300 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex justify-center">
                <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="w-12 h-12">
            </div>
            <h1 class="text-2xl font-semibold ml-4 flex-grow text-center md:text-left">Encuesta de Acompañamiento</h1>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-3xl font-semibold text-center mb-4">¡Gracias por completar la encuesta!</h2>
            <p class="text-lg mb-6 text-center">
                Tus respuestas serán tomadas en cuenta para optimizar nuestros procesos formativos y asegurar que tu
                formación sea de la mejor calidad. Nos comprometemos a seguir trabajando para brindarte un entorno
                educativo que se ajuste a tus necesidades y expectativas.
            </p>
            <p class="text-lg text-center mb-8">
                ¡Gracias por ser parte de este proceso de mejora continua!
            </p>
            <div class="flex justify-center">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="bg-[#38a901] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-[#38a901] focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300">
                    Finalizar
                </a>
            </div>
        </div>
    </main>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>

</html>
