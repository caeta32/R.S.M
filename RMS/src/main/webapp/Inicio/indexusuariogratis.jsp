<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<%String nombreUsuario=(String)session.getAttribute("username"); %>

<!DOCTYPE html>
<html>
<head>
    <title>R.S.M</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>    
<body style="background-color: #254746; margin:0px;padding:0px;overflow:hidden">
<div style="position: fixed; width: 100%;">
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #254746;">
    <a class="navbar-brand" href="iniciousuariogratis.html" target="miIframe" style="margin-left: 3%; color: #DCAE52; font-size: 1.7em">R.S.M</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="iniciousuariogratis.html" target="miIframe" style="color: #FFFFFF">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../estaciones/estaciones.html" target="miIframe"  style="color: #FFFFFF">Estaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../formaparte-faq/formaparte.html" target="miIframe"  style="color: #FFFFFF">Forma Parte de la Red</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../formaparte-faq/preguntasfrecuentes.html" target="miIframe"  style="color: #FFFFFF">Preguntas Frecuentes</a>
            </li>
        </ul>
    </div>
    <a class="navbar-brand" href="#" style=" color: #DCAE52; font-size: large; margin-left: 1%; "><i class="fa fa-user-circle" aria-hidden="true" style="padding-right: 10%"></i><%=nombreUsuario%></a>
    <a class="nav-link" href="#" style="color: #DCAE52; margin-right: 3%; font-size: large">Salir</a>

</nav>
</div>
<iframe src="iniciousuariogratis.html" name="miIframe" style="overflow:hidden;height:100vh;width:100vw" height="100%" width="100%"></iframe>

</body>
</html>
