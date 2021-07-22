/*CREAR PETICIÓN AJAX --------------------------------------------------*/
function makeRequest() {
	let request = false;
	
	/*Se crea el objeto XMLHttpRequest haciendo controles de compatibilidad.*/
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
        request = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
				console.error(e);
				return false;
			}
        }
    }
	/*En caso de no lograr crear el objeto XMLHttpRequest*/
	if (!request) {
        alert('ERROR : No es posible crear una instancia XMLHTTP');
		return false;
    }

	return request;
}
/*FIN CREAR PETICIÓN AJAX --------------------------------------------------*/

/*Mostrar marcador seleccionado y otro marcador con la estación más cercana ------------------*/
function selectedMarkerAndNearestStation() {
	if(this.readyState == 4) {
		if (this.status == 200) {
			let responseJson = JSON.parse(this.responseText);
			
			/*Agregando marcador en las coordenadas obtenidas.*/
			let marker = L.marker([responseJson.result.latitud, responseJson.result.longitud], 
					 {
						icon: markerStation, 
						id: responseJson.result.id, 
						name: responseJson.result.nombre, 
						localidad: responseJson.result.localidad,
						represents: "station"
					});
			marker.on('click', onClickMarkerStation);
			marker.addTo(layerGroup);
		} else {
			alert('El código de estado fue: ' + this.status);
		}
	}
}
/*FIN Mostrar marcador seleccionado y otro marcador con la estación más cercana ------------------*/

/*Lo que ocurre si se presiona click en el marcador de una estación. ----------------------*/
function onClickMarkerStation(e) {
	/*Obtengo las opciones agregadas al marcador.*/
	let infoStation = this.options;
	
	/*Si ambos reprsentan una estación y son el mismo...*/
	if(e.target.options.represents === "station" && 
	   eventBackup !== null && 
       eventBackup.target.options.represents === "station" &&
       eventBackup.target.options.id === e.target.options.id) {
		/*Se deselecciona.*/
		eventBackup = null;
		e.target.setIcon(markerStation);
		paintCollapseByDefault();
	} else {
		/*Se guarda el id para la llamada AJAX por los datos metereológicos.*/
		let idStation = infoStation.id;
		/*LLAMADA AJAX ------------------------------------------------*/
	
		/*Se crea el objeto XMLHttpRequest haciendo controles de compatibilidad.*/
	    request = makeRequest();
		/*En caso de no lograr crear el objeto XMLHttpRequest*/
		if (!request) {
	        alert('ERROR : No es posible crear una instancia XMLHTTP');
			return false;
	    } else {
			/*Si se logró crear el objeto XMLHttpRequest*/
			
			/*Se asignan las acciones ante el cambio de estado*/
			request.onreadystatechange = function() {
				if(this.readyState == 4) {
					/*Si se recibieron los datos.*/
					if (this.status == 200) {
						/*Se recibe la respuesta y se parsea a JSON.*/
						let responseJson = JSON.parse(this.responseText);
						/*Se obtiene el array de datos metereológicos.*/
						let lastPosArrayData = responseJson.result.length - 1;
						let lastData = responseJson.result[lastPosArrayData];
						
						/*Si ningún marcador está activo...*/
						if (eventBackup === null) {
							/*Se cambia el marcador de estación al "activo".*/
							e.target.setIcon(markerStationActive);
							/*Se almacena como marcador activo.*/
							eventBackup = e;						
							/*Se pinta el collapse con los datos metereológicos.*/
							paintCollapse(infoStation, lastData);
						} else {
							/*Si ya hay un marcador activo...*/
							/*Si representa una ubicación seleccionada por el usuario...*/
							if (eventBackup.target.options.represents === "click") {
								/*Se cambia por el marcador de selección del usuario "inactivo".*/
								eventBackup.target.setIcon(markerSelected);
								/*Se cambia el marcador de estación al "activo".*/
								e.target.setIcon(markerStationActive);
								/*Se almacena como marcador activo.*/
								eventBackup = e;
								/*Se pinta el collapse con los datos metereológicos.*/
								paintCollapse(infoStation, lastData);
							} else if (eventBackup.target.options.represents === "station") {
								/*Si representa una estación...*/
								/*Se cambia el activo por el marcador de estación "inactivo".*/
								eventBackup.target.setIcon(markerStation);
								/*Se cambia el marcador de estación al "activo".*/
								e.target.setIcon(markerStationActive);
								/*Se almacena como marcador activo.*/
								eventBackup = e;
								/*Se pinta el collapse con los datos metereológicos.*/
								paintCollapse(infoStation, lastData);
							}
						}
					} else {
						alert('El código de estado fue: ' + this.status);
					}
				}
			}
			/*Se define URL*/
			let fileUrl = '../Controllers/AjaxController.jsp';
			let params = '?accion=verDatosDeEstacion&id='+idStation;
			request.open('GET', fileUrl + params, true);
			request.send();
		}
		/*FIN LLAMADA AJAX ------------------------------------------------*/	
	}
}
/*FIN Lo que ocurre si se presiona click en el marcador de una estación. ----------------------*/

