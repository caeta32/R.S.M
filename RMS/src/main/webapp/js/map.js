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

/*Se desactiva el zoom al hacer doble click*/
map.doubleClickZoom.disable();

map.on('click', e => {
	if(shownAllStation === false) {
		if(eventBackup !== null) {
			if(eventBackup.target.options.represents === "station") {
				paintCollapseByDefault();
			}
			eventBackup = null;
		}
		/*Se borran los posibles marcadores previos que estuviesen agregados al grupo.*/
		layerGroup.clearLayers();
		shownAllStation = true;
	}
})

/*Se define la funcionalidad al hacer doble click sobre el mapa.*/
/*Lo realizado es pintar un marcador donde hizo doble click el usuario y 
en la estación más cercana.*/
map.on('dblclick', e => {
	/*Se obtienen las coordenadas de donde ocurrió el evento de click.*/
	let latLng = map.mouseEventToLatLng(e.originalEvent);
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
	markerUser.on('click', onClickMarkerUser);
	markerUser.addTo(layerGroup);
	
	/*Petición AJAX ---------------------------------------------------------------*/
	
	/*Se crea el objeto XMLHttpRequest haciendo controles de compatibilidad.*/
    request = makeRequest();
	/*En caso de no lograr crear el objeto XMLHttpRequest*/
	if (!request) {
        alert('ERROR : No es posible crear una instancia XMLHTTP');
		return false;
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