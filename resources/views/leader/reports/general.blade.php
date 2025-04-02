<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte General - Instructor</title>
    <link rel="stylesheet" href="{{ asset('/css/show.css') }}">
    <!--  <script src="{{ mix('js/app.js') }}"></script>-->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="container">
        <h2>ENCUESTA GENERAL DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA – EJECUCIÓN DE LA FORMACIÓN.</h2>
        <h3>Instructor: {{ $instructor->user->name }} {{ $instructor->user->last_name }}</h3>

        <div class="container-grafic">
            <div class="chart-section" id="chart1">
                <h2>1. Integralidad del Instructor</h2>
            </div>
            <div class="chart-section" id="chart2">
                <h2>2. Planeación del Procedimiento de Ejecución de la Formación</h2>
            </div>
            <div class="chart-section" id="chart3">
                <h2>3. Ejecución de la Formación Personal</h2>
            </div>
            <div class="chart-section" id="chart4">
                <h2>4. Evaluación General</h2>
            </div>

           

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@4.5.0/dist/apexcharts.min.js"
        onerror="this.onerror=null; this.src='/js/apexcharts.min.js';"></script>
    <script>
        // Función para dividir el texto en varias líneas
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
            const reportData = @json($reportData->pluck('average')->values());
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
                            style: {
                                fontSize: '16px'
                            }
                        },
                        min: 0,
                        max: 5,
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(2); // Redondear a dos decimales
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

</html>