/*Lo que ocurre si se presiona click en el marcador de ubicación seleccionada por el usuario.*/
function onClickMarkerUser(e) {
	/*Si ningún marcador está activo...*/
	if (eventBackup === null) {
		console.log('Detecté que ninguno está seleccionado...');
		/*Se cambia el marcador al "activo".*/
		e.target.setIcon(markerSelectedActive);
		/*Se almacena como marcador activo.*/
		eventBackup = e;
	} else {
		console.log('Detecté que hay alguno seleccionado...');
		/*Si ya hay un marcador activo...*/
		/*Si representa una ubicación seleccionada por el usuario...*/
		if (eventBackup.target.options.represents === "click") {
			console.log('Detecté que está seleccionado el del user...');
			/*Se deselecciona*/
			/*Se cambia el marcador al "inactivo".*/
			e.target.setIcon(markerSelected);
			/*Se almacena que no hay marcador activo.*/
			eventBackup = null;	
		} else if (eventBackup.target.options.represents === "station") {
			console.log('Detecté que está seleccionada una estación...');
			/*Si representa una estación...*/
			/*Se cambia el activo por el marcador de estación "inactivo".*/
			eventBackup.target.setIcon(markerStation);
			/*Se cambia el marcador de estación al "activo".*/
			e.target.setIcon(markerSelectedActive);
			/*Se almacena como marcador activo.*/
			eventBackup = e;
			paintCollapseByDefault();
		}
	}
}
/*FIN - Lo que ocurre si se presiona click en el marcador de ubicación seleccionada por el usuario.*/

/*Pintar Collapse si se selecciona estación ---------------------------------------------------*/
function paintCollapse(infoStation, lastData) {
	/*Se obtiene el elemento en el que se va a pintar la información.*/
	let infoElement = document.getElementById('infoCollapseSelectedStation');
	let info = '';
	/*Se pinta nombre y localidad de la estación.*/
	info = '<div class="list-group-item border-0 text-center text-break" style="font-size: 1.3rem;">'
		 + 		'<strong><span id="nameSelectedStation">' + infoStation.name + '</span></strong>'
		 + '</div>'
		 + '<div class="list-group-item border-0 text-center text-break" style="font-size: 1.2rem;">'
		 +		'<strong>localidad:</strong> <span id="localidadSelectedStation">' + infoStation.localidad + '</span>'
		 + '</div>'
		 + '<div class="list-group-item border-0 text-break">'
		 + 		'<div class="row justify-content-center text-center" style="font-size: 1.2rem;">'
		 +			'<div class="col-lg-9">'
		 +				'<hr />'
		 +			'</div>'
		 +		'</div>'
		 + '</div>';
	/*Se pinta cada dato verficando que no sea null, para en caso de serlo no pintarlo.*/
	if(lastData.temperatura !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Temperatura Ambiente: </strong> <span>' + lastData.temperatura + ' [°C]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.humedad !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Humedad Ambiente: </strong> <span>' + lastData.humedad + ' [%]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.presion !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Presión Atomsférica: </strong> <span>' + lastData.presion + ' [HPa]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.velocidadViento !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Velocidad del Viento: </strong> <span>' + lastData.velocidadViento + ' [km/h]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.direccionViento !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Dirección del viento: </strong> <span>' + lastData.direccionViento + '</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.radiacionSolar !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Radiación Solar: </strong> <span>' + lastData.radiacionSolar + ' [W/m^2]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.radiacionIndiceUV !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Índice de Radiación UV: </strong> <span>' + lastData.radiacionIndiceUV + '</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	if(lastData.indicePluviometrico !== null) {
		info += '<div class="list-group-item border-0">'
			 +		'<div id="dataSelectedStation" class="list-group text-start">'
			 +			'<div class="list-group-item border-0 text-break" style="font-size: 1.1rem;">'
			 +				'<strong>Índice Pluviométrico: </strong> <span>' + lastData.indicePluviometrico + ' [mm]</span>'
			 +			'</div>'
			 +		'</div>'
			 +	'</div>';
	}
	/*Se asigna la información al elemento HTML.*/
	infoElement.innerHTML = info;
	/*Se obtiene o crea el elemento collapse y se abre automáticamente para poder visualizar la información.*/
	let collapseElement = document.getElementById('collapseSelectedStation');
	let collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapseElement);
	collapseInstance.show();

}
/*FIN Pintar Collapse si se selecciona estación ---------------------------------------------------*/

/*Pintar Collapse cuando no se ha seleccionado ninguna estación ------------------------------------*/
function paintCollapseByDefault() {
	/*Se obtiene o crea el elemento collapse y se cierra automáticamente.*/
	let collapseElement = document.getElementById('collapseSelectedStation');
	let collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapseElement);
	if(hiddenCollapseStation === true) {
		/*Se pinta un mensaje para indicar que no hay información para mostrar.*/
		document.getElementById('infoCollapseSelectedStation').innerHTML = 
			    '<div class="list-group-item border-0 text-center text-break" style="font-size: 1.1rem;">'
			  + 	'<strong class="text-muted">Aún no ha seleccionado ninguna estación.</strong>'
			  + '</div>';
	} else {
		hideFromDefault = true;
		collapseInstance.hide();	
	}
}
/*FIN Pintar Collapse cuando no se ha seleccionado ninguna estación ------------------------------------*/