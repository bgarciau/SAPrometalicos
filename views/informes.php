<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }
    ?>
    <div class="base">
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
        <div class="contenedor">
            informes
        </div>
        <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
    </div>
</body>

</html>