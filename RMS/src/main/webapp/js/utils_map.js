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
			/*Creando contenido de popup*/
			const content = 	
			'<div class="containerTitlePopup">' +
				'<strong class="titlePopup">Estación más cercana</strong>' +
			'</div>' +
			'<h5 class="mt-3 text-break px-2">' + responseJson.result.nombre + '</h5>' +
			'<p class="distanciaPopup text-break"><strong>Distancia desde la ubicación seleccionada:</strong></p>' +
			'<p class="distanciaNumPopup">' + responseJson.result.distancia + ' ' + responseJson.result.unidadDistancia + '</p>' +
			'<p class="text-muted coordenadasPopup">lat: ' + responseJson.result.latitud + ', lng: ' + responseJson.result.longitud +'</p>';
			/*Agregando popup*/
			marker.bindPopup(content, {
				closeOnEscapeKey: false,
				offset: L.point(0, -26),
				closeButton: false,
				className: 'popup'
			});
			marker.on('click', () => onClickMarkerStation(marker));
			marker.addTo(layerGroup);
		} else {
			alert('El código de estado fue: ' + this.status);
		}
	}
}
/*FIN Mostrar marcador seleccionado y otro marcador con la estación más cercana ------------------*/

/*Lo que ocurre si se presiona click en el marcador de una estación. ----------------------*/
function onClickMarkerStation(marker) {
	/*Obtengo las opciones agregadas al marcador.*/
	let infoStation = marker.options;
	
	/*Si ambos reprsentan una estación y son el mismo...*/
	if(infoStation.represents === "station" && 
	   eventBackup !== null && 
       eventBackup.options.represents === "station" &&
       eventBackup.options.id === infoStation.id) {
		/*Se deselecciona.*/
		eventBackup = null;
		marker.setIcon(markerStation);
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
							marker.setIcon(markerStationActive);
							/*Se almacena como marcador activo.*/
							eventBackup = marker;						
							/*Se pinta el collapse con los datos metereológicos.*/
							paintCollapse(infoStation, lastData);
						} else {
							/*Si ya hay un marcador activo...*/
							/*Si representa una ubicación seleccionada por el usuario...*/
							if (eventBackup.options.represents === "click") {
								/*Se cambia por el marcador de selección del usuario "inactivo".*/
								eventBackup.setIcon(markerSelected);
								/*Se cambia el marcador de estación al "activo".*/
								marker.setIcon(markerStationActive);
								/*Se almacena como marcador activo.*/
								eventBackup = marker;
								/*Se pinta el collapse con los datos metereológicos.*/
								paintCollapse(infoStation, lastData);
							} else if (eventBackup.options.represents === "station") {
								/*Si representa una estación...*/
								/*Se cambia el activo por el marcador de estación "inactivo".*/
								eventBackup.setIcon(markerStation);
								/*Se cambia el marcador de estación al "activo".*/
								marker.setIcon(markerStationActive);
								/*Se almacena como marcador activo.*/
								eventBackup = marker;
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
function onClickMarkerUser(markerUser) {
	/*Si ningún marcador está activo...*/
	if (eventBackup === null) {
		/*Se cambia el marcador al "activo".*/
		markerUser.setIcon(markerSelectedActive);
		/*Se almacena como marcador activo.*/
		eventBackup = markerUser;
	} else {
		/*Si ya hay un marcador activo...*/
		/*Si representa una ubicación seleccionada por el usuario...*/
		if (eventBackup.options.represents === "click") {
			/*Se deselecciona*/
			/*Se cambia el marcador al "inactivo".*/
			markerUser.setIcon(markerSelected);
			/*Se almacena que no hay marcador activo.*/
			eventBackup = null;	
		} else if (eventBackup.options.represents === "station") {
			/*Si representa una estación...*/
			/*Se cambia el activo por el marcador de estación "inactivo".*/
			eventBackup.setIcon(markerStation);
			/*Se cambia el marcador de estación al "activo".*/
			markerUser.setIcon(markerSelectedActive);
			/*Se almacena como marcador activo.*/
			eventBackup = markerUser;
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

/*Desplegar collapse de todas las estaciones. ---------------------------------------------------------*/
function showCollapseAllStations() {
	/*Se obtiene o crea el elemento collapse y se abre automáticamente para poder visualizar la información.*/
	let collapseElement = document.getElementById('collapseAllStations');
	let collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapseElement);
	collapseInstance.show();
}
/*FIN - Desplegar collapse de todas las estaciones. ---------------------------------------------------------*/

/*Mostrar todas las estaciones en el mapa y en el listado de la barra lateral --------------------------*/
function showAllStations(idStation = null) {
	return new Promise(resolve => {
		markerToReturn = null;
		/*Petición AJAX*/
		let req = makeRequest();
		
		if(!req) {
			alert('ERROR : No es posible crear una instancia XMLHTTP');
		} else {
			req.onreadystatechange = function () {
				if (this.readyState == 4) {
					if(this.status == 200) {
						let responseJson = JSON.parse(this.responseText);
						let arrayStations = responseJson.result;
						/*Se crea un fragmento para agregar los items y luego pasarlos al DOM (mejora de rendimiento).*/
						const fragment = document.createDocumentFragment();
						/*Se recorren las estaciones.*/
						arrayStations.forEach(function(station) {
							/*Agregando marcador en las coordenadas obtenidas.*/
							let marker = L.marker([station.latitud, station.longitud], 
								{
									icon: markerStation, 
									id: station.id, 
									name: station.nombre, 
									localidad: station.localidad,
									represents: "station"
								});
							/*Creando contenido de popup*/
							const content = 	
							'<div class="containerTitlePopup">' +
								'<strong class="titlePopup">Estación Metereológica</strong>' +
							'</div>' +
							'<h5 class="mt-3 text-break px-2">' + station.nombre + '</h5>' +
							'<p class="text-muted coordenadasPopup">lat: ' + (+station.latitud).toPrecision(9) + ', lng: ' + (+station.longitud).toPrecision(9) +'</p>';
							/*Agregando popup*/
							marker.bindPopup(content, {
								closeOnEscapeKey: false,
								offset: L.point(0, -26),
								closeButton: false,
								className: 'popup'
							});
							marker.on('click', () => onClickMarkerStation(marker));
							marker.addTo(layerGroup);
							
							if(station.id === idStation)
								markerToReturn = marker;
							
							/*Creando los elementos HTML con sus atributos y contenido.*/
							const a = document.createElement("a");
							a.setAttribute("href", "#");
							a.classList.add("list-group-item", "list-group-item-action", "flex-column", "align-items-start");
							
							const div = document.createElement("div");
							div.classList.add("d-flex", "w-100", "justify-content-between");
							a.appendChild(div);
							
							const h5 = document.createElement("h5");
							h5.classList.add("mb-2");
							h5.textContent = station.nombre;
							div.appendChild(h5);
							
							const small = document.createComment("<!-- <small>Corriendo hace: 3 meses (sin implmenetar)</small> -->");
							div.appendChild(small);
							
							const p = document.createElement("p");
							p.classList.add("mb-1");
							p.textContent = "Localidad: " + station.localidad;
							a.appendChild(p);
							
							/*Se le asigna al elemento <a>, la funcionalildad de seleccionar la estación 
							que corresponde al hacerle click, además de pintar los datos metereológicos de
							dicha estación en el collapse de estación seleccionada.*/
							a.addEventListener('click', async () => {
								let mark = null;
								/*Si no se están mostrando todas las estaciones...*/
								if(shownAllStation === false) {
									/*Se borran los posibles marcadores previos que estuviesen agregados al grupo.*/
									layerGroup.clearLayers();
									/*Se muestran todas las estaciones registradas nuevamente.*/
									mark = await showAllStations(station.id);
									if(mark === null)
										alert("ERROR: No se encontró registrada la estación seleccionada.")
									/*Se marca que se están mostrando todas las estaciones.*/
									shownAllStation = true;
								} else {
									mark = marker;
								}
								if(mark.isPopupOpen()) {
									mark.closePopup();
								} else {
									mark.openPopup();
								}
								onClickMarkerStation(mark);
							});
							
							/*Se agrega el elemento de la etiqueta <a> al fragmento.*/
							fragment.appendChild(a);
						});
						/*Se obtiene el elemento del DOM que contendrá todos los items de cada estación.*/
						const infoElement = document.getElementById('infoCollapseAllStations');
						/*Asegurándose de que no tenga items ya ingresados.*/
						infoElement.innerHTML = "";
						/*Agregando el fragmento.*/
						infoElement.appendChild(fragment);
						/*Se despliega el collapse de todas las estaciones.*/
						showCollapseAllStations();
					} else {
						alert('El código de estado fue: ' + this.status);
					}
					resolve(markerToReturn);
				}
			}
			let fileURL = '../Controllers/AjaxController.jsp';
			let param = '?accion=verTodoEstaciones';
			req.open('GET', fileURL + param, true);
			req.send();
		}
		/*FIN - Petición AJAX*/
	})
	/*FIN Promesa*/
}
/*FIN - Mostrar todas las estaciones en el mapa y en el listado de la barra lateral --------------------------*/