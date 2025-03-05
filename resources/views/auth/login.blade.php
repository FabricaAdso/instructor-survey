<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Encuesta SENA</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">

        <div class="flex flex-col lg:flex-row items-center justify-center min-h-screen bg-white">
            <div class="hidden w-2/5 h-full bg-cover lg:block">
                <img src="../img/imagen.png" alt="Imagen ilustrativa" class="w-80 h-auto">
            </div>

            <div class="flex items-center justify-center w-full max-w-md p-8 bg-white rounded-lg shadow-md lg:w-3/5">
                <div class="w-full space-y-6">
                    <div class="flex justify-center">
                        <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="w-20 h-20">
                    </div>

                    <h1 class="text-2xl font-bold text-center text-gray-700">Encuesta de Acompañamiento y Satisfacción</h1>

                    <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label id="identity_document" for="documento" class="block text-sm font-medium text-gray-600">Documento de Identidad</label>
                            <input type="number" id="identity_document" name="identity_document" placeholder="Ingresa tu documento" class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none" required>
                        </div>
                        <div>
                            <label id="course_code" for="ficha" class="block text-sm font-medium text-gray-600">Ficha</label>
                            <input type="number" id="course_code" name="course_code" placeholder="Ingresa tu ficha" class="block w-full px-4 py-2 mt-1 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 focus:outline-none" required>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 text-white bg-[#38a901] rounded-lg hover:bg-[#38a901] focus:ring focus:ring-green-300">
                                Enviar Código
                        </button>
                    </form>
                    @if ($errors->any())
                        <div>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <div class="text-center">
                    <p class="text-sm text-gray-500">Ingresar como <a href="{{ route('login.admin') }}" class="text-green-500 hover:underline">Administrador</a></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        appearance: none;
    }
</style>
