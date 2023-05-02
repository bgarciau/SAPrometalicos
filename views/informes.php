<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>INFORMES</title>
    <link rel="icon" type="image/png" href="../images/fav.png"/>     <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion

        header("location:../index.php");
    }
    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php');//carga el header
            $i = 1;
            ?>
        </header>
        <div class="contenedor"> <!-- contenido entre el header y el footer -->
            
        </div>
        <footer>
            <?php
            require_once('../php/footer.php');//carga el footer
            ?>
        </footer>
    </div>
</body>

</html>