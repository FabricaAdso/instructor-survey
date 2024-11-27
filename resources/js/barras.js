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
    categories: ["Propone ejemplos o ejercicios que vinculan los resultados de aprendizaje con la práctica real", 2002, 2003, 2004, 2005, 2006, 2007],
    labels: {
      formatter: (val) => {
        return val / 1000 + 'K'
      }
    }
  },
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();