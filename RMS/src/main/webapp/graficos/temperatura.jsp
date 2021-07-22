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
        <h1 style = "text-align: center;">Grafica de Linea</h1>
        <div style="width: 100%; overflow-x: auto;">
            <div :style="{width: (data.length * 30) + 'px', height: '800px'}">
              <canvas id="miGrafico" height="800" width="0"></canvas>
            </div>
          </div>
        <div style="text-align: center;">
        <button type="button" class="btn btn-dark" style="position: absolute; top: 94%; left: 46%" id="download-pdf" >
            Descargar PDF
          </button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.min.js"></script>
        <script src="../js/lineaTemperaturabundle.js" idEstacion="<%=idEstacion%>" tipoFechaGraf="<%=tipoFechaGraf%>"></script>
    </body>
    
</html>