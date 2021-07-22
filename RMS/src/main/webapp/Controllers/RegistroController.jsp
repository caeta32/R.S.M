<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
 
 
<%@page import="java.net.*" %>
<%@page import="java.io.*" %>
<%@page import="BCrypt.*" %>
<%

String email = request.getParameter("email");
String nombre = request.getParameter("nombre");
String apellido = request.getParameter("apellido");
String password = request.getParameter("pass");
String passwordconfirmado = request.getParameter("passconf");

if(password.equals(passwordconfirmado)) {
	String hashed = BCrypt.hashpw(password, BCrypt.gensalt());

	try{
		URL url = new URL("http://localhost/sitio-cliente_JEE/R.S.M/Servicios%20R.S.M/");
		String postData = "email=" + email + "&nombre=" + nombre + "&apellido=" + apellido + "&nivelAcceso=a&contrasenia="+ hashed + "&accion=nuevoUsuario";
		
		URLConnection conn = url.openConnection();
		conn.setDoOutput(true);
		conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
		conn.setRequestProperty("Content-Length", Integer.toString(postData.length()));
		
		try (DataOutputStream dos = new DataOutputStream(conn.getOutputStream())) {
		    dos.writeBytes(postData);
		}
		
		try (BufferedReader bf = new BufferedReader(new InputStreamReader(
		                                                conn.getInputStream())))
		{
		    String line;
		    while ((line = bf.readLine()) != null) {
		        System.out.println(line);
		    }
		}
		String redirectURL = "../usuarios/login.html";
	    response.sendRedirect(redirectURL);
	} catch (Exception e) {
		System.out.println(e.getMessage());
		String redirectURL = "../usuarios/registererroremail.html";
	    response.sendRedirect(redirectURL);
	}
} else {
	String redirectURL = "../usuarios/registererrorpass.html";
    response.sendRedirect(redirectURL);
}




%>
