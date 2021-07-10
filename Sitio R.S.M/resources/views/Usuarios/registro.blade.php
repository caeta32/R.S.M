@include('Navbars.navbarinvitado')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body style="background-color: #254746">
<div style="margin: 0; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="card" style="width: 18rem; height: 30rem">
                    <i class="fas fa-user fa-5x" style="color: #000000; text-align: center; padding-top: 10%"></i>
                    <div class="card-body" style="margin-top: 15%">
                        <h5 class="card-title" style="text-align: center"><b>Usuario Gratuito</b></h5>
                        <ul>
                            <li>Visualizacion general del tiempo.</li>
                            <li>Acceso a informacion limitada.</li>
                            <li>Sin permisos de modificacion de datos.</li>
                        </ul>
                        <div style="text-align: center">
                            <a href="{{'/altausuario'}}" class="btn btn-primary" style="margin-bottom: -50%">Registrarme</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem; height: 30rem" >
                    <i class="fas fa-user-check fa-5x" style="color: #000000; text-align: center; padding-top: 10%"></i>
                    <div class="card-body" style="margin-top: 15%">
                        <h5 class="card-title" style="text-align: center"><b>Usuario Especializado</b></h5>
                        <ul>
                            <li>Generacion de reportes completos</li>
                            <li>Acceso a informacion detallada.</li>
                            <li>Permisos de manipulacion de datos mas elevados.</li>
                        </ul>
                        <div style="text-align: center">
                            <a href="#" class="btn btn-primary" style="margin-bottom: -50%">Suscribirme</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card" style="width: 18rem; height: 30rem">
                    <i class="fas fa-user-cog fa-5x" style="color: #000000; text-align: center; padding-top: 10%"></i>
                    <div class="card-body" style="margin-top: 15%">
                        <h5 class="card-title" style="text-align: center"><b>Usuario Administrador</b></h5>
                        <ul>
                            <li>Visualizacion de todas las funciones del sistema</li>
                            <li>Capaz de gestionar todos los datos disponibles</li>
                        </ul>
                        <div style="text-align: center;">
                            <a href="#" class="btn btn-primary" style="margin-bottom: -89%">Contactanos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
