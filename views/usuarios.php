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
        
        $usuar=$base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
        <h2>USUARIOS</h2>
            <div id="div__tablaSolicitudes">
                <a class="agregarUsuario" href="agregarUsuario.php"><input class="btn_add" type="button" value="+AGREGAR"></a>
                <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                <div class="outer_wrapperS">
                <div class="table_wrapperS">
                    <input class="inputBuscar" type="search" name="" value="BUSCAR">  
                    <table border="4px" id="tabla__solicitudes">
                        <thead>
                            <th>#</th>
                                <th>Nombre usuario</th>
                                <th>Departamento</th>
                                <th>Sucursal</th> 
                                <th>Codigo de usuario</th>
                                <th>OPCIONES</th>
                            </thead>
                            <?php
                                $i=1;
                                foreach($usuar as $usuario):?>
                                    <tr>
                                        <td><?php echo $i?></td>
                                        <td><?php echo $usuario->NOMBRE_USUARIO?></td>
                                        <td><?php echo $usuario->FK_ID_DEPARTAMENTO?></td>
                                        <td><?php echo $usuario->SUCURSAL?></td>
                                        <td><?php echo $usuario->PK_CODIGO_USUARIO?></td>
                                        <td class="opcionesTabla">
                                            <a href="actualizarUsuario.php?codigoUsuario=<?php echo $usuario->PK_CODIGO_USUARIO?>& nombreUsuario=<?php echo $usuario->NOMBRE_USUARIO?>& departamento=<?php echo $usuario->FK_ID_DEPARTAMENTO?>& sucursal=<?php echo $usuario->SUCURSAL?>& password=<?php echo $usuario->PASSWORD?> & tipoUsuario=<?php echo $usuario->FK_TIPO_USUARIO?>"><input class="btn_update" type="button" value="update"></a>
                                            <a href="../crud/eliminar_usuario.php?codigoUsuario=<?php echo $usuario->PK_CODIGO_USUARIO?>"><input class="btn_delete" type="button" value="delete"></a>
                                        </td>
                                    </tr>
                            <?php
                                    $i=$i+1;
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