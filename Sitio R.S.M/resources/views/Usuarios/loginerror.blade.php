@include("Navbars.navbarinvitado")
    <!DOCTYPE html>
<html>

<head>

</head>

<body style="background-color:#ffa500;">

<div class="container" style="margin-top: 10%; text-align: center;">

    <div class="card bg-light" style="max-width: 500px;margin: 0 auto; float: none;">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <b style="font-size: xx-large">R.S.M</b>
            </div>
            <br>
            <div class="alert alert-danger" role="alert" style="text-align: center; width: 110%; margin-left: -4.45%;">
                ERROR: Credenciales Invalidas.
            </div>
            <h4 class="card-title mt-3 text-center">Ingresa</h4>
            <form id="formid" onsubmit="return formSubmit(this);" action="{{ route('loginController') }}" method="POST">
                @csrf
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input name="email" class="form-control" placeholder="Nombre de Usuario" type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input name="pass" class="form-control" placeholder="Password" type="password">
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
