@include("Navbars.navbarinvitado")
<!DOCTYPE html>
<html>

<head>
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

<body style="background-color:#ffa500;">

<div class="container" style="margin-top: 10%; text-align: center;">
    <div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <b style="font-size: xx-large">R.S.M</b>
            </div>

            <h4 class="card-title mt-3 text-center">Ingresa</h4>
            <form id="formid" onsubmit="return formSubmit(this);" action="{{ route('loginController') }}" method="POST">
                @csrf
                <div class="form-group input-group" >
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Email" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name="pass" class="form-control" placeholder="Contraseña" type="password">
                </div>

                <div class="form-group">
                    <button  id="btnFetch" type="submit" class="btn btn-primary btn-block"> Acceder </button>
                </div> <!-- form-group// -->
            </form>
        </article>
    </div> <!-- card.// -->

</div>

<!--container end.//-->
</body>

</html>
