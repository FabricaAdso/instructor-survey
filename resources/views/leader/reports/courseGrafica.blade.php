<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General - Instructor</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 0 auto;
            padding: 10px;
        }

        .header {
    text-align: center;
    color: #28a745;
    margin-bottom: 20px;
    border-bottom: 2px solid #28a745; /* Estilo de borde */
    padding-bottom: 10px;
    border-top: 3px solid #28a745;  /* Si deseas un borde superior también */
}


        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        h2, h3 {
            color: #28a745;
            font-size: 20px;
            margin-bottom: 15px;
            text-align: center;
        }

        .instructor-info {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .chart-section {
    margin-bottom: 50px;
    border: 2px solid #f4f4f4;  /* Borde en color verde */
    border-radius: 8px;  /* Borde redondeado */
    padding: 15px;  /* Espacio interno */
}

        .chart-section h2 {
            margin-bottom: 20px;
        }

        .observations-section ul {
    list-style-type: disc;
    padding-left: 20px;
    border-left: 4px solid #28a745;  /* Línea de borde a la izquierda */
    padding-left: 25px; /* Aumentar el espacio después del borde */
}

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Primera Hoja -->
    <div class="container">
        <div class="header">
            <h2>REPORTE DE SATISFACCIÓN DEL APRENDIZ EN ETAPA LECTIVA – EJECUCIÓN DE LA FORMACIÓN: FICHA {{ $course->code }}</h2>
        </div>
        <br><br>

        <div class="instructor-info">
            <h3>Instructor: {{ $instructor->name }} {{$instructor->last_name}} {{$instructor->second_last_name}}</h3>
            <h3>programa:{{ $program->name }}</h3>
        </div>
        <br><br>

        <div class="charts-container">
            <div class="chart-section" id="chart1">
                <h2>1. Integralidad del Instructor</h2>
            </div>
            <br><br><br><br>
            <div class="chart-section" id="chart2">
                <h2>2. Planeación del Procedimiento de Ejecución de la Formación</h2>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Segunda Hoja -->
    <div class="container">
        <div class="charts-container">
            <div class="chart-section" id="chart3">
                <h2>3. Ejecución de la Formación Personal</h2>
            </div>
            <br><br><br><br>
            <div class="chart-section" id="chart4">
                <h2>4. Evaluación General</h2>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Observaciones -->
    <div class="container">
        <h2 style="text-align: center;">5. Observaciones</h2>
        <div class="observations-section">
            <ul>
                @foreach ($observations->filter(fn($answer) => $answer->question_id == 21) as $observation)
                    <li>{{ $observation->qualification }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Recomendaciones -->
    <div class="container">
        <h2 style="text-align: center;">6. Recomendaciones</h2>
        <div class="observations-section">
            <ul>
                @foreach ($observations->filter(fn($answer) => $answer->question_id == 22) as $recommendation)
                    <li>{{ $recommendation->qualification }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
            const reportData = @json($reportData->pluck('average')->values());
            const questions = JSON.parse(@json($questions)); // Convertimos JSON en un array


            const distribution = [6, 4, 6, 4]; // Cantidad de preguntas por gráfica
            let startIndex = 0;

            distribution.forEach((count, index) => {
                const chunkData = reportData.slice(startIndex, startIndex + count);
                const chunkCategories = questions
                    .slice(startIndex, startIndex + count)
                    .map((q) => splitText(q, 23)); // Dividir el texto en líneas aquí

                startIndex += count;

                const options = {
                    chart: {
                        type: 'bar',
                        height: '400',
                        width: '1250', // Hacer que la gráfica ocupe el 100% del contenedor
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
                                fontSize: '14px',
                                fontWeight: '',
                                whiteSpace: 'pre-line', // Importante para manejar saltos de línea
                            },
                            rotate: 0
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
