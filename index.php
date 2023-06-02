<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('views/head.php');
    $log = "normal";
    if (isset($_GET["log"])) { //confirma si el usuario ya inicio sesion
        $log = "mal";
    }
    ?>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="navbar  navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-bottom-dark"
        data-bs-theme="dark">
        <div class="container-fluid">
            <img src="images/logo.png" alt="PROMETALICOS" height="80px">
        </div>
    </nav>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="images/carga.gif">
    </div>
    <div class="contenedor-login" style="min-height: 80vh;" id="principal"> <!-- Contenedido entre el header y el footer -->
        <div class="loginback"> <!-- le pone un gif al fondo del login -->
            <div class="login"><!-- acomoda el recuadro del login en el centro -->
                <form action="crud/comprueba_login.php" method="post">
                    <!-- Formulario dle login que manda la informacion recibida a comprueba login que lo que hace es comprobar los datos en la base de datos -->
                    <div class="form">
                        <h2>INGRESO</h2>
                        <input type="text" placeholder="USUARIO" name="usuario">
                        <input type="password" placeholder="CONTRASEÑA" name="password">
                        <p style="color:red;font-size:small" id="loginIncorrecto" hidden>*Usuario o contraseña
                            incorrectos*</p>
                        <input onclick="ingresar()" type="button" value="INGRESAR" name="Ingresar" class="submit">
                        <input type="submit" value="INGRESAR" id="Ingresar" class="submit" hidden>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <img src="images/logo.png" alt="" height="120px">
                </div>
                <div class="col-md-5">
                    <i class="bi bi-geo-alt-fill"> Cra 21 N° 72 -04 Zona Industrial Alta Suiza Manizales, Colombia</i>
                    <br>
                    <i class="bi bi-telephone-fill"> NUMERO</i>
                    <br>
                    <i class="bi bi-envelope-fill"> CORREO</i>
                </div>
            </div>
            <div class="copyright text-center">
                &copy; 2023 PROMETALCIOS
            </div>
        </div>
    </footer>
</body>
<script>
    if ('<?php echo $log ?>' == "mal") {
        $('#loginIncorrecto').show();
    }
    function ingresar() {
        $('#principal').fadeOut();
        $('#carga').prop("hidden", false);
        $('#Ingresar').click();
    }
</script>

</html>