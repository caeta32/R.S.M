<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>

<%@page import="java.net.*" %>
<%@page import="java.io.*" %>
<%@page import="BCrypt.*" %>

<%

String email = request.getParameter("email");
String password = request.getParameter("pass");
StringBuilder result = new StringBuilder();
String hashed = BCrypt.hashpw(password, BCrypt.gensalt());

try{
	   URL dest = new URL("http://localhost/sitio-cliente_JEE/R.S.M/Servicios%20R.S.M/?accion=verUsuario&email=" + email);
	   URLConnection yc = dest.openConnection();
	   BufferedReader in = new BufferedReader(
	                           new InputStreamReader(
	                           yc.getInputStream()));

	   String inputLine;
	   while ((inputLine = in.readLine()) != null){
	       System.out.println(inputLine);
	   		result.append(inputLine);
	   }
	   in.close();
	   
	   String respuestaHttp = result.toString();
	   String[] datosSeparados = respuestaHttp.split("\"");
	   if(datosSeparados.length <= 9) {
	   	
	   } else {
		/* int i=0;
		for (String s:datosSeparados) {
			System.out.println(i + " : " + s);
			i++;
		} */
	   	String passSinFormatear = datosSeparados[13];
	   	System.out.println("PASS SIN FORMATEAR : " + passSinFormatear);
	   	String passFormateado;
	   	if(passSinFormatear.contains("\\")){
	   		passFormateado = passSinFormatear.replace("\\", "");
	   	} else {
	   		passFormateado = passSinFormatear;
	   	}
		System.out.println("antes del if");
	   	if (BCrypt.checkpw(password, passFormateado)){
	   		System.out.println("después del if");	
	   		HttpSession sesion=request.getSession();  
	   		sesion.setAttribute("username",datosSeparados[13]);  
	   		String redirectURL = "../Inicio/indexusuariogratis.jsp";
	   		response.sendRedirect(redirectURL);
	   		
	   	} else {
	   		System.out.println("En el else del if");
	   		String redirectURL = "../usuarios/loginerror.html";
	   		response.sendRedirect(redirectURL);
	   	}
	   }
	   
} catch (Exception e) {
	System.out.println("En el catch");
	System.out.println(e.getMessage());
	String redirectURL = "../usuarios/loginerror.html";
    response.sendRedirect(redirectURL);
}
%>


