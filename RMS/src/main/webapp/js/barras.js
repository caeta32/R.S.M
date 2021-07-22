const GRAFICOBARRA = document.getElementById("miGraficoBarra");



let graficaBarra = new Chart(GRAFICOBARRA, {
    type: 'bar',
    data: {
        labels: ['K2', 'Makalu', 'Manaslu', 'Nanga Parbat', 'Lhotse', 'Cho Oyu', 'Dhaulagiri I', 'Everest', 'Kangchenjunga', 'Annapurna I'],
        datasets: [{
          label: "Altura (m)",
          data: [8611, 8485, 8163, 8126, 8516, 8188, 8167, 8849, 8586, 8091],
          backgroundColor: [
            'rgba(0,174,239, 0.2)'
          ],
          borderColor: [
            'rgb(0,174,239)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
            legend: {
              position: 'left',
            },
            title: {
              display: true,
              text: 'Las 10 monta\u00f1as mas altas del mundo',
              position: 'top'
          }
        },
            scales: {
                y: {
		beginAtZero:false
                }
            }
    } 
});

document.getElementById('download-pdf').addEventListener("click", downloadPDF);
var canvas = document.querySelector('#miGraficoBarra');

function downloadPDF() {
    var canvas = document.querySelector('#miGraficoBarra');
      //creates image
      var canvasImg = canvas.toDataURL("image/png", 1.0);
      //creates PDF from img
      var doc = new jsPDF('p', 'mm', [220, 210]);

      doc.addImage(canvasImg, 'PNG', 10, 10, 190, 190);
      doc.save('canvas.pdf');
  }