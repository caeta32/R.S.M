const GRAFICOPUNTOS = document.getElementById("miGraficoPuntos");

let graficaPuntos = new Chart(GRAFICOPUNTOS, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Venta de Helados vs Temperatura',
            data: [{
                x: 11.9,
                y: 185
            }, {
                x: 14.2,
                y: 215
            }, {
                x: 16.4,
                y: 325
            }, {
                x: 15.2,
                y: 332
            }, {
                x: 18.5,
                y: 406
            }, {
                x: 22.1,
                y: 552
            }, {
                x: 19.4,
                y: 412
            }, {
                x: 25.1,
                y: 614
            }, {
                x: 23.4,
                y: 544
            }, {
                x: 22.6,
                y: 445
            }, {
                x: 17.2,
                y: 408
            }, {
                x: 18.1,
                y: 421
            },],
            backgroundColor: 'rgb(0,174,239)'
        }],
    },
    options: {
        scales: {
            x: {
                type: 'linear',
                position: 'left',
                display: true,
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function (value, index, values) {
                        return value + '\xB0C';
                    }
                }
            },
            y: {
                display: true,
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function (value, index, values) {
                        return '$' + value;
                    }
                }
            },
        }
    }
});

document.getElementById('download-pdf').addEventListener("click", downloadPDF);
var canvas = document.querySelector('#miGraficoPuntos');

function downloadPDF() {
    var canvas = document.querySelector('#miGraficoPuntos');
      //creates image
      var canvasImg = canvas.toDataURL("image/png", 1.0);
      //creates PDF from img
      var doc = new jsPDF('p', 'mm', [220, 210]);

      doc.addImage(canvasImg, 'PNG', 10, 10, 190, 190);
      doc.save('canvas.pdf');
  }