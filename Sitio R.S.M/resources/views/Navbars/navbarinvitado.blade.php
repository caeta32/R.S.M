<?php
?>

<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" /><body style="background-color: #254746">
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>TuTienda</title>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">    <!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #254746;">
    <a class="navbar-brand" href="/" style="margin-left: 3%; color: #DCAE52; font-size: 1.7em">R.S.M</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/"  style="color: #FFFFFF">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{'/estaciones'}}"  style="color: #FFFFFF">Estaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"  style="color: #FFFFFF">Forma Parte de la Red</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"  style="color: #FFFFFF">Preguntas Frecuentes</a>
            </li>
        </ul>
    </div>
    <a class="nav-link" href="{{'/login'}}" style="color: #DCAE52">Iniciar Sesion</a>
    <a class="nav-link" href="{{'/registro'}}" style="color: #DCAE52; margin-right: 3%">Registrate</a>

</nav>

</body>

</html>
