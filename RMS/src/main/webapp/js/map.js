/*Se pinta el texto por defecto en el collapse de estación seleccionada.*/
paintCollapseByDefault();

/*Se crea el mapa*/
let map = L.map('map');

/*Se agrega al mapa el grupo de capas al que le serán agregados los marcadores.*/
const layerGroup = L.layerGroup().addTo(map);

/*Se intenta ubicar el centro del mapa en la localización del usuario.
En caso de no lograrlo, se posiciona en el centro de Uruguay a un zoom alejado.*/
map.locate()
	.on('locationfound', function(e) {
		let latlng = e.latlng;
		map.setView(latlng, 15);
	})
	.on('locationerror', function(e) {
		console.log(e);
		map.setView([-32.7891847, -56.0686270], 7);
	});

L.tileLayer(tilesProvider, {
	maxZoom: 18
}).addTo(map);
/*attribution: "Map data © <a href='https://www.openstreetmap.org/copyright' target='_blank'>OpenStreetMap</a> contributors, CC-BY-SA",*/

/*Mostrar todas las estaciones en el mapa una vez cargado y listarlas.*/
showAllStations();

/*Se desactiva el zoom al hacer doble click*/
map.doubleClickZoom.disable();

/*Se define la funcionalidad al hacer click en cualquier punto del mapa.*/
map.on('click', () => {
	click = 1;
	setTimeout(function () {
		if(click === 1) {
			if(shownAllStation === false) {
				if(eventBackup !== null) {
					if(eventBackup.options.represents === "station") {
						paintCollapseByDefault();
					}
					eventBackup = null;
				}
				/*Se borran los posibles marcadores previos que estuviesen agregados al grupo.*/
				layerGroup.clearLayers();
				/*Se muestran todas las estaciones registradas nuevamente.*/
				showAllStations();
				/*Se marca que se están mostrando todas las estaciones.*/
				shownAllStation = true;
			} else {
				if (eventBackup !== null) {
					if(eventBackup.options.represents === "station") {
						eventBackup.setIcon(markerStation);
						paintCollapseByDefault();
					}
					eventBackup = null;
				}
			}
			click = 0;
		}
	}, 250);
})

/*Se define la funcionalidad al hacer doble click sobre el mapa.*/
/*Lo realizado es pintar un marcador donde hizo doble click el usuario y 
en la estación más cercana.*/
map.on('dblclick', e => {
	click = 0;
	/*Se obtienen las coordenadas de donde ocurrió el evento de click.*/
	let latLng = map.mouseEventToLatLng(e.originalEvent);
	if(eventBackup !== null) {
		eventBackup = null;
	}
	/*Se borran los posibles marcadores previos que estuviesen agregados al grupo.*/
	layerGroup.clearLayers();
	/*Se almacena que ya no se están mostrando todas las estaciones.*/
	shownAllStation = false;		
	/*Agregando marcador en las coordenadas obtenidas.*/
	let markerUser = L.marker([latLng.lat, latLng.lng], 
			 {
				icon: markerSelected,
				represents: "click"
			 });
	/*Creando contenido de popup*/
	const content = 	
	'<div class="containerTitlePopup">' +
		'<strong class="titlePopup">Ubicación Seleccionada</strong>' +
	'</div>' +
	'<p class="text-muted coordenadasPopup">lat: ' + +(latLng.lat).toPrecision(9) + ', lng: ' + +(latLng.lng).toPrecision(9) +'</p>';
	/*Agregando popup*/
	markerUser.bindPopup(content, {
		closeOnEscapeKey: false,
		offset: L.point(30, -25),
		closeButton: false,
		className: 'popup'
	});
	markerUser.on('click', () => onClickMarkerUser(markerUser));
	markerUser.addTo(layerGroup);
	
	/*Petición AJAX ---------------------------------------------------------------*/
	
	/*Se crea el objeto XMLHttpRequest haciendo controles de compatibilidad.*/
    let request = makeRequest();
	/*En caso de no lograr crear el objeto XMLHttpRequest*/
	if (!request) {
        alert('ERROR : No es posible crear una instancia XMLHTTP');
    } else {
		/*Si se logró crear el objeto XMLHttpRequest*/
		
		/*Se asignan las acciones ante el cambio de estado*/
		request.onreadystatechange = selectedMarkerAndNearestStation;
		/*Se define URL*/
		let fileUrl = '../Controllers/AjaxController.jsp';
		let params = '?accion=obtenerEstacionMasCercana&longitud='+latLng.lng+'&latitud='+latLng.lat;
		request.open('GET', fileUrl + params, true);
		request.send();
	}
	/*FIN Petición AJAX ---------------------------------------------------------------*/
});