@include('Navbars.navbarinvitado')


<div class="container py-3">
    <div class="card bg-light" style="padding: 3%; max-width: 1600px; height: 90%; margin: 5% auto; float: none;">
        <div style="text-align: center; color: #000000; margin-top: 0%; margin-bottom: 0%"><h1>Forma parte de la R.S.M</h1></div>
        <hr>
        <div class="row">
            <div class="col-6">
                <img src="{{URL::asset('/rsm1.png')}}" style="max-width:100%; height:auto; border-radius: 10px">
            </div>
            <div class="col" style="margin-top: 0%">
               <p style="font-size: x-large; font-weight: bolder">Luego de ejecutar el archivo descargado (<b><a href="{{'https://www.python.org/'}}" target="_blank">Python requerido</a></b>), los pasos a seguir son extremadamente
                sencillos:</p>
                <ul style="font-size: x-large; font-weight: bolder">
                    <li>Introducir un nombre para tu estacion.</li>
                    <li>Elegir el departamento en donde te encuentras.</li>
                    <li>Poner en marcha.</li>
                </ul>
            </div>
        </div>
        <div class="row" style="margin-top: 5%">
            <div class="col" style="margin-top: 8%;">
                <p style="font-size: x-large; font-weight: bolder">Si el nombre de tu estacion esta disponible, el programa comenzara a transmitir los diferentes <b><a href="{{'/faq'}}" target="_blank">datos meteorologicos</a></b> a nuestros servidores. Los mismos quedaran
                disponibles para cualquier usuario que desee verlos.</p>
            </div>
            <div class="col-6">
                <img src="{{URL::asset('/rsm2.png')}}" style="max-width:100%; height:auto; border-radius: 10px">
            </div>
        </div>
        <hr>
        <div class="row">
            <div style="text-align: center">
                <a href="https://drive.google.com/uc?id=1bBSrjWNWkhn1__2KGYwWfDorE6vBL7Nr&export=download" target="_blank" class="btn btn-success" style="width: 20%; margin-top: 5%" role="button">Descargar</a>
            </div>
        </div>
        <!--/row-->
    </div>
</div>

