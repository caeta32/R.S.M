@include('Navbars.navbarinvitado')
    <!DOCTYPE html>
<html>
<head>
    <!------ Include the above in your HEAD tag ---------->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        if(grecaptcha.getResponse() == "") {
            alert("Por favor, completa el captcha.");
        } else {}
    </script>
</head>
<body style="background-color:#ffa500;">

<div class="container" style="margin-top: 2%;">

    <div class="card bg-light">
        <article class="card-body mx-auto" style="max-width: 1000px;">
            <div style="text-align: center;">
                <b style="font-size: xx-large">R.S.M</b>
            </div>
            <br>
            <div class="alert alert-danger" role="alert" style="text-align: center; width: 110%; margin-left: -4.45%;">
                Email ya registrado, intente nuevamente.
            </div>
            <h4 class="card-title mt-3 text-center">Crea tu cuenta</h4>
            <form id="formid" action="{{ route('registrarController') }}" method="POST"> @csrf
                <div class="form-group input-group">
                    <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-user"></i> </span> </div>
                    <input name="nombre" class="form-control" placeholder="Nombre" type="text" required>
                    <div class="input-group-prepend" style="margin-left: 5%;"> <span class="input-group-text"> <i class="fa fa-user"></i> </span> </div>
                    <input name="apellido" class="form-control" placeholder="Apellido" type="text" required> </div>
                <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-envelope"></i> </span> </div>
                    <input name="email" class="form-control" placeholder="Correo Electronico" type="email" required> </div>
                <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-lock"></i> </span> </div>
                    <input name="pass" class="form-control" placeholder="Contrase??a" minlength="8" type="password" required> </div>
                <!-- form-group// -->
                <div class="form-group input-group">
                    <div class="input-group-prepend"> <span class="input-group-text"> <i class="fa fa-lock"></i> </span> </div>
                    <input name="passconf" class="form-control" placeholder="Confirmar contrase??a" minlength="8" type="password" required> </div>
                <!-- form-group// -->
                <div style="text-align:center;">
                    <div class="g-recaptcha" data-sitekey="6LcIOAIbAAAAAMSBCU7S48MUYrr58lNSsnanq4lw" data-callback="recaptchaCallback" style="display: inline-block; padding-bottom: 12px"></div>
                </div>
                <div class="form-group">
                    <button id="btnFetch" type="submit" class="btn btn-primary btn-block hidden"> Registrate </button>
                </div>
                <input type="hidden" id="nivelacceso" name="nivelacceso" value="a">
                <!-- form-group// -->
                <p class="text-center">Ya tienes una cuenta? <a href="{{url('/login')}}">Ingresar</a> </p>
            </form>
        </article>
    </div>
    <!-- card.// -->
</div>

<!--container end.//-->
</body>
<script>
    $("form").submit(function(event) {
        var recaptcha = $("#g-recaptcha-response").val();
        if(recaptcha === "") {
            event.preventDefault();
            alert("Por favor, completa el reCAPTCHA");
        }
    });
</script>
