<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
    
<%@page import="java.net.*" %>
<%@page import="java.io.*" %>
<%@page import="java.util.*" %>
<%@page import="org.json.*" %>

    
<%
StringBuilder result = new StringBuilder();
StringBuilder idList = new StringBuilder();
StringBuilder nombreEstaciones = new StringBuilder();
URL url = new URL("http://localhost/server-central_JEE/ws/?accion=verTodoDatosEstaciones");
HttpURLConnection conn = (HttpURLConnection) url.openConnection();
conn.setRequestMethod("GET");
try (BufferedReader reader = new BufferedReader(
            new InputStreamReader(conn.getInputStream()))) {
    for (String line; (line = reader.readLine()) != null; ) {
        result.append(line);
    }
}

JSONObject jsonObj = new JSONObject(result.toString());
JSONArray ids = jsonObj.getJSONArray("result");
for (int i = 0 ; i < ids.length(); i++) {
    JSONObject obj = ids.getJSONObject(i);
    idList.append(obj.getString("idEstacion"));
    nombreEstaciones.append("nombre");
}

String idListString = idList.toString();

char[] chars = idListString.toCharArray();
Set<Character> charSet = new LinkedHashSet<Character>();
for (char c : chars) {
    charSet.add(c);
}

StringBuilder sb = new StringBuilder();
for (Character character : charSet) {
    sb.append(character);
}

%>    
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Insert title here</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="body-index" style="background-color: #254746;">
    <div style="text-align: center;  margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);">
    <form action="mandarIdEstacion.jsp" method="POST">
		<div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <b style="font-size: xx-large">R.S.M</b>
            </div>

            <h4 class="card-title mt-3 text-center">Seleccione una estacion</h4>
		<select class="form-control" name="idEstacion" style="min-width: 300px;">     <% for (Character idEstacion : charSet) { %>
        <option>      
        <%=idEstacion%>
        </option>
    <% } %></select>
    <br>
    <select class="form-control" name="tipoFechaGraf" style="min-width: 300px;">
		<option value="Dia">Grafico del Dia</option>
		<option value="Semanal">Grafico Semanal</option>
		<option value="Mes">Grafico Mensual</option>
		<option value="Anual">Grafico Anual</option>
    </select>
    <br>
	<button type="submit" name="ver-graficas" value="ver-graficas" class="btn btn-dark" style="min-width: 300px;">Ver Graficas</button>
	<br>
	<br>
	<h4 class="card-title mt-3 text-center">Reportes</h4>
	<select class="form-control" name="tipoFecha" style="min-width: 300px;">
		<option value="Dia">Reporte del Dia</option>
		<option value="Semanal">Reporte Semanal</option>
		<option value="Mes">Reporte Mensual</option>
		<option value="Anual">Reporte Anual</option>
	</select>
	<br>
	<button type="submit" name="generar-reporte" value="generar-reporte" class="btn btn-dark" style="min-width: 300px;">Generar Reporte</button>
	</article>
	</div>
    </form>
    </div>
</body>
</html>