<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <!-- Contenedor principal -->
    <div class="bg-white max-w-2xl w-full p-6 rounded-lg shadow-lg">
        <!-- Título -->
        <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">Encuesta de Acompañamiento</h1>

        <!-- Información -->
        <p class="text-gray-700 leading-relaxed mb-6 " style="16px">
            Agradecemos que evalúe de 1 a 5 a los instructores acompañantes del proceso formativo, teniendo en cuenta la siguiente escala:
        </p>


        <!-- Escala de valoración -->
        <div class="text-gray-700 space-y-4">
            <p>
                <strong>1.  insatisfecho / Muy en desacuerdo:</strong>
                La experiencia o aspecto evaluado no cumple en absoluto con mis expectativas.
            </p>
            <p>
                <strong>2. Insatisfecho / En desacuerdo:</strong>
                La experiencia o aspecto evaluado no cumple completamente con mis expectativas, pero presenta algunos aspectos positivos.
            </p>
            <p>
                <strong>3. Neutral / Ni acuerdo ni desacuerdo:</strong>
                No tengo una opinión clara o no me siento ni satisfecho ni insatisfecho con este aspecto.
            </p>
            <p>
                <strong>4. Satisfecho / De acuerdo:</strong>
                La experiencia o aspecto evaluado cumple con mis expectativas y tiene algunos puntos destacados.
            </p>
            <p>
                <strong>5. Muy satisfecho / Muy de acuerdo:</strong>
                La experiencia o aspecto evaluado supera ampliamente mis expectativas como aprendiz y es altamente satisfactorio.
            </p>
        </div>

        <br>
        <!-- Botón -->
        <div class="flex justify-center">
            <a href="{{ route('survey.show',['apprenticeId'=>3,'surveyId'=>1]) }}">
                Siguiente
            </a>
        </div>
    </div>
    <br>
    <div x-data="{ page: 1 }">
        <!-- Página 1 -->
        <div :class="{ 'hidden': page !== 1 }">
            <!-- Título -->
            <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">Encuesta de Acompañamiento</h1>
            <!-- Información -->
            <p class="text-gray-700 leading-relaxed mb-6"></p>
            <!-- Escala de valoración -->
            <div class="text-gray-700 space-y-4">
                <p>
                    <strong>1. Muy insatisfecho / Muy en desacuerdo:</strong>
                    La experiencia o aspecto evaluado no cumple en absoluto con mis expectativas.
                </p>

            </div>
        </div>

        <!-- Página 2 -->
        <div :class="{ 'hidden': page !== 2 }">
            2
        </div>

        <!-- Página 3 -->
        <div :class="{ 'hidden': page !== 3 }">
            3
        </div>

        <!-- Página 4 -->
        <div :class="{ 'hidden': page !== 4 }">
            4
        </div>

        <!-- Botones de navegación -->
        <button @click="page--" :disabled="page === 1" class="bg-blue-500 text-white py-2 px-4 rounded">
            Anterior
        </button>
        <button @click="page++" :disabled="page === 4" class="bg-blue-500 text-white py-2 px-4 rounded">
            Siguiente
        </button>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>



</body>
</html>
