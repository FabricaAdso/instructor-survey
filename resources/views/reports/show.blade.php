<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte-instructor</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Calificación Promedio',
                    data: @json($reportData->values())
                }],
                xaxis: {
                    categories: @json($questions) 
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
</head>
<body>
    <div class="container">

    
    </div>


</body>
</html>
