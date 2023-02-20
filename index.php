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
    <header>
        <div class="wrapper">
            <a href="views/hacerSolicitud" class="logo"><img src="images/logo.png" alt="logo" height="90px" width="auto"></a>
        </div>
    </header>
        <div class="loginback">
            <div class="login">
                <form action="crud/comprueba_login.php" method="post">
                    <div class="form">
                        <h2>INGRESO</h2>
                        <input type="text" placeholder="USUARIO" name="usuario">
                        <input type="password" placeholder="CONTRASEÑA" name="password">
                        <input type="submit" value="INGRESAR" name="Ingresar" class="submit">
                        <input type="button" value="RECUPERAR CONTRASEÑA" class="submit">
                    </div>
                </form>
            </div>
        </div>
        <footer>
            <div class="wrapper2">
                <div class="logo">
                    <img class="imagen" src="images/facebook.png" alt="logo_facebok">
                    <img class="imagen" src="images/instagram.png" alt="logo_instagram">
                    <img class="imagen" src="images/youtube.png" alt="logo_youtube" >
                    <img class="imagen" src="images/whatsapp.png" alt="logo_whatsapp">
                </div>
                <div class="texto">
                    <p style="color: white">Cra 21 N° 72-04 Zona Industrial Alta suiza Manizales, Colombia<br>
                    2023 <br>
                    Todos los derechos reservados</p>
                </div>
            </div>
        </footer>
</body>
</html>