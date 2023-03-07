<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Hacer solicitud</title>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");
    include("../php/SAP.php");

    if (isset($_POST["guardarA"])) {
        $tipo = "articulo";
        $cantidad = 0;
        $j = 0;
        while ($j < 20) {
            $cod_arse = $_POST["cod_arse$j"];
            if ($cod_arse == -1) {
            } else {
                $cantidad++;
                $codSol = $_POST["numSol"];
                $codArse[$j] = $_POST["cod_arse$j"];
                $code[$j] = $respuestaArticulos->value[$codArse[$j]]->ItemCode;
                $descripcion[$j] = $_POST["descripcion$j"];
                $desc[$j] = $respuestaArticulos->value[$codArse[$j]]->ItemName;
                $fechaNec[$j] = $_POST["fecha_nec$j"];
                $proveedor[$j] = $_POST["proveedor$j"];
                $cant_nec[$j] = $_POST["cant_nec$j"];
                $precioInfo[$j] = $_POST["precio_inf$j"];
                $porDesc[$j] = $_POST["por_desc$j"];
                $indImp[$j] = $_POST["ind_imp$j"];
                $total[$j] = $_POST["total$j"];
                $uen[$j] = $_POST["uen$j"];
                $xlinea[$j] = $_POST["lineas$j"];
                $sublinea[$j] = $_POST["sublinea$j"];

                if ($cant_nec[$j] <= 0) {
                    echo '<script>alert("Error al enviar su solicitud, verifique los campos obligatorios\nLos unicos que no son obligatorios con el proveedor y el precio info");</script>';
                    $cantidad = -50;
                    while ($j < 20) {
                        if ($cod_arse == -1) {
                        } else {
                            $codArse[$j] = $_POST["cod_arse$j"];
                            $descripcion[$j] = $_POST["descripcion$j"];
                            $fechaNec[$j] = $_POST["fecha_nec$j"];
                            $proveedor[$j] = $_POST["proveedor$j"];
                            $cant_nec[$j] = $_POST["cant_nec$j"];
                            $precioInfo[$j] = $_POST["precio_inf$j"];
                            $porDesc[$j] = $_POST["por_desc$j"];
                            $indImp[$j] = $_POST["ind_imp$j"];
                            $total[$j] = $_POST["total$j"];
                            $uen[$j] = $_POST["uen$j"];
                            $xlinea[$j] = $_POST["lineas$j"];
                            $sublinea[$j] = $_POST["sublinea$j"];
                        }
                        $j++;
                    }
                } else {
                    $sql = "INSERT INTO list_arse (fk_num_sol,fk_cod_arse,nom_arse,fecha_nec,fk_prov,cant_nec,precio_info,por_desc,ind_imp,total_ml,uen,linea,sublinea) 
                VALUES(:_numSol,:_codArse,:_descripcion,:_fechaNec,:_proveedor,:_cant_nec,:_precioInfo,:_por_desc,:_ind_imp,:_total_ml,:_uen,:_linea,:_sublinea)";

                    $serv = $base->prepare($sql);

                    $serv->execute(array(":_numSol" => $codSol, ":_codArse" => $code[$j], ":_descripcion" => $desc[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_cant_nec" => $cant_nec[$j], ":_precioInfo" => $precioInfo[$j], ":_por_desc" => $porDesc[$j], ":_ind_imp" => $indImp[$j], ":_total_ml" => $total[$j], ":_uen" => $uen[$j], ":_linea" => $xlinea[$j], ":_sublinea" => $sublinea[$j]));
                }
            }
            $j++;
        }
        if ($cantidad > 0) {
            $codSol = $_POST["numSol"];
            $estado = $_POST["estado"];
            $nomSol = $_POST["nomSol"];
            $correoElectronico = $_POST["correoElectronico"];
            $propietario = $_POST["propietario"];
            $comentarios = $_POST["comentarios"];
            $codUsr = $_POST["codUsr"];
            $departamento = $_POST["departamento"];
            $sucursal = $_POST["sucursal"];


            $sql = "INSERT INTO solicitud_compra (pk_num_sol,estado_sol,nom_solicitante,sucursal,correo_sol,propietario,comentarios,fk_cod_usr,depart_sol,tipo,cantidad) 
                VALUES(:_codSol,:_estado,:_nomSol,:_sucursal,:_correoElectronico,:_propietario,:_comentarios,:_codUsr,:_departamento,:_tipo,:_cantidad)";

            $solicitud = $base->prepare($sql);

            $solicitud->execute(array(":_codSol" => $codSol, ":_estado" => $estado, ":_nomSol" => $nomSol, ":_sucursal" => $sucursal, ":_correoElectronico" => $correoElectronico, ":_propietario" => $propietario, ":_comentarios" => $comentarios, ":_codUsr" => $codUsr, ":_departamento" => $departamento, ":_tipo" => $tipo, ":_cantidad" => $cantidad));
            header("location:misSolicitudes.php?xtabla=tarticulos");
        }
    }

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <table border="1px" id="tabla__general">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <tr>
                        <td colspan="6">
                            <div id="div__solicitante">
                                <?php
                                include("../php/conexion.php");

                                $usuario = $_SESSION['usuario'];

                                $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($user as $duser) :
                                    $tuser = $base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$duser->fk_tipo_usr'")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($tuser as $tipo) : ?>
                                        <input type="hidden" name="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                        <label for="Solicitante">Solicitante:</label>
                                        <select name="solicitante" id="sel__solicitante">
                                            <option value="<?php echo $tipo->des_usr ?>"><?php echo $tipo->des_usr ?></option>
                                            <option value="Usuario">Usuario</option>
                                            <option value="Empleado">Empleado</option>
                                        </select>
                                        <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>"><br>
                                        <label for="NombreSolicitante">Nombre Solicitante:</label>
                                        <input type="text" name="nomSol" value="<?php echo $duser->nom_usr ?>"><br>
                                        <label for="Sucursal">Sucursal:</label>
                                        <select name="sucursal" id="datosFormu">
                                            <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?>
                                            </option>
                                            <option value="Principal">Principal</option>
                                            <option value="DefinirNuervo">Definir nuevo</option>
                                        </select><br>
                                        <label for="Departamento">Departamento:</label>
                                        <select name="departamento" id="datosFormu">
                                            <?php
                                            $dep = $base->query("SELECT * FROM departamento WHERE pk_dep= '<?php $duser->fk_depart ?>'")->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($dep as $depa) : ?>
                                                <option value="<?php echo $duser->fk_depart ?>"><?php echo $depa->nom_dep ?>
                                                </option>
                                            <?php
                                            endforeach;
                                            ?>
                                            <?php
                                            $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($departamento as $departamentos) : ?>
                                                <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select><br>
                                <?php
                                    endforeach;
                                endforeach;
                                ?>
                                <input type="checkbox" value="enviarCorreo" name="enviarCorreo">
                                <label id="enviarCorreo" for="EnviarCorreo">Enviar Correo Electronico si se agrego
                                    pedido</label><br>
                                <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                                <input type="text" name="correoElectronico" placeholder="correo@correo.com"><br>
                            </div>
                        </td>
                        <td colspan="6">
                            <div id="div__fechas">
                                <?php
                                $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                                $num = 1;
                                foreach ($ultimo as $ultimoo) :
                                    $num++;
                                endforeach; ?>
                                <label for="Nsolicitud">N° solicitud de compra:</label>
                                <input type="text" name="numSol" value="<?php echo $num ?>" readonly><br>
                                <label for="Estado">Estado:</label>
                                <input type="text" name="estado" value="ABIERTO" readonly><br>

                                <label for="FechaContabilizacion">Fecha documento:</label>
                                <input type="text" name="fechaDocumento" placeholder="Fecha documento"><br>
                                <label for="FechaContabilizacion">Fecha necesaria:</label>
                                <input type="text" name="fechaNecesaria" placeholder="Fecha necesaria"><br>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12">
                            <div id="div__tablaServicios">
                                <a href="hacerSolicitud.php"><input class="btn_sel" type="button" value="servicios"></a>
                                <a href=""><input class="btn_sel" type="button" value="articulos"></a>
                                <div class="outer_wrapper">
                                    <div class="table_wrapper">
                                        <!-- tabla articulos  -->
                                        <table id="tabla__articulos">
                                            <thead>
                                                <th>#</th>
                                                <th>codigo</th>
                                                <th>Descripcion</th>
                                                <th>Proveedor</th>
                                                <th>Fecha Necesaria</th>
                                                <th>Canidad Necesaria</th>
                                                <th>Precio Info</th>
                                                <th>% Descuento</th>
                                                <th>indicador de impuestos</th>
                                                <th>total ml</th>
                                                <th>UEN</th>
                                                <th>lineas</th>
                                                <th>sublineas</th>
                                            </thead>
                                            <?php

                                            $i = 0;
                                            while ($i < 20) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td><select class="selectArticulo" name="cod_arse<?php echo $i ?>" id="codigoArticulo<?php echo $i ?>">
                                                            <option value="<?php if (isset($codArse[$i])) {
                                                                                echo $codArse[$i];
                                                                            } else {
                                                                                echo (-1);
                                                                            } ?>" selected><?php if (isset($codArse[$i]) and $codArse[$i] != -1) {
                                                                                                print_r($respuestaArticulos->value[$codArse[$i]]->ItemCode);
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?></option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaArticulos->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $s ?>"><?php echo "$item->ItemCode" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><select name="descripcion<?php echo $i ?>" id="descripcion<?php echo $i ?>">
                                                            <option value="<?php if (isset($descripcion[$i])) {
                                                                                echo $descripcion[$i];
                                                                            } else {
                                                                                echo (-1);
                                                                            } ?>" selected><?php if (isset($descripcion[$i]) and $descripcion[$i] != -1) {
                                                                                                print_r($respuestaArticulos->value[$descripcion[$i]]->ItemName);
                                                                                            } else {
                                                                                                echo "";
                                                                                            } ?></option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaArticulos->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $s ?>"><?php echo "$item->ItemName" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select></td>
                                                    <td><input class="inputTabla" type="search" name="proveedor<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="date" value="<?php if (isset($fechaNec[$i])) {
                                                                                                            echo $fechaNec[$i];
                                                                                                        } else {
                                                                                                            echo date("Y-m-d");
                                                                                                        } ?>" name="fecha_nec<?php echo $i ?>"></td>
                                                    <td><input class="inputTabla" type="number" value="<?php if (isset($cant_nec[$i])) {
                                                                                                            echo $cant_nec[$i];
                                                                                                        } else {
                                                                                                            echo 0;
                                                                                                        } ?>" name="cant_nec<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="precio_inf<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="number" value=0 name="por_desc<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="ind_imp<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="number" value=0 name="total<?php echo $i ?>"></td>
                                                    <td><input class="inputTabla" type="search" id="uen<?php echo $i ?>" name="uen<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if (isset($linea[$i])) {
                                                                                                            echo $linea[$i];
                                                                                                        } else {
                                                                                                            echo "";
                                                                                                        } ?>" name="lineas<?php echo $i ?>"></td>
                                                    <td><input class="inputTabla" type="search" name="sublinea<?php echo $i ?>" value=""></td>
                                                </tr>
                                            <?php
                                                $i = $i + 1;
                                            }

                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div id="div__comentarios">
                                <label for="Propietario">Propietario:</label>
                                <input type="text" name="propietario" placeholder="Propietario"><br>
                                <label for="Comentarios">Comentarios:</label>
                                <textarea name="comentarios" rows="4" cols="50" placeholder="comentarios"></textarea>
                            </div>
                        </td>
                        <td colspan="6">
                            <div id="div__enviar">


                                <a><input class="btn_env" type="submit" value="GUARDAR SOLICITUD" name="guardarA"></a>
                                <!-- <button class="btn_env">ENVIAR SOLICITUD</button> -->

                            </div>
                        </td>

                    </tr>
                </form>
            </table>
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
    <script>
        const datos = <?php echo json_encode($respuestaArticulos); ?>
        // Justo aquí estamos pasando la variable ----^
        // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
        console.log("Los datos son: ", datos);

        const valores = datos.value;

        function ftotal() {
            i = 0;
            while (i < 20) {
                const desc = document.getElementById('por_dec' + i).value;
                const precio = document.getElementById('precio_inf' + i).value;
                document.getElementById('total_ml' + i).value = precio - (desc * precio / 100);
                i++;
            }
        }

        $(document).ready(function() {

            for (i = 0; i < 20; i++) {
                $('#codigoArticulo' + i).select2();
                $('#descripcion' + i).select2();
                $('#proveedor' + i).select2();
                $('#proyecto' + i).select2();
                $('#codigoArticulo' + i).change(function(e) {


                    for (i = 0; i < 20; i++) {
                        if ($(this).val() == document.getElementById('codigoArticulo' + i).value && $(this).val() != -1) {
                            // $('#proyecto' + i).prop("required", true).prop("disabled", false);
                            // $('#ind_imp' + i).prop("required", true).prop("disabled", false);
                            // $('#fecha_Nec' + i).prop("required", true).prop("disabled", false);
                            // $('#proveedor' + i).prop("disabled", false);
                            // $('#precio_inf' + i).prop("disabled", false);
                            // $('#cuentaMayor' + i).prop("disabled", false).prop("readonly", true);
                            // $('#uen' + i).prop("disabled", false).prop("readonly", true);
                            // $('#lineas' + i).prop("disabled", false).prop("readonly", true);
                            // $('#sublineas' + i).prop("disabled", false).prop("readonly", true);
                            // $('#por_dec' + i).prop("disabled", false);
                            // $('#total_ml' + i).prop("disabled", false);
                            // document.getElementById('fecha_Nec' + i).type = 'date';
                            $('#descripcion' + i).val(valores[$(this).val()]["ItemName"]).prop("readonly", true);
                            // $('#cuentaMayor' + i).val(valores[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                            // $('#lineas' + i).val(valores[$(this).val()]["U_Linea"]).prop("readonly", true);
                            // $('#sublineas' + i).val(valores[$(this).val()]["U_SubLinea"]).prop("readonly", true);
                        }
                        else if ($(this).val() == document.getElementById('descripcion' + i).value && $(this).val() != -1) {
                            // $('#proyecto' + i).prop("required", true).prop("disabled", false);
                            // $('#ind_imp' + i).prop("required", true).prop("disabled", false);
                            // $('#fecha_Nec' + i).prop("required", true).prop("disabled", false);
                            // $('#proveedor' + i).prop("disabled", false);
                            // $('#precio_inf' + i).prop("disabled", false);
                            // $('#cuentaMayor' + i).prop("disabled", false).prop("readonly", true);
                            // $('#uen' + i).prop("disabled", false).prop("readonly", true);
                            // $('#lineas' + i).prop("disabled", false).prop("readonly", true);
                            // $('#sublineas' + i).prop("disabled", false).prop("readonly", true);
                            // $('#por_dec' + i).prop("disabled", false);
                            // $('#total_ml' + i).prop("disabled", false);
                            // document.getElementById('fecha_Nec' + i).type = 'date';
                            // $('#uen' + i).val(valores[$(this).val()]["U_UEN"]).prop("readonly", true);
                            // $('#cuentaMayor' + i).val(valores[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                            // $('#lineas' + i).val(valores[$(this).val()]["U_Linea"]).prop("readonly", true);
                            // $('#sublineas' + i).val(valores[$(this).val()]["U_SubLinea"]).prop("readonly", true);
                        }

                        if ($(this).val() == -1) {
                            // $('#proyecto' + i).prop("disabled", true).prop("required", false);
                            // $('#ind_imp' + i).prop("disabled", true).prop("required", false);
                            // $('#fecha_Nec' + i).prop("disabled", true).prop("required", false);
                            // $('#proveedor' + i).prop("disabled", true);
                            // $('#precio_inf' + i).prop("disabled", true);
                            // $('#cuentaMayor' + i).prop("disabled", true).prop("readonly", true);
                            // $('#uen' + i).prop("disabled", true);
                            // $('#lineas' + i).prop("disabled", true);
                            // $('#sublineas' + i).prop("disabled", true);
                            // $('#por_dec' + i).prop("disabled", true);
                            // $('#total_ml' + i).prop("disabled", true);
                            // document.getElementById('fecha_Nec' + i).type = 'text';
                            // $('#uen' + i).val("");
                            // $('#cuentaMayor' + i).val("");
                            // $('#lineas' + i).val("");
                            // $('#sublineas' + i).val("");
                        }

                    }
                })
            }

        });
    </script>
</body>

</html>