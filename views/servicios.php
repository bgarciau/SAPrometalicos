<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/hfstyle.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }
    include("../php/conexion.php");

    $usuario=$_SESSION['usuario'];
                
    $registros=$base->query("SELECT * FROM usuario WHERE PK_CODIGO_USUARIO= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach($registros as $Tusuario){
        $userx=$Tusuario->FK_TIPO_USUARIO;
    }
    if($userx!=3){
        header("location:hacerSolicitud.php");    
    }

    $serv = $base->query("SELECT * FROM servicios")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>SERVICIOS</h2>
            <div id="div__tablaSolicitudes">
                <a class="agregarServicio" href="agregarServicio.php"><input class="btn_add" type="button" value="+AGREGAR"></a>
                <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                <div class="outer_wrapperS">
                    <div class="table_wrapperS">
                        <input class="inputBuscar" type="search" name="" value="BUSCAR">
                        <table border="4px" id="tabla__solicitudes">
                            <thead>
                                <th>#</th>
                                <th>Descripcion</th>
                                <th>OPCIONES</th>
                            </thead>
                            <?php
                            $i = 1;
                            foreach ($serv as $servicio):?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $servicio->descripcion_servicio?></td>
                                    <td class="opcionesTabla">
                                        <a href="actualizarServicio.php?idServicio=<?php echo $servicio->id_servicio ?>& descripcionServicio=<?php echo $servicio->descripcion_servicio?> "><input class="btn_update" type="button" value="update"></a>
                                        <a href="../crud/eliminar_servicio.php?idServicio=<?php echo $servicio->id_servicio ?>"><input class="btn_delete" type="button" value="delete"></a>
                                    </td>
                                </tr>
                            <?php
                                $i = $i + 1;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>

</html>