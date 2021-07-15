<?php
?>

@include('Navbars.navbarinvitado')
<head>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>

<div style="width: 17%; height: 92.9vh; position: absolute">
    <div class="list-group" style="border-radius: 0px">
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Aficionados del Tiempo</h5>
                <small>Corriendo hace: 3 meses</small>
            </div>
            <p class="mb-1">Localidad: Maldonado.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Meteorologia Artigas</h5>
                <small>Corriendo hace: 25 dias</small>
            </div>
            <p class="mb-1">Localidad: Artigas.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Florida R.S.M</h5>
                <small>Corriendo hace: 9 meses</small>
            </div>
            <p class="mb-1">Localidad: Florida.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Los de Tacuarembo</h5>
                <small>Corriendo hace: 3 horas</small>
            </div>
            <p class="mb-1">Localidad: Tacuarembo.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Estacion Salto</h5>
                <small>Corriendo hace: 11 meses</small>
            </div>
            <p class="mb-1">Localidad: Salto.</p>
        </a>
    </div>
</div>

<iframe src="{{url('/mapa')}}"  marginwidth="0"
        marginheight="0"style="right: 0; width: 83%; height: 94.72vh; position: absolute"></iframe>

</div>
</body>




