var _ = require('underscore');
var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
var theUrl = "http://localhost/server-central_JEE/ws/?accion=verDatosDeEstacion&id=3"

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}

respuesta = JSON.parse(httpGet(theUrl))

var direcciones = _.pluck(respuesta.result, 'direccionViento');
var norte = 0;
var noreste = 0;
var este = 0;
var sureste = 0;
var sur = 0;
var suroeste = 0;
var oeste = 0;
var noroeste = 0;
for (var direccion in direcciones) {
  if(direcciones[direccion] == "N"){
    norte++;
  }
  if(direcciones[direccion] == "NE"){
    noreste++;
  }
  if(direcciones[direccion] == "E"){
    este++;
  }
  if(direcciones[direccion] == "SE"){
    sureste++;
  }
  if(direcciones[direccion] == "S"){
    sur++;
  }
  if(direcciones[direccion] == "SO"){
    suroeste++;
  }
  if(direcciones[direccion] == "NO"){
    noroeste++;
  }
  if(direcciones[direccion] == "O"){
    oeste++;
  }
}
