<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="images/fav.png"/>  <!-- imagen del fav -->
    <link rel="stylesheet" href="css/style.css">     <!-- estilos del contenido de la pagina -->
    <link rel="stylesheet" href="css/hfstyle.css">     <!-- estilos del header y el footer -->
    <link rel="stylesheet" href="css/login.css">        <!-- estilos del login -->
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>
<?php
    $log="normal";
    if (isset($_GET["log"])) { //confirma si el usuario ya inicio sesion
        $log="mal";
    }
?>
<body>
<div class="base"> <!-- Vista de  la pagina -->
    <header>
    <div class="wrapper-login"> <!-- Contenedor del header -->
        <a><img src="images/logo.png" alt="logo" height="100%" width="auto"></a> <!-- Logo de la empresa -->
    </div>
    </header>
    <div class="contenedor" id="carga" hidden>
            <img id="centrar-carga" src="images/carga10.gif">
    </div>
    <div class="contenedor-login" id="principal"> <!-- Contenedido entre el header y el footer -->
    <div class="loginback"> <!-- le pone un gif al fondo del login -->
        <div class="login"><!-- acomoda el recuadro del login en el centro -->
            <form action="crud/comprueba_login.php" method="post"> <!-- Formulario dle login que manda la informacion recibida a comprueba login que lo que hace es comprobar los datos en la base de datos -->
                <div class="form">
                    <h2>INGRESO</h2>
                    <input type="text" placeholder="USUARIO" name="usuario">
                    <input type="password" placeholder="CONTRASEÑA" name="password">
                    <p style="color:red;font-size:small" id="loginIncorrecto" hidden>*Usuario o contraseña incorrectos*</p>
                    <input onclick="ingresar()" type="button" value="INGRESAR" name="Ingresar" class="submit">
                    <input type="submit" value="INGRESAR" id="Ingresar" class="submit" hidden>
                </div>
            </form>
        </div>
    </div>
</div>
    <footer>
    <div class="wrapperFooter">
        <div class="logo">
            <img class="sena" src="images/Sena.png" alt="logo_sena">
        </div>
        <div class="texto">
            <p style="color: white">Cra 21 N° 72-04 Zona Industrial Alta suiza Manizales, Colombia<br>
            20<?php echo date("y")?> <br>
            Todos los derechos reservados</p>
        </div>
    </div>
    </footer>
</div>
</body>
<script>
    if('<?php echo $log ?>' == "mal"){
        $('#loginIncorrecto').show();
    }
    function ingresar(){
        $('#principal').fadeOut();
        $('#carga').prop("hidden",false);
        $('#Ingresar').click();
    }
</script>
</html>