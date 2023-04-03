<?php
include("../php/conexion.php");

$usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para los permisos

$registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se toma su tiipo de usuario

foreach ($registros as $Tusuario) {
    $userx = $Tusuario->tipo_usuario;
}
?>
    <link rel="stylesheet" href="../css/hfstyle.css">
    <div class="wrapper">
        <div class="logo">
        <img src="../images/logo.png" alt="logo" height="100%" width="100%  ">
        </div>
        <nav>
            <?php
            if ($userx == 3) { //este es el tipo de usuario administrador
            ?>
            <a href="../views/hacerSolicitud.php">HACER SOLICITUD</a>
            <ul>
                <li class="dropdown">
                    <a href="../views/misSolicitudes.php">MIS SOLICITUDES</a>
                    <ul>
                        <li><a href="../views/solicitudesUsuario.php">SOLICITUDES USUARIOS</a></li>
                    </ul>
                </li>
            </ul>
            <a href="../views/informes.php">INFORMES</a>
            <a href="../views/usuarios.php">USUARIOS</a>
            <a href="../views/servicios.php">SERVICIOS</a>
            <a class="salir" href="../crud/cerrar_session.php"><input class="btn_sal" type="button" value="SALIR"></a>
        <?php
    } else { //otro tipo de usuario que no sea administrador
        ?>
            <a href="../views/hacerSolicitud.php">HACER SOLICITUD</a>
            <a href="../views/misSolicitudes.php">MIS SOLICITUDES</a>
            <a href="../views/informes.php">INFORMES</a>
            <a class="salir" href="../crud/cerrar_session.php"><input class="btn_sal" type="button" value="SALIR"></a>
        <?php
    }
        ?>
        </nav>
    </div>