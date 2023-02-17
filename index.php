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
                        <input type="submit" value="RECUPERAR CONTRASEÑA" class="submit">
                    </div>
                </form>
            </div>
        </div>
    <footer>
        <?php
        require_once('php/footer.php');
        ?>
    </footer>
    </footer>
</body>
</html>