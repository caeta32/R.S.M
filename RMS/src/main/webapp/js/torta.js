const GRAFICOTORTA = document.getElementById("miGraficoTorta");

let graficaTorta = new Chart(GRAFICOTORTA, {
    type: 'pie',
     options: {
        title: {
            display: true,
            text: 'TEST'
        }
    },
    data: {
        labels: ['Aventura', 'Accion', 'Drama', 'Comedia', 'Suspenso', 'Horror', 'Romanticos', 'Musicales'],
        datasets: [{
          label: 'Ingresos de diferentes generos del cine (Billones de U$S)',
          data: [63.57, 47.72, 36.89, 34.34, 20.33, 11.84, 9.99, 4.11],
          backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(128, 255, 0)',
            'rgb(255, 128, 0)',
            'rgb(255, 0, 191)',
            'rgb(128, 0, 255)',
            'rgb(255, 255, 0)'  
          ],
          hoverOffset: 4,
        }
    ]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Ganancia de diferentes generos de cine en USA (Billones de U$S)',
                position: 'top'
            }
        },

    }
});

document.getElementById('download-pdf').addEventListener("click", downloadPDF);
var canvas = document.querySelector('#miGraficoTorta');

function downloadPDF() {
    var canvas = document.querySelector('#miGraficoTorta');
      //creates image
      var canvasImg = canvas.toDataURL("image/png", 1.0);
      //creates PDF from img
      var doc = new jsPDF('p', 'mm', [220, 210]);

      doc.addImage(canvasImg, 'PNG', 10, 10, 190, 190);
      doc.save('canvas.pdf');
  }