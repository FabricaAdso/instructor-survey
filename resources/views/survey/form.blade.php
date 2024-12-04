<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function validateForm(event) {
            let isValid = true;
            const requiredFields = document.querySelectorAll('input[required], select[required], text[required]');

            requiredFields.forEach(field => {
                if (!field.checked && field.type === 'radio') {
                    const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                    if (![...radioGroup].some(radio => radio.checked)) {
                        isValid = false;
                    }
                } else if (field.value.trim() === '') {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
                document.getElementById('warning-message').classList.remove('hidden');
            }
        }
    </script>
</head>

<body class="font-sans bg-gray-100">
    <header class="bg-white text-gray-800 border-b border-gray-300 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex justify-center">
                <img src="../img/logo-sena-verde-complementario-svg-2022.svg" alt="Logo SENA" class="w-12 h-12">
            </div>
            <h1 class="text-2xl font-semibold ml-4 flex-grow text-center md:text-left">Encuesta de Acompañamiento</h1>
            <nav class="hidden md:flex space-x-4">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-green-600 font-semibold hover:text-green-800">
                    Salir
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
            
        </div>
    </header>

    <form action="{{ route('survey.submit', $survey->id) }}" method="POST"
        class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">

        @csrf

        <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg p-6">

            <div id="warning-message" class="bg-yellow-300 text-yellow-800 p-4 rounded-md mb-6 hidden">
                <strong>¡Atención!</strong> Aún te faltan campos por completar. Por favor, responde todas las preguntas
                antes de continuar.
            </div>

            <div x-data="{ page: 1 }">

                <div :class="{ 'hidden': page !== 1 }">
                    <div class="p-6 bg-white rounded-lg shadow-lg border border-gray-300">
                        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">ENCUESTA DE SATISFACCIÓN DEL
                            APRENDIZ EN ETAPA LECTIVA – EJECUCIÓN DE LA FORMACIÓN.</h1>

                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            Evaluar la satisfacción de los aprendices con respecto a la ejecución de la formación en la
                            etapa lectiva,
                            con el fin de identificar áreas de oportunidad para mejorar la calidad del proceso educativo
                            y optimizar las
                            metodologías, recursos y estrategias empleadas, garantizando que las necesidades de los
                            aprendices sean atendidas
                            y que el aprendizaje sea efectivo y de calidad. Los resultados de esta encuesta servirán
                            como base para implementar
                            acciones de mejora continua en los programas de formación.
                        </p>

                        <div class="bg-green-50 p-4 rounded-md mb-6">
                            <h2 class="text-xl font-semibold text-green-700 mb-2">Agradecemos su participación</h2>
                            <p class="text-gray-700">
                                EVALÚE de <strong>1 a 5</strong>, a los instructores acompañantes del proceso formativo,
                                teniendo en cuenta
                                la siguiente escala:
                            </p>
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            <ul class="list-disc pl-5">
                                <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong> - La experiencia o aspecto
                                    evaluado no cumple
                                    en absoluto con mis expectativas.</li>
                                <li><strong>2: Insatisfecho / En desacuerdo</strong> - La experiencia o aspecto evaluado
                                    no cumple completamente
                                    con mis expectativas, pero presenta algunos aspectos positivos.</li>
                                <li><strong>3: Neutral / Ni acuerdo ni desacuerdo</strong> - No tengo una opinión clara
                                    o no me siento ni
                                    satisfecho ni insatisfecho con este aspecto.</li>
                                <li><strong>4: Satisfecho / De acuerdo</strong> - La experiencia o aspecto evaluado
                                    cumple con mis expectativas
                                    y tiene algunos puntos destacados.</li>
                                <li><strong>5: Muy satisfecho / Muy de acuerdo</strong> - La experiencia o aspecto
                                    evaluado supera ampliamente
                                    mis expectativas como aprendiz y es altamente satisfactorio.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div :class="{ 'hidden': page !== 2 }">
                    <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">1. INTEGRALIDAD DEL INSTRUCTOR</h1>
                    <details class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-300">
                        <summary
                            class="font-semibold bg-green-50 text-green-700 py-2 px-4 rounded-md cursor-pointer hover:bg-green-100 transition-all">
                            Escala de Valoración
                        </summary>
                        <div class="mt-2 text-sm text-gray-700">
                            <ul class="list-disc pl-5">
                                <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong></li>
                                <li><strong>2: Insatisfecho / En desacuerdo</strong></li>
                                <li><strong>3: Neutral / Ni de acuerdo ni desacuerdo</strong></li>
                                <li><strong>4: Satisfecho / De acuerdo</strong></li>
                                <li><strong>5: Muy satisfecho / Muy de acuerdo</strong></li>
                            </ul>
                        </div>
                    </details>

                    <div id="question-container" class="overflow-x-auto p-4">
                        @foreach ($survey->questions->slice(0, 6) as $question)
                            <div class="mb-4 p-4 bg-white shadow-lg rounded-lg border border-gray-300">
                                <h4 class="text-2xl font-semibold text-green-700 mb-4">{{ $question->question }}</h4>

                                <table class="min-w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-green-50">
                                            <th class="px-4 py-2 text-left font-medium text-green-700 w-1/2">Instructor
                                            </th>
                                            @foreach ($question->options as $option)
                                                <th class="px-2 py-2 text-center text-sm text-green-700 w-[10%]">
                                                    {{ $option }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructors as $instructor)
                                            <tr class="border-b hover:bg-green-50">
                                                <td class="px-4 py-3 text-sm text-gray-800 w-1/2">
                                                    <strong>{{ $instructor->name }} {{ $instructor->last_name }}
                                                        {{ $instructor->second_last_name }}</strong>
                                                </td>
                                                @foreach ($question->options as $option)
                                                    <td class="px-2 py-2 text-center">
                                                        <input type="radio"
                                                            class="h-6 w-6 text-green-600 border-gray-300 focus:ring-green-500"
                                                            name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                            value="{{ $option }}"
                                                            id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                            required>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div :class="{ 'hidden': page !== 3 }">
                    <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">2. PLANEACION DEL PROCEDIMIENTO DE
                        EJECUCION DE LA FORMACION</h1>
                    <details class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-300">
                        <summary
                            class="font-semibold bg-green-50 text-green-700 py-2 px-4 rounded-md cursor-pointer hover:bg-green-100 transition-all">
                            Escala de Valoración
                        </summary>
                        <div class="mt-2 text-sm text-gray-700">
                            <ul class="list-disc pl-5">
                                <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong></li>
                                <li><strong>2: Insatisfecho / En desacuerdo</strong></li>
                                <li><strong>3: Neutral / Ni de acuerdo ni desacuerdo</strong></li>
                                <li><strong>4: Satisfecho / De acuerdo</strong></li>
                                <li><strong>5: Muy satisfecho / Muy de acuerdo</strong></li>
                            </ul>
                        </div>
                    </details>

                    <div id="question-container" class="overflow-x-auto p-4">
                        @foreach ($survey->questions->slice(6, 4) as $question)
                            <div class="mb-4 p-4 bg-white shadow-lg rounded-lg border border-gray-300">
                                <h4 class="text-2xl font-semibold text-green-700 mb-4">{{ $question->question }}</h4>

                                <table class="min-w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-green-50">
                                            <th class="px-4 py-2 text-left font-medium text-green-700 w-1/2">Instructor
                                            </th>
                                            @foreach ($question->options as $option)
                                                <th class="px-2 py-2 text-center text-sm text-green-700 w-[10%]">
                                                    {{ $option }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructors as $instructor)
                                            <tr class="border-b hover:bg-green-50">
                                                <td class="px-4 py-3 text-sm text-gray-800 w-1/2">
                                                    <strong>{{ $instructor->name }} {{ $instructor->last_name }}
                                                        {{ $instructor->second_last_name }}</strong>
                                                </td>
                                                @foreach ($question->options as $option)
                                                    <td class="px-2 py-2 text-center">
                                                        <input type="radio"
                                                            class="h-6 w-6 text-green-600 border-gray-300 focus:ring-green-500"
                                                            name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                            value="{{ $option }}"
                                                            id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                            required>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div :class="{ 'hidden': page !== 4 }">
                    <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">3. EJECUCION DE LA FORMACION
                        PROFESIONAL</h1>
                    <details class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-300">
                        <summary
                            class="font-semibold bg-green-50 text-green-700 py-2 px-4 rounded-md cursor-pointer hover:bg-green-100 transition-all">
                            Escala de Valoración
                        </summary>
                        <div class="mt-2 text-sm text-gray-700">
                            <ul class="list-disc pl-5">
                                <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong></li>
                                <li><strong>2: Insatisfecho / En desacuerdo</strong></li>
                                <li><strong>3: Neutral / Ni de acuerdo ni desacuerdo</strong></li>
                                <li><strong>4: Satisfecho / De acuerdo</strong></li>
                                <li><strong>5: Muy satisfecho / Muy de acuerdo</strong></li>
                            </ul>
                        </div>
                    </details>

                    <div id="question-container" class="overflow-x-auto p-4">
                        @foreach ($survey->questions->slice(10, 6) as $question)
                            <div class="mb-4 p-4 bg-white shadow-lg rounded-lg border border-gray-300">
                                <h4 class="text-2xl font-semibold text-green-700 mb-4">{{ $question->question }}</h4>

                                <table class="min-w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-green-50">
                                            <th class="px-4 py-2 text-left font-medium text-green-700 w-1/2">Instructor
                                            </th>
                                            @foreach ($question->options as $option)
                                                <th class="px-2 py-2 text-center text-sm text-green-700 w-[10%]">
                                                    {{ $option }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructors as $instructor)
                                            <tr class="border-b hover:bg-green-50">
                                                <td class="px-4 py-3 text-sm text-gray-800 w-1/2">
                                                    <strong>{{ $instructor->name }} {{ $instructor->last_name }}
                                                        {{ $instructor->second_last_name }}</strong>
                                                </td>
                                                @foreach ($question->options as $option)
                                                    <td class="px-2 py-2 text-center">
                                                        <input type="radio"
                                                            class="h-6 w-6 text-green-600 border-gray-300 focus:ring-green-500"
                                                            name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                            value="{{ $option }}"
                                                            id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                            required>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div :class="{ 'hidden': page !== 5 }">
                    <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">4. EVALUACION</h1>
                    <details class="mb-6 p-4 bg-white rounded-lg shadow-md border border-gray-300">
                        <summary
                            class="font-semibold bg-green-50 text-green-700 py-2 px-4 rounded-md cursor-pointer hover:bg-green-100 transition-all">
                            Escala de Valoración
                        </summary>
                        <div class="mt-2 text-sm text-gray-700">
                            <ul class="list-disc pl-5">
                                <li><strong>1: Muy insatisfecho / Muy en desacuerdo</strong></li>
                                <li><strong>2: Insatisfecho / En desacuerdo</strong></li>
                                <li><strong>3: Neutral / Ni de acuerdo ni desacuerdo</strong></li>
                                <li><strong>4: Satisfecho / De acuerdo</strong></li>
                                <li><strong>5: Muy satisfecho / Muy de acuerdo</strong></li>
                            </ul>
                        </div>
                    </details>

                    <div id="question-container" class="overflow-x-auto p-4">
                        @foreach ($survey->questions->slice(16, 4) as $question)
                            <div class="mb-4 p-4 bg-white shadow-lg rounded-lg border border-gray-300">
                                <h4 class="text-2xl font-semibold text-green-700 mb-4">{{ $question->question }}</h4>

                                <table class="min-w-full table-auto border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-green-50">
                                            <th class="px-4 py-2 text-left font-medium text-green-700 w-1/2">Instructor
                                            </th>
                                            @foreach ($question->options as $option)
                                                <th class="px-2 py-2 text-center text-sm text-green-700 w-[10%]">
                                                    {{ $option }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instructors as $instructor)
                                            <tr class="border-b hover:bg-green-50">
                                                <td class="px-4 py-3 text-sm text-gray-800 w-1/2">
                                                    <strong>{{ $instructor->name }} {{ $instructor->last_name }}
                                                        {{ $instructor->second_last_name }}</strong>
                                                </td>
                                                @foreach ($question->options as $option)
                                                    <td class="px-2 py-2 text-center">
                                                        <input type="radio"
                                                            class="h-6 w-6 text-green-600 border-gray-300 focus:ring-green-500"
                                                            name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                            value="{{ $option }}"
                                                            id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                            required>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div :class="{ 'hidden': page !== 6 }">
                    <h1 class="text-xl font-bold text-gray-800 mb-4 text-center">Preguntas Abiertas</h1>
                    <div id="question-container">
                        @foreach ($survey->questions->slice(20, 2) as $question)
                            <div class="mb-4 p-4 bg-white shadow-lg rounded-lg border border-gray-300">
                                <h4 class="text-2xl font-semibold text-green-700 mb-4">{{ $question->question }}</h4>
                                @foreach ($instructors as $instructor)
                                    <div class="mb-6">
                                        <p class="text-sm font-semibold text-gray-800"><strong>Instructor:
                                                {{ $instructor->name }} {{ $instructor->last_name }}
                                                {{ $instructor->second_last_name }}</strong></p>
                                        <div class="w-full text-sm text-left border border-gray-300 rounded-lg p-4">
                                            @if ($question->type == 'radio')
                                                @foreach ($question->options as $option)
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <input type="radio"
                                                            class="h-5 w-5 text-green-600 border-gray-300 focus:ring-green-500"
                                                            name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                            value="{{ $option }}"
                                                            id="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}">
                                                        <label
                                                            for="question-{{ $question->id }}-instructor-{{ $instructor->id }}-{{ $option }}"
                                                            class="text-gray-700">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            @elseif($question->type == 'text')
                                                <input type="text" maxlength="30"
                                                    name="answers[{{ $instructor->id }}][{{ $question->id }}]"
                                                    id="question-{{ $question->id }}-instructor-{{ $instructor->id }}"
                                                    class="w-full h-12 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500  resize-none text-gray-700 placeholder-gray-400 text-start">
                                                </input>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>



                <div class="flex justify-between mt-6">
                    <button type="button" @click="page--" x-show="page > 1"
                        class="bg-blue-500 text-white py-2 px-4 rounded">
                        Anterior
                    </button>
                    <button type="button" @click="page++" x-show="page < 6"
                        class="bg-blue-500 text-white py-2 px-4 rounded ml-auto">
                        Siguiente
                    </button>
                </div>

                <div class="flex justify-end mt-6" x-show="page === 6">
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Enviar Encuesta</button>

                </div>
            </div>
        </div>
    </form>
</body>