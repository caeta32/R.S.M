/*Se define el proveedor de tiles*/
const tilesProvider = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

/*Definir iconos de markers personalidazados*/
const iconStation = "../img/iconosMapa/redMarker/mapas-y-banderas.png";
const markerStation = L.icon({
	iconUrl: iconStation,
	iconSize: [50, 50],
    iconAnchor: [25, 50],
});
const iconStationActive = "../img/iconosMapa/redBorderMarker/mapas-y-banderas.png";
const markerStationActive = L.icon({
	iconUrl: iconStationActive,
	iconSize: [50, 50],
    iconAnchor: [25, 50],
});
const iconSelected = "../img/iconosMapa/blueMarker/push-pin.png";
const markerSelected = L.icon({
	iconUrl: iconSelected,
	iconSize: [50, 50],
    iconAnchor: [0, 50],
});
const iconSelectedActive = "../img/iconosMapa/blueBorderMarker/push-pin.png";
const markerSelectedActive = L.icon({
	iconUrl: iconSelectedActive,
	iconSize: [50, 50],
    iconAnchor: [0, 50],
});

/*VARIABLES AUXILIARES*/

/*Variable global para almacenar el marcador al que se ha hecho click
o null si no hay ningún marcador "activo" por hacerle click.*/
let eventBackup = null;

let hideFromDefault = false;
let hiddenCollapseStation = true;
let shownAllStation = true;
let click = 0;