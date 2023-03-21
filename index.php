<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/hfstyle.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
<div class="base"> <!-- Vista de  la pagina -->
    <header>
    <div class="wrapper-login"> <!-- Contenedor del header -->
        <a><img src="images/logo.png" alt="logo" height="100%" width="auto"></a> <!-- Logo de la empresa -->
    </div>
    </header>
    <div class="contenedor"> <!-- Contenedido entre el header y el footer -->
    <div class="loginback"> <!-- le pone un gif al fondo del login -->
        <div class="login"><!-- acomoda el recuadro del login en el centro -->
            <form action="crud/comprueba_login.php" method="post"> <!-- Formulario dle login que manda la informacion recibida a comprueba login que lo que hace es comprobar los datos en la base de datos -->
                <div class="form">
                    <h2>INGRESO</h2>
                    <input type="text" placeholder="USUARIO" name="usuario">
                    <input type="password" placeholder="CONTRASEÑA" name="password">
                    <input type="submit" value="INGRESAR" name="Ingresar" class="submit">
                </div>
            </form>
        </div>
    </div>
</div>
    <footer>
        <div class="wrapper-footer-login">  <!-- footer del login -->
            <div class="logo"> <!-- Acomoda los logos de las redes en la derecha del footer -->
                <img class="imagen" src="images/facebook.png" alt="logo_facebok">
                <img class="imagen" src="images/instagram.png" alt="logo_instagram">
                <img class="imagen" src="images/youtube.png" alt="logo_youtube">
                <img class="imagen" src="images/whatsapp.png" alt="logo_whatsapp">
            </div>
            <div class="texto"> 
                <p style="color: white">Cra 21 N° 72-04 Zona Industrial Alta suiza Manizales, Colombia<br>
                    20
                    <?php echo date("y") ?> <br>
                    Todos los derechos reservados
                </p>
            </div>
        </div>
    </footer>
</div>
</body>

</html>