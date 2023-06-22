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
    <div style="min-height: 80vh;" id="principal" class="loginback">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="card mt-5">
                        <div class="card-header bg-dark text-white text-center">
                            <h4>INGRESO</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="crud/comprueba_login.php">
                                <div class="form-group">
                                    <label for="usuario">
                                        <i class="bi bi-person-fill"></i>USUARIO:</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="password"><i class="bi bi-key-fill"></i>Contraseña:</label>
                                    <div class="input-group">
                                        <input id="password" name="password" type="password" Class="form-control" required>
                                        <div class="input-group-append">
                                            <button id="show_password" class="btn btn-dark" type="button"
                                                onclick="mostrarPassword('password')"> <i id="icon" class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <h6 style="color:red;font-size:small" id="loginIncorrecto" hidden>*Usuario o contraseña incorrectos*</h6>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block mt-4" onclick="ingresar()" value="INGRESAR" name="Ingresar">Iniciar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
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
<script type="text/javascript">
    function mostrarPassword(nombreInput){
        // console.log("entra a la funcion mostrar pass");
        // console.log($('#password').prop('type'));
		if($('#'+nombreInput).prop('type') == "password"){
            // console.log("entra a tipo password");
			$('#'+nombreInput).prop('type',"text");
			$('#icon').removeClass('bi bi-eye-slash').addClass('bi bi-eye');
		}else{
            // console.log("entra a tipo text");
			$('#'+nombreInput).prop('type',"password");
			$('#icon').removeClass('bi bi-eye').addClass('bi bi-eye-slash');
		}
	}
    
    if ('<?php echo $log ?>' == "mal") {
        $('#loginIncorrecto').prop("hidden",false);
    }
    function ingresar() {
        $('#principal').fadeOut();
        $('#carga').prop("hidden", false);
        $('#Ingresar').click();
    }
</script>

</html>