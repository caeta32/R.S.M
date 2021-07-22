<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>

<%@page import="java.net.URL" %>
<%@page import="java.net.HttpURLConnection" %>
<%@page import="java.io.InputStreamReader" %>
<%@page import="java.io.BufferedReader" %>
<%@page import="java.io.PrintWriter" %>

<%
// Se recibe la acción.
String accion = request.getParameter("accion");
//Se especifica la URL.
String ruta = "http://127.0.0.1:80/sitio-cliente_JEE/R.S.M/Servicios%20R.S.M/";
// Variables para las diferentes acciones.
String params = "";
String aux1 = "";
String aux2 = "";

switch (accion) {
	case "obtenerEstacionMasCercana":
		aux1 = request.getParameter("latitud");
		aux2 = request.getParameter("longitud");
		params = "?accion="+accion+"&longitud="+aux2+"&latitud="+aux1;		
		break;
	case "verDatosDeEstacion":
		aux1 = request.getParameter("id");
		params = "?accion="+accion+"&id="+aux1;		
		break;
	default:
		params = "";
		
}

//Se crea el resultado.
StringBuilder result = new StringBuilder();
//Se crea un objeto de tipo URL con la URL de la API y los parámetros correspondientes.
URL url = new URL(ruta + params);
//Se abre la conexión y se indica que será de tipo GET.
HttpURLConnection conn = (HttpURLConnection) url.openConnection();
conn.setRequestMethod("GET");
//Búferes para leer.
BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));
String line;
//Mientras el BufferedReader se pueda leer, agrega contenido al resultado.
while ((line = reader.readLine()) != null) {
	result.append(line);
}
//Se cierra el BufferedReader.
reader.close();

//Se define el tipo de contenido en la cabecera de la respuesta HTTP.
response.setContentType("text/plain ");

PrintWriter pw = response.getWriter();
pw.print(result.toString());
%>