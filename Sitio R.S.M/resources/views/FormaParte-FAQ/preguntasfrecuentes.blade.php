@include('Navbars.navbarinvitado')
<head>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<div class="container py-3">
    <div class="card bg-light" style="padding: 3%; max-width: 1600px; height: 90%; margin: 5% auto; float: none;">
        <div style="text-align: center; color: #000000; margin-top: 0%; margin-bottom: 4%"><h1>Preguntas Frecuentes</h1></div>
        <div class="row">
        <div class="col-10 mx-auto">
            <div class="accordion" id="faqExample">
                <div class="card" style="box-shadow: 0px 0px 25px -14px rgba(0,0,0,0.75);">
                    <div class="card-header p-2" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" style="text-decoration: none" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h4>¿Como formar parte de la Red?</h4>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqExample">
                        <div class="card-body">
                            Para formar parte de la R.S.M, necesitas instalar el <a href="{{'/formaparte'}}">CLIENTE R.S.M</a> y <a href="{{'/registro'}}">Registrarte</a> en nuestra pagina, <b>cualquier nivel de usuario puede
                            poner en marcha una estacion meteorologica.</b>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card" style="box-shadow: 0px 0px 25px -14px rgba(0,0,0,0.75);">
                    <div class="card-header p-2" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" style="text-decoration: none" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h4>¿Necesito una computadora potente para correr el Cliente?</h4>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqExample">
                        <div class="card-body">
                            <b>No.</b> Cualquier sistema puede correr este Cliente sin problemas, dado que el mismo consume una cantidad pequeña de recursos.
                        </div>
                    </div>
                </div>
                <br>
                <div class="card" style="box-shadow: 0px 0px 25px -14px rgba(0,0,0,0.75);">
                    <div class="card-header p-2" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" style="text-decoration: none" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <h4>¿Que tipo de datos voy a compartir?</h4>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqExample">
                        <div class="card-body">
                            El cliente se conecta automaticamente a la API de <a href="https://openweathermap.org/" target="_blank">OpenWeatherMap</a> para obtener y compartir los siguientes datos:
                            <br>
                            <ul>
                                <li>Temperatura (Celsius)</li>
                                <li>Humedad (%)</li>
                                <li>Presion Atmosferica (HPa)</li>
                                <li>Velocidad del viento (km/h)</li>
                                <li>Dirección del viento (Puntos Cardinales)</li>
                                <li>Radiación solar (W/m²)</li>
                                <li>Indice de radiación UV</li>
                                <li>Indice pluviométrico (mm)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card" style="box-shadow: 0px 0px 25px -14px rgba(0,0,0,0.75);">
                    <div class="card-header p-2" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" style="text-decoration: none" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <h4>¿Cuanto tiempo debo mantener el cliente activo?</h4>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqExample">
                        <div class="card-body">
                            El cliente debe tener un tiempo de actividad de al menos un <b>90% al mes (27 dias)</b>. Asegurate de poder cumplir este requisito, de lo
                            contrario, <b>tu estacion sera dada de baja.</b>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--/row-->
</div>
</div>
<!--container-->
