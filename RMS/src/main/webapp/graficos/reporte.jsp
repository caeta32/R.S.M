<%String idEstacion = (String) session.getAttribute("idEstacion"); %>
<%String tipoFecha = (String) session.getAttribute("tipoFecha"); %>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Insert title here</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js" integrity="sha512-y7bAIyWa5Ncv0AFN0tcz4QLyPFVIHqN66hvNyIY0uryA8RIcKNyR9MjHpuUY95Baj2KwQ7EdRWoer7vLimZAHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="body-index" style="background-color: #254746;">
    <div style="text-align: center;  margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);">
        <div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <b style="font-size: xx-large">Reporte Generado.</b>

            </div>
             <br>
             <br>
            <div style="margin-left: 5px">
        <button class="btn btn-dark" type="button" style="min-width: 300px;" id="download-pdf">Descargar Reporte</button>
        </div>
        </article>
        </div>
    </div>
    <script src="../js/pdf.js" idEstacion="<%=idEstacion%>" tipoFecha="<%=tipoFecha%>"></script>
</body>
</html>