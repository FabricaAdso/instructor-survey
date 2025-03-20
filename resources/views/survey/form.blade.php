<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Encuesta con Arrays de Preguntas y Tooltips Mejorados</title>
    <style>
        body {
            padding: 15px;
            background-color: #f3f4f6;
            margin: 0;
            font-family: sans-serif;
        }

        form {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .warning-message {
            background-color: #fdecea;
            color: #b91c1c;
            padding: 1rem;
            border: 1px solid #fca5a5;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: none;
            font-weight: bold;
            text-align: center;
            font-size: 1.1rem;
        }

        .page {
            display: none;
        }

        .active {
            display: block;
        }

        .card {
            background-color: #fff;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 1rem;
            border-radius: 0.5rem;
            background: linear-gradient(to right, #34d399, #3b82f6);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #d1d5db;
        }

        th,
        td {
            padding: 0.5rem;
            text-align: center;
            font-size: 0.875rem;
        }

        th {
            background-color: #f0fdf4;
            color: #065f46;
        }

        td {
            color: #374151;
        }

        .invalid {
            position: relative;
        }

        .invalid::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 28px;
            height: 28px;
            border: 2px solid #EF4444;
            border-radius: 50%;
            pointer-events: none;
            z-index: 2;
        }


        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .button-container-end {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .btn-green {
            background-color: #10b981;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        input[type="radio"] {
            width: 24px;
            height: 24px;
            border: 1px solid #ef4444;
            margin: 0 auto;
            display: block;
        }

        input[type="text"] {
            width: 97%;
            height: 3rem;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            color: #374151;
        }

        input[type="text"]::placeholder {
            color: #9ca3af;
        }

        label {
            display: inline-block;
            width: 24px;
            height: 24px;
            position: relative;
            text-align: center;
            margin: 0 auto;
        }

        .tooltip {
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-in-out;
            z-index: 10;
        }

        .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        label:hover .tooltip,
        label:focus-within .tooltip {
            opacity: 1;
            visibility: visible;
        }

        p {
            margin: 0;
        }

        .btn-lift {
            background: linear-gradient(to right, #34d399, #3b82f6);
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            font-size: 1rem;
            font-weight: 600;
        }

        .btn-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }



        .warning-message {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 600px;
            z-index: 9999;

            background-color: #fdecea;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
            font-weight: bold;
            font-size: 1rem;
            display: none;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }

        .scale {
            margin: 1rem 0;
        }

        .evaluation-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .evaluation-number {
            font-size: 1.1rem;
            color: #2d3748;
            margin: 0 0 0.25rem;
        }

        .evaluation-description {
            font-size: 0.95rem;
            color: #4a5568;
            margin: 0;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }

        #nextBtn,
        #submitBtn {
            margin-left: auto;
        }
    </style>
</head>

<body>

    <div id="warning-message" class="warning-message" role="alert" aria-live="assertive">
        <strong>¡Atención!</strong> Aún te faltan campos por completar. Por favor, llena todos los campos antes
        de continuar.
    </div>
    <form
        action="{{ route('survey.submit', ['surveyId' => $survey->id, 'apprenticeId' => Auth::user()->apprentice->id]) }}"
        method="POST" aria-labelledby="form-title">
        @csrf
        <div class="container">

            <div id="survey-container">
                <div class="page active" data-page="1">
                    <div class="card">
                        <h1 id="form-title" class="title">ENCUESTA DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA –
                            EJECUCIÓN DE LA FORMACIÓN.</h1>
                        <br>
                        <p style="font-size: 1rem; color: #374151; line-height: 1.5; margin-bottom: 1rem;">
                            Evaluar la satisfacción de los aprendices con respecto a la ejecución de la formación en la
                            etapa lectiva, con el fin de identificar áreas de oportunidad para mejorar la calidad del
                            proceso educativo y optimizar las metodologías, recursos y estrategias empleadas.
                        </p>
                        <div class="card" style="background-color: #d1fae5; margin-bottom: 1rem; padding: 1rem;">
                            <h2 style="font-size: 1.25rem; font-weight: 600; color: #065f46; margin-bottom: 0.5rem;">
                                Agradecemos su participación</h2>
                            <p style="font-size: 1rem; color: #374151;">
                                EVALÚE de <strong>1 a 5</strong> a los instructores acompañantes del proceso formativo,
                                teniendo en cuenta la siguiente escala:
                            </p>
                        </div>
                        <div style="font-size: 0.875rem; color: #4b5563;">
                            <div class="scale">
                                <div class="evaluation-item">
                                    <p class="evaluation-number"><strong>1. Insatisfecho / Muy en desacuerdo:</strong>
                                    </p>
                                    <p class="evaluation-description">La experiencia o aspecto evaluado no cumple en
                                        absoluto con mis expectativas.</p>
                                </div>
                                <div class="evaluation-item">
                                    <p class="evaluation-number"><strong>2. Insatisfecho / En desacuerdo:</strong></p>
                                    <p class="evaluation-description">La experiencia o aspecto evaluado no cumple
                                        completamente con mis expectativas, pero presenta algunos aspectos positivos.
                                    </p>
                                </div>
                                <div class="evaluation-item">
                                    <p class="evaluation-number"><strong>3. Neutral / Ni de acuerdo ni en
                                            desacuerdo:</strong></p>
                                    <p class="evaluation-description">No tengo una opinión clara o no me siento ni
                                        satisfecho ni insatisfecho con este aspecto.</p>
                                </div>
                                <div class="evaluation-item">
                                    <p class="evaluation-number"><strong>4. Satisfecho / De acuerdo:</strong></p>
                                    <p class="evaluation-description">La experiencia o aspecto evaluado cumple con mis
                                        expectativas y tiene algunos puntos destacados.</p>
                                </div>
                                <div class="evaluation-item">
                                    <p class="evaluation-number"><strong>5. Muy satisfecho / Muy de acuerdo:</strong>
                                    </p>
                                    <p class="evaluation-description">La experiencia o aspecto evaluado supera
                                        ampliamente mis expectativas como aprendiz y es altamente satisfactorio.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="page" data-page="2">
                    <h2 class="title" style="margin-bottom: 1rem;">1. INTEGRALIDAD DEL INSTRUCTOR</h2>
                    <div id="page2-questions"></div>
                </div>
                <div class="page" data-page="3">
                    <h2 class="title" style="margin-bottom: 1rem;">2. PLANEACIÓN DEL PROCEDIMIENTO DE EJECUCIÓN</h2>
                    <div id="page3-questions"></div>
                </div>
                <div class="page" data-page="4">
                    <h2 class="title" style="margin-bottom: 1rem;">3. EJECUCIÓN DE LA FORMACIÓN PROFESIONAL</h2>
                    <div id="page4-questions"></div>
                </div>
                <div class="page" data-page="5">
                    <h2 class="title" style="margin-bottom: 1rem;">4. EVALUACIÓN</h2>
                    <div id="page5-questions"></div>
                </div>
                <div class="page" data-page="6">
                    <h2 class="title" style="margin-bottom: 1rem;">Preguntas Abiertas</h2>
                    @foreach ($openQuestions as $question)
                        <div class="card">
                            <p style="font-size: 1.5rem; font-weight: 600; color: #065f46; margin-bottom: 0.5rem;">
                                {{ $question->question }}
                            </p>

                            <p style="font-size: 0.875rem; color: #374151; margin-bottom: 1rem;">
                                Estas preguntas son <strong>opcionales</strong>. No es obligatorio responderlas.
                            </p>
                            @foreach ($instructors as $instructor)
                                <div class="mb-4" style="margin-bottom: 1rem;">
                                    <p class="text-sm font-semibold text-gray-800">
                                        <strong>Instructor: {{ $instructor->user->name }}
                                            {{ $instructor->user->last_name }}</strong>
                                    </p>
                                    <input class="open-cuestion-input" type="text"
                                        name="answers[{{ $instructor->id }}][{{ $question->id }}]" maxlength="100"
                                        placeholder="Tu respuesta">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="button-container" id="navigationButtons">
                <button type="button" id="prevBtn" class="btn-lift" style="display: none;">Anterior</button>
                <button type="button" id="nextBtn" class="btn-lift">Siguiente</button>
                <button type="submit" id="submitBtn" class="btn btn-lift"
                    style="display: none; font-size:1.3rem">Enviar Encuesta</button>
            </div>

        </div>
    </form>

    <script>
        const scaleDescriptions = [
            "Muy insatisfecho / Muy en desacuerdo",
            "Insatisfecho / En desacuerdo",
            "Neutral / Ni de acuerdo ni desacuerdo",
            "Satisfecho / De acuerdo",
            "Muy satisfecho / Muy de acuerdo"
        ];

        const shortScaleDescrition = [
            "Muy instisfecho",
            "Insatisfecho",
            "Neutral",
            "Satisfecho",
            "Muy satisfecho"
        ]

        const questionsSection2 = [
            "Presentación personal",
            "Relaciones interpersonales",
            "Conocimiento general del área",
            "Actitud de servicio",
            "Lenguaje claro y sencillo",
            "Puntualidad"
        ];
        const questionsSection3 = [
            "Establece el plan de trabajo concertado",
            "Socializa el programa de formación",
            "Socializa el proyecto formativo",
            "Socializa las guías de aprendizaje"
        ];
        const questionsSection4 = [
            "Propone ejemplos o ejercicios que vinculan los resultados de aprendizaje con la práctica real",
            "Orienta de manera clara los conocimientos y procesos asociados con la competencia",
            "Propicia el desarrollo de un ambiente de respeto y confianza",
            "Estimula la reflexión sobre la manera que aprende",
            "Presenta y expone las sesiones de formación de manera organizada y estructurada",
            "Utiliza diversas estrategias, métodos y materiales"
        ];
        const questionsSection5 = [
            "Identifica los conocimientos y habilidades de los aprendices al inicio de cada competencia",
            "Aplica técnicas e instrumentos de evaluación de acuerdo con la evidencia requerida",
            "Da a conocer los resultados de la evaluación en el plazo establecido",
            "Retroalimenta con el aprendiz las valoraciones realizadas"
        ];

        const totalPages = 6;
        let currentPage = 1;

        function showPage(page) {
            document.querySelectorAll('.page').forEach(pageDiv => {
                pageDiv.classList.remove('active');
            });

            const currentPageDiv = document.querySelector(`.page[data-page="${page}"]`);
            if (currentPageDiv) {
                currentPageDiv.classList.add('active');

                const heading = currentPageDiv.querySelector('h2.title');
                if (heading) {
                    heading.setAttribute('tabindex', '-1');
                    heading.focus();
                } else {
                    const firstInput = currentPageDiv.querySelector('input, select, textarea, button');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            }

            document.getElementById('prevBtn').style.display = (page > 1) ? 'inline-block' : 'none';

            if (page < totalPages) {
                document.getElementById('nextBtn').style.display = 'inline-block';
                document.getElementById('submitBtn').style.display = 'none';
            } else {
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('submitBtn').style.display = 'inline-block';
            }

        }


        var instructors = @json($instructors);

        function generateQuestionsFromArray(containerId, radioNamePrefix, questionsArray, startNumber) {
            const container = document.getElementById(containerId);
            questionsArray.forEach((questionText, index) => {
                const questionNumber = startNumber + index;
                const card = document.createElement('div');
                card.className = 'card';

                const p = document.createElement('p');
                p.style.fontSize = '1.5rem';
                p.style.fontWeight = '600';
                p.style.color = '#065f46';
                p.style.marginBottom = '0.5rem';
                p.textContent = questionNumber + '. ' + questionText;
                card.appendChild(p);

                const table = document.createElement('table');
                const thead = document.createElement('thead');
                const trHead = document.createElement('tr');
                const thEmpty = document.createElement('th');
                thEmpty.style.textAlign = 'left';
                thEmpty.style.width = '50%';
                thEmpty.textContent = 'Instructor';
                trHead.appendChild(thEmpty);
                for (let j = 1; j <= 5; j++) {
                    const th = document.createElement('th');
                    th.textContent = j;
                    th.style.width = '10%';
                    trHead.appendChild(th);
                }
                thead.appendChild(trHead);
                table.appendChild(thead);

                const tbody = document.createElement('tbody');
                instructors.forEach(instructor => {
                    const trBody = document.createElement('tr');
                    const tdName = document.createElement('td');
                    tdName.style.textAlign = 'left';
                    tdName.textContent = instructor.user.name + " " + instructor.user
                        .last_name;
                    trBody.appendChild(tdName);
                    for (let j = 1; j <= 5; j++) {
                        const td = document.createElement('td');
                        const label = document.createElement('label');
                        label.style.display = 'block';
                        label.style.textAlign = 'center';
                        label.style.position = 'relative';
                        const input = document.createElement('input');
                        input.type = 'radio';
                        input.name = "answers[" + instructor.id + "][" + (startNumber + index) + "]";
                        input.value = j;
                        input.required = true;
                        input.setAttribute('aria-invalid', 'false');
                        input.setAttribute('title', scaleDescriptions[j - 1]);
                        label.appendChild(input);
                        const tooltip = document.createElement('span');
                        tooltip.className = 'tooltip';
                        tooltip.textContent = j + ': ' + shortScaleDescrition[j - 1];
                        label.appendChild(tooltip);
                        td.appendChild(label);
                        trBody.appendChild(td);
                    }
                    tbody.appendChild(trBody);
                });
                table.appendChild(tbody);
                card.appendChild(table);
                container.appendChild(card);
            });
        }

        generateQuestionsFromArray('page2-questions', 'p2_q_', questionsSection2, 1);
        generateQuestionsFromArray('page3-questions', 'p3_q_', questionsSection3, 7);
        generateQuestionsFromArray('page4-questions', 'p4_q_', questionsSection4, 11);
        generateQuestionsFromArray('page5-questions', 'p5_q_', questionsSection5, 17);

        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validatePage(currentPage)) {
                if (currentPage < totalPages) {
                    currentPage++;
                    showPage(currentPage);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }
        });

        function validatePage(page) {
            let valid = true;
            const warning = document.getElementById('warning-message');
            const pageDiv = document.querySelector('.page.active');
            const tables = pageDiv.querySelectorAll('table');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const radios = row.querySelectorAll('input[type="radio"]');
                    if (radios.length > 0) {
                        let checked = false;
                        radios.forEach(radio => {
                            if (radio.checked) {
                                checked = true;
                                radio.setAttribute('aria-invalid', 'false');
                            }
                        });
                        if (!checked) {
                            valid = false;
                            radios.forEach(radio => {
                                radio.parentElement.classList.add('invalid');
                                radio.setAttribute('aria-invalid', 'true');
                            });
                        } else {
                            // Si uno está checked, quitamos la marca de error
                            radios.forEach(radio => {
                                radio.parentElement.classList.remove('invalid');
                                radio.setAttribute('aria-invalid', 'false');
                            });
                        }
                    }
                });
            });
            if (!valid) {
                warning.style.display = 'block';
                // No avanza
                return false;
            } else {
                warning.style.display = 'none';
                return true;
            }
        }

        document.getElementById('survey-container').addEventListener('change', function(event) {
            if (event.target.type === 'radio') {
                const radios = document.querySelectorAll('input[name="' + event.target.name + '"]');
                radios.forEach(radio => {
                    radio.parentElement.classList.remove('invalid');
                    radio.setAttribute('aria-invalid', 'false');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            showPage(currentPage);
        });

        document.addEventListener("DOMContentLoaded", function() {
            if (!instructors || instructors.length === 0) {
                const submitBtn = document.getElementById("submitBtn");
                submitBtn.disabled = true;
                submitBtn.style.backgroundColor = '#ccc';
                submitBtn.title = "No hay instructores asignados";

                const warningMessage = document.getElementById("warning-message");
                warningMessage.style.display = 'block';
                warningMessage.textContent =
                    "No hay instructores asignados. Por favor, agregue al menos un instructor antes de enviar la encuesta.";
            }
        });
    </script>
</body>

</html>
