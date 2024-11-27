<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</head>
<body>
    <div style="width:100%">
    <div id="chart">
    </div>
    </div>
    <script>
        

         var options = {
          series: [{
            name: 'seria',
          data: [44, 55, 41, 64, 22, 43, 21]
        }, {
          data: [53, 32, 33, 52, 13, 44, 32]
        }, {
          data: [10, 1, 33, 59, 23, 27, 56]
        }
    ],
          chart: {
          type: 'bar',
          height: 430,
          
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: 'top',
            },
          }
        },
        dataLabels: {
          enabled: true,
          offsetX: -10,
          style: {
            fontSize: '10px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
        tooltip: {
          shared: true,
          intersect: false
        },
        xaxis: {
            categories: [
            "Propone ejemplos o ejercicios que vinculan los resultados de aprendizaje con la práctica real",
            "Propone ejemplos o ejercicios que vinculan los resultados", "Propone ejemplos o ejercicios que vinculan los resultados", "Propone ejemplos o ejercicios que vinculan los resultados", "Propone ejemplos o ejercicios que vinculan los resultados", "Propone ejemplos o ejercicios que vinculan los resultados", "Propone ejemplos o ejercicios que vinculan los resultados"
            ],
            labels: {
                showDuplicates: true,
      rotate: -45,  // Ajusta el ángulo de las etiquetas
      style: {
        fontSize: '12px',  // Tamaño de la fuente
        fontFamily: 'Arial',  // Fuente
      },
      // Usar un salto de línea para dividir el texto largo
      
      // También puedes intentar esta propiedad para mejorar el ajuste del texto
      trim: true,
    
  },
        }
    };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
</body>
</html>