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
            <a class="btn from-center" href="../views/hacerSolicitud.php">HACER SOLICITUD</a>
            <ul>
                <li class="dropdown">
                    <a class="btn from-center" href="../views/misSolicitudes.php">MIS SOLICITUDES</a>
                    <ul>
                        <li><a class="btn from-center" href="../views/solicitudesUsuario.php">SOLICITUDES USUARIOS</a></li>
                    </ul>
                </li>
            </ul>
            <a class="btn from-center" href="../views/informes.php">INFORMES</a>
            <a class="btn from-center" href="../views/usuarios.php">USUARIOS</a>
            <a class="btn from-center" href="../views/servicios.php">SERVICIOS</a>
            <!-- <a class="salir" href="../crud/cerrar_session.php"><input class="btn_sal" type="button" value="SALIR"></a> -->
            <div class="div-boton-salir">
        <div class="svg-wrapper-salir">
                                <svg height="35" width="100" xmlns="http://www.w3.org/2000/svg">
                                    <rect id="shape-salir" height="35" width="100" />
                                    <div id="text-salir">
                                        <a onclick="cerrar_sesion()"><span class="spot-salir"></span>SALIR</a>
                                    </div>
                                </svg>
                            </div>
                            </div>
        <?php
    } else { //otro tipo de usuario que no sea administrador
        ?>
            <a href="../views/hacerSolicitud.php">HACER SOLICITUD</a>
            <a href="../views/misSolicitudes.php">MIS SOLICITUDES</a>
            <a href="../views/informes.php">INFORMES</a>
            <!-- <a class="salir" href="../crud/cerrar_session.php"><input class="btn_sal" type="button" value="SALIR"></a> -->
            <div class="div-boton-salir">
        <div class="svg-wrapper-salir">
                                <svg height="35" width="100" xmlns="http://www.w3.org/2000/svg">
                                    <rect id="shape-salir" height="35" width="100" />
                                    <div id="text-salir">
                                        <a onclick="cerrar_sesion()"><span class="spot-salir"></span>SALIR</a>
                                    </div>
                                </svg>
                            </div>
                            </div>
        <?php
    }
        ?>
        </nav>
    </div>