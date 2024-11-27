import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;  // Esto hace ApexCharts accesible globalmente

// Crear el gráfico
document.addEventListener('DOMContentLoaded', function () {
    var options = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Calificación',
            data: [30, 40, 35, 50]
        }],
        xaxis: {
            categories: ['Enero', 'Febrero', 'Marzo', 'Abril']
        },
        title: {
            text: 'Resultados de la Encuesta'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});
