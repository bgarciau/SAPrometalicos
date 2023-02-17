<header>
    <div class="wrapper">
        <a href="hacerSolicitud" class="logo"><img src="../images/logo.png" alt="logo" height="90px" width="auto"></a>
        <nav>
            <?php
                include("../php/conexion.php");
                $user=3;
                if($user==3){
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
                <a href="../views/configuracion.php">CONFIGURACION</a>
                <a class="salir" href="../crud/cerrar_session.php"><input class="btn_sal" type="button" value="SALIR"></a>
            <?php 
                    }
                    else{
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
</header>