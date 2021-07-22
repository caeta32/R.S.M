/**
 * PARA OTRAS CARGAS AUTOMÁTICAS NECESARIAS UNA VEZ CARGADA LA PÁGINA.
 */

/*Se define el cambio de información en caso de que se esté ocultando el Collapse de información de una estación*/
let collapseElement = document.getElementById('collapseSelectedStation');
collapseElement.addEventListener('hidden.bs.collapse', function() {
	/*Se se ha ocultado desde la función paintCollapseByDefault...*/
	if (hideFromDefault === true) {
		/*Se pinta un mensaje para indicar que no hay información para mostrar.*/
		document.getElementById('infoCollapseSelectedStation').innerHTML = 
			    '<div class="list-group-item border-0 text-center text-break" style="font-size: 1.1rem;">'
			  + 	'<strong class="text-muted">Aún no ha seleccionado ninguna estación.</strong>'
			  + '</div>';
		hideFromDefault = false;
	}
	hiddenCollapseStation = true;
});
/*FIN - Se define el cambio de información en caso de que se esté ocultando el Collapse de información de una estación*/
/* ----------------------------------------------------------------------------------------- */
/*Se define acción cuando el collapse de una estación seleccionada se abrió.*/
collapseElement.addEventListener('shown.bs.collapse', function() {
	hiddenCollapseStation = false;
});
/*FIN - Se define acción cuando el collapse de una estación seleccionada se abrió.*/
/* ----------------------------------------------------------------------------------------- */