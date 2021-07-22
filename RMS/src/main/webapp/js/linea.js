
var _ = require('underscore');

const GRAFICOLINEA = document.getElementById("miGrafico");
var theUrl = "http://localhost/server-central_JEE/ws/?accion=verDatosDeEstacion&id=3"

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}

respuesta = JSON.parse(httpGet(theUrl))

var indicesPluviometricos = _.pluck(respuesta.result, 'indicePluviometrico'); 

let graficaLinea = new Chart(GRAFICOLINEA, {
    type: 'line',
    data: {
        labels: ['ARTIGAS', 'CANELONES', 'MONTEVIDEO', 'CERRO LARGO', 'COLONIA', 'DURAZNO', 'FLORES', 'FLORIDA', 'LAVALLEJA', 'MALDONADO'
            , 'PAYSANDU', 'RIO NEGRO', 'RIVERA', 'ROCHA', 'SALTO', 'SAN JOSE', 'SORIANO', 'TACUAREMBO', 'TREINTA Y TRES'],    
        datasets: [{
            label: 'Numero de Votos',
            data: [54185, 350202, 877327, 65487, 92750, 44819, 20033, 51581, 45412, 124045, 83047, 40244, 75020, 55133, 91729, 74986, 64566, 69530, 37879],
            backgroundColor: [
                'rgba(0,174,239, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgb(0,174,239)'
            ],
            pointBorderColor: [
                'rgb(255,255,255, 0)'
            ],
            pointBackgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        animation:{
            onComplete: function(){
                dataURL3 = graficaLinea.toDataURL('image/png');
        }
    },
        plugins: {
            legend: {
              position: 'left',
            },
            title: {
                display: true,
                text: 'Numero de votantes por departamento en Uruguay',
                position: 'top'
            },
       responsive: false
    }
}
});

document.getElementById('download-pdf').addEventListener("click", downloadPDF);
var canvas = document.querySelector('#miGrafico');

function downloadPDF() {
    var canvas = document.querySelector('#miGrafico');
      //creates image
      var canvasImg = canvas.toDataURL("image/png", 1.0);
      //creates PDF from img
      var doc = new jsPDF('p', 'mm', [220, 210]);

      doc.addImage(canvasImg, 'PNG', 10, 10, 190, 190);
      doc.save('canvas.pdf');
  }