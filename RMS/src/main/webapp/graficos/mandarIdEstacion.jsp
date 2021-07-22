<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<% 

String idEstacion = request.getParameter("idEstacion");
String tipoFecha = request.getParameter("tipoFecha");
String tipoFechaGraf = request.getParameter("tipoFechaGraf");
session = request.getSession();


String botonReporte = request.getParameter("generar-reporte");
String botonGraficas =  request.getParameter("ver-graficas");
if(botonReporte!=null && botonReporte.equals("generar-reporte"))
{
	session.setAttribute("tipoFecha", tipoFecha);
	session.setAttribute("idEstacion", idEstacion);
	String redirectURL = "reporte.jsp";
	response.sendRedirect(redirectURL);
} else if(botonGraficas!=null && botonGraficas.equals("ver-graficas")) {
	session.setAttribute("idEstacion", idEstacion);
	session.setAttribute("tipoFechaGraf", tipoFechaGraf);
	String redirectURL = "graficos.html";
	response.sendRedirect(redirectURL);	
}

%>