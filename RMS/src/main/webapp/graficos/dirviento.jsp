<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<%String idEstacion = (String) session.getAttribute("idEstacion"); %>
<%String tipoFechaGraf = (String) session.getAttribute("tipoFechaGraf"); %>

<!DOCTYPE html>
<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/styles.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.js" integrity="sha512-15/VHS2R4JdAmwfmS9BOoknhwUlccJjW4U6+9jBZXwUTXGdwxM03bUFk1ihHvcNjdRR7icUiLAqySrX+k0aaYQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    </head>
    <body>
        <h1 style = "text-align: center;">Grafica de Radar</h1>
        <canvas id="miGrafico" width="800" height="800" style="margin:0 auto;"></canvas>
        <button type="button"  style="position: absolute; top: 94%; left: 46.5%" class="btn btn-dark" id="download-pdf" >
            Descargar PDF
          </button>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js"></script>
 		<script src="../js/radarDirViento.js" idEstacion="<%=idEstacion%>" tipoFechaGraf="<%=tipoFechaGraf%>"></script>
    </body>
</html>      
