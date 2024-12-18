<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte-instructor</title>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>

<body>
    <div class="container">
        <h2>REPORTE DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA – EJECUCIÓN DE LA FORMACIÓN: FICHA {{ $course->code }}</h2>
        <h3>Instructor: {{ $instructor->name }} {{$instructor->last_name}}</h3>
        <h3>programa:{{ $program->name }}</h3>

        {{-- <a target="download" href="{{route('reportsDownloadCourse', ['courseId' => $course->id, 'instructorId' => $instructor->id, 'programId' => $program->id]) }}" class="btn btn-primary">
            Descargar Reporte Ficha
        </a> --}}

        <div class="container-grafic">
            <div class="chart-section" id="chart1">
                <h2>1.Integralidad del instructor</h2>
            </div>
            <div class="chart-section" id="chart2">
                <h2>2.Planeacion del procedimiento de ejecucion de la formación</h2>
            </div>
            <div class="chart-section" id="chart3">
                <h2>3.Ejecucion de la formacion personal</h2>
            </div>
            <div class="chart-section" id="chart4">
                <h2>4.Evaluacion</h2>
            </div>
            <div class="chart-section"
                style="padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

                <div style="display: flex; justify-content: space-between;  gap: 20px; flex-wrap: wrap;">
                    <!-- Observaciones -->
                    <div
                        style="width: 48%; max-width: 100%; background-color: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden; box-sizing: border-box;">
                        <h3 style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 8px; word-wrap: break-word;">Observaciones
                        </h3>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($observations->filter(fn($answer) => $answer->question_id == 21) as $observation)
                                <li
                                    style="background-color: #e9fbe8; padding: 10px; margin-bottom: 8px; border-radius: 5px; word-wrap: break-word;">
                                    {{ $observation->qualification }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recomendaciones -->
                    <div
                        style="width: 48%; max-width: 100%; background-color: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden; box-sizing: border-box;">
                        <h3 style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 8px; word-wrap: break-word;">
                            RECOMENDACIÓN O SUGERENCIAS</h3>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($observations->filter(fn($answer) => $answer->question_id == 22) as $recommendation)
                                <li
                                    style="background-color: #e9fbe8; padding: 10px; margin-bottom: 8px; border-radius: 5px; word-wrap: break-word;">
                                    {{ $recommendation->qualification }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>

function splitText(text, maxLength) {
            const words = text.split(' '); // Dividimos el texto en palabras
            let lines = [];
            let currentLine = '';

            words.forEach((word) => {
                // Si agregar la palabra excede el límite de longitud, comenzamos una nueva línea
                if ((currentLine + word).length <= maxLength) {
                    currentLine += (currentLine ? ' ' : '') + word; // Añadimos la palabra al final de la línea
                } else {
                    lines.push(currentLine); // Añadimos la línea completa al array
                    currentLine = word; // Empezamos una nueva línea con la palabra actual
                }
            });

            // Agregamos la última línea
            if (currentLine) {
                lines.push(currentLine);
            }

            return lines;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const reportData = @json($reportData -> pluck('average') -> values());
            const questions = JSON.parse(@json($questions)); // Convertimos JSON en un array

            // Comprobación para asegurarnos que questions es un array
            if (!Array.isArray(questions)) {
                console.error("La variable 'questions' no es un array:", questions);
            }

            const distribution = [6, 4, 6, 4]; // Cantidad de preguntas por gráfica
            let startIndex = 0;

            distribution.forEach((count, index) => {
                const chunkData = reportData.slice(startIndex, startIndex + count);
                const chunkCategories = questions
                    .slice(startIndex, startIndex + count) // Usamos slice aquí
                    .map((q) => splitText(q, 23)); // Dividir el texto en líneas aquí

                startIndex += count;

                const options = {
                    chart: {
                        type: 'bar',
                        height: 400,
                        toolbar: {
                            show: false,
                        },
                    },
                    series: [{
                        name: 'Promedio de Calificación',
                        data: chunkData,
                    }, ],
                    xaxis: {
                        categories: chunkCategories,
                        labels: {
                            style: {
                                fontSize: '11px',
                                fontWeight: '',
                            },
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Calificación Promedio',
                            style:{
                                fontSize: '16px'
                            }
                        },
                        min: 0,
                        max: 5,
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(2);
                            },
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2);
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toFixed(2);
                        },
                        style: {
                            colors: ['#333'],
                        },
                    },
                };

                const chart = new ApexCharts(
                    document.querySelector(`#chart${index + 1}`),
                    options
                );
                chart.render();
            });
        });
    </script>
</body>
