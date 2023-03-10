<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>
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
            $codArt = $_POST["codArt$j"];
            if ($codArt == -1) {
            } else {
                $cantidad++;
                $codSol = $_POST["numSol"];
                $codArt[$j] = $_POST["codArt$j"];
                $code[$j] = $respuestaArticulos->value[$codArt[$j]]->ItemCode;
                $desc[$j] = $respuestaArticulos->value[$codArt[$j]]->ItemName;
                $fechaNec[$j] = $_POST["fecha_Nec$j"];
                $proveedor[$j] = $_POST["proveedor$j"];
                $cant_nec[$j] = $_POST["cant_nec$j"];
                $precioInfo[$j] = $_POST["precio_inf$j"];
                $porDesc[$j] = $_POST["por_desc$j"];
                $indImp[$j] = $_POST["ind_imp$j"];
                $total[$j] = $_POST["total$j"];
                $uen[$j] = $_POST["uen$j"];
                $xlinea[$j] = $_POST["lineas$j"];
                $sublinea[$j] = $_POST["sublinea$j"];


                if ($cantidad > 0) {
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
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php');
            ?>
        </header>
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
                                <input type="text" name="fechaDocumento" value="<?php echo date("d-m-y") ?>" readonly><br>
                                <label for="FechaContabilizacion">Fecha necesaria:</label>
                                <input type="date" name="fechaNecesaria" placeholder="Fecha necesaria"><br>
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
                                                <th>codigo Articulo</th>
                                                <th>Descripcion Articulo</th>
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
                                                        <?php echo $i + 1 ?>
                                                    </td>
                                                    <td><select class="selectServicio" name="codArt<?php echo $i ?>" id="codigoArticulo<?php echo $i ?>" hidden>
                                                            <option value="<?php echo (-1) ?>" selected>~</option>
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
                                                        <input name="codArtt<?php echo $i ?>" id="codArtt<?php echo $i ?>" hidden>
                                                    </td>
                                                    <td><select class="selectServicio" name="descripcion<?php echo $i ?>" id="descripcion<?php echo $i ?>" hidden>
                                                            <option value="<?php echo (-1) ?>" selected>~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaArticulos->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $s ?>"><?php echo "$item->ItemName" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                        <input name="cdes<?php echo $i ?>" id="cdes<?php echo $i ?>" hidden>
                                                    </td>
                                                    <td><select class="selectServicio" name="proveedor<?php echo $i ?>" id="proveedor<?php echo $i ?>" disabled>
                                                            <option value=-1 selected>~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaProveedor->value as $item) :

                                                            ?>
                                                                <option value="<?php echo "$item->CardName" . PHP_EOL; ?>"><?php echo "$item->CardName" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>

                                                        </select></td>
                                                    <td><input class="inputTablaFecha" type="text" value="<?php if (isset($fechaNec[$i])) {
                                                                                                                echo $fechaNec[$i];
                                                                                                            }
                                                                                                            ?>" id="fecha_Nec<?php echo $i ?>" name="fecha_Nec<?php echo $i ?>" disabled></td>
                                                    <td><input class="inputTabla" type="number" value="<?php if (isset($cant_nec[$i])) {
                                                                                                            echo $cant_nec[$i];
                                                                                                        } else {
                                                                                                        } ?>" id="cant_nec<?php echo $i ?>" name="cant_nec<?php echo $i ?>" disabled></td>
                                                    <td><input class="inputTabla" type="search" id="precio_inf<?php echo $i ?>" name="precio_inf<?php echo $i ?>" value="" disabled></td>
                                                    <td><input class="inputTabla" type="number" value=0 id="por_desc<?php echo $i ?>" name="por_desc<?php echo $i ?>" disabled></td>
                                                    <td><select class="selectServicio" name="ind_imp<?php echo $i ?>" id="ind_imp<?php echo $i ?>" disabled>
                                                            <option value="-1">~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaIndImp->value as $item) :

                                                            ?>
                                                                <option value="<?php echo "$item->Code" . PHP_EOL; ?>"><?php echo "$item->Code | $item->Name " . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select></td>
                                                    <td><input class="inputTabla" type="search" id="total<?php echo $i ?>" name="total<?php echo $i ?>" onclick="ftotal()" disabled readonly></td>
                                                    <td><select class="selectServicio" name="uen<?php echo $i ?>" id="uen<?php echo $i ?>" disabled>
                                                            <option value="<?php echo (-1) ?>" selected>~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaUen->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $item->FactorCode ?>"><?php echo "$item->FactorCode | $item->FactorDescription" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select></td>
                                                    <td><select class="selectServicio" name="lineas<?php echo $i ?>" id="linea<?php echo $i ?>" disabled>
                                                            <option value="<?php echo (-1) ?>" selected>~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaLinea->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $item->FactorCode ?>"><?php echo "$item->FactorCode | $item->FactorDescription" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select></td>
                                                    <td><select class="selectServicio" name="sublinea<?php echo $i ?>" id="sublinea<?php echo $i ?>" disabled>
                                                            <option value="<?php echo (-1) ?>" selected>~</option>
                                                            <?php
                                                            $s = 0;
                                                            foreach ($respuestaSubLinea->value as $item) :

                                                            ?>
                                                                <option value="<?php echo $item->FactorCode ?>"><?php echo "$item->FactorCode | $item->FactorDescription" . PHP_EOL; ?></option>
                                                            <?php
                                                                $s++;
                                                            endforeach;
                                                            ?>
                                                        </select></td>
                                                </tr>
                                            <?php
                                                $i++;
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
                            </div>
                        </td>

                    </tr>
                </form>
            </table>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php');
            ?>
        </footer>
    </div>
    <script>
        for (i = 0; i < 20; i++) {
            $('#codigoArticulo' + i).select2();
            $('#descripcion' + i).select2();
            $('#proveedor' + i).select2();
            $('#proyecto' + i).select2();
        }
        const datos = <?php echo json_encode($respuestaArticulos); ?>
        // Justo aquí estamos pasando la variable ----^
        // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
        console.log("Los datos son: ", datos);

        const valores = datos.value;

        function ftotal() {
            i = 0;
            while (i < 20) {
                const desc = document.getElementById('por_desc' + i).value;
                const precio = document.getElementById('precio_inf' + i).value;
                document.getElementById('total' + i).value = precio - (desc * precio / 100);
                i++;
            }
        }

        $(document).ready(function() {

            for (i = 0; i < 20; i++) {

                $('#codigoArticulo' + i).change(function(e) {

                    for (i = 0; i < 20; i++) {

                        if ($(this).val() == document.getElementById('codigoArticulo' + i).value && $(this).val() != -1) {
                            // $('#descripcion' + i).select2();
                            // $('#descripcion' + i).select2('destroy');
                            // $('#cdes' + i).prop("hidden", false).prop("readonly", true).prop("class", "inputTablaArt");
                            // $('#cdes' + i).val(valores[$(this).val()]["ItemName"]).prop("readonly", true).prop("title", valores[$(this).val()]["ItemName"]);
                            $('#descripcion' + i).val($(this).val());
                            $('#descripcion' + i).select2();
                            $('#proveedor' + i).prop("disabled", false);
                            $('#fecha_Nec' + i).prop("required", true).prop("disabled", false);
                            document.getElementById('fecha_Nec' + i).type = 'date';
                            $('#cant_nec' + i).prop("disabled", false).prop("required", true);
                            $('#precio_inf' + i).prop("disabled", false);
                            $('#por_desc' + i).prop("disabled", false).prop("required", true);
                            $('#ind_imp' + i).prop("disabled", false).prop("required", true);
                            $('#total' + i).prop("disabled", false).prop("required", true);
                            $('#uen' + i).prop("disabled", false).prop("required", true);

                        }

                        if ($(this).val() == document.getElementById('codigoArticulo' + i).value && $(this).val() == -1) {
                            // $('#cdes' + i).prop("hidden", true);
                            // $('#descripcion' + i).select2();
                            // $('#cdes' + i).val(-1);
                            // $('#cdes' + i).prop("hidden", true).prop("class", null);
                            $('#descripcion' + i).val($(this).val());
                            $('#descripcion' + i).select2();
                            $('#proveedor' + i).prop("disabled", true).val(-1);
                            $('#proveedor' + i).select2();
                            $('#fecha_Nec' + i).prop("disabled", true).prop("required", false).val(0);
                            document.getElementById('fecha_Nec' + i).type = 'text';
                            $('#cant_nec' + i).prop("disabled", true).prop("required", false).val(0);
                            $('#precio_inf' + i).prop("disabled", true).val(0);
                            $('#por_desc' + i).prop("disabled", true).prop("required", false).val(0);
                            $('#ind_imp' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#total' + i).prop("disabled", true).prop("required", false);
                            $('#uen' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#linea' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#linea' + i).select2();
                            $('#sublinea' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#sublinea' + i).select2();
                        }
                    }
                })

                $('#descripcion' + i).change(function(e) {

                    for (i = 0; i < 20; i++) {

                        if ($(this).val() == document.getElementById('descripcion' + i).value && $(this).val() != -1) {
                            // $('#codigoArticulo' + i).select2();
                            // $('#codigoArticulo' + i).select2('destroy');
                            // // $('#codArtt' + i).prop("hidden", false).prop("readonly", true).prop("class", "inputTablaArt");
                            // // $('#codArtt' + i).val(valores[$(this).val()]["ItemCode"]).prop("readonly", true);
                            $('#codigoArticulo' + i).val($(this).val());
                            $('#codigoArticulo' + i).select2();
                            $('#proveedor' + i).prop("disabled", false);
                            $('#fecha_Nec' + i).prop("required", true).prop("disabled", false);
                            document.getElementById('fecha_Nec' + i).type = 'date';
                            $('#cant_nec' + i).prop("disabled", false).prop("required", true);
                            $('#precio_inf' + i).prop("disabled", false);
                            $('#por_desc' + i).prop("disabled", false).prop("required", true);
                            $('#ind_imp' + i).prop("disabled", false).prop("required", true);
                            $('#total' + i).prop("disabled", false).prop("required", true);
                            $('#uen' + i).prop("disabled", false).prop("required", true);

                        }
                        if ($(this).val() == document.getElementById('descripcion' + i).value && $(this).val() == -1) {
                            // $('#codArtt' + i).prop("hidden", true);
                            // $('#codigoArticulo' + i).select2();
                            // $('#codArtt' + i).val(-1);
                            // $('#codArtt' + i).prop("hidden", true).prop("class", null);
                            $('#codigoArticulo' + i).val($(this).val());
                            $('#codigoArticulo' + i).select2();
                            $('#proveedor' + i).prop("disabled", true).val(-1);
                            $('#proveedor' + i).select2();
                            $('#fecha_Nec' + i).prop("disabled", true).prop("required", false).val(0);
                            document.getElementById('fecha_Nec' + i).type = 'text';
                            $('#cant_nec' + i).prop("disabled", true).prop("required", false).val(0);
                            $('#precio_inf' + i).prop("disabled", true).val(0);
                            $('#por_desc' + i).prop("disabled", true).prop("required", false).val(0);
                            $('#ind_imp' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#total' + i).prop("disabled", true).prop("required", false);
                            $('#uen' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#linea' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#linea' + i).select2();
                            $('#sublinea' + i).prop("disabled", true).prop("required", false).val(-1);
                            $('#sublinea' + i).select2();
                        }
                    }
                })

                $('#uen' + i).change(function(e) {
                    for (i = 0; i < 20; i++) {
                        if ($(this).val() == document.getElementById('uen' + i).value && $(this).val() != -1) {
                            $('#linea' + i).prop("disabled", false).val(-1);
                            $('#linea' + i).select2();
                            $('#sublinea' + i).val(-1);
                            $('#sublinea' + i).select2();
                        } else {
                            $('#linea' + i).prop("disabled", true).val(-1);
                            $('#linea' + i).select2();
                            $('#sublinea' + i).prop("disabled", true).val(-1);
                            $('#sublinea' + i).select2();
                        }
                    }
                })

                $('#linea' + i).change(function(e) {
                    for (i = 0; i < 20; i++) {
                        if ($(this).val() == document.getElementById('linea' + i).value && $(this).val() != -1) {
                            $('#sublinea' + i).prop("disabled", false).val(-1);
                            $('#sublinea' + i).select2();
                        } else {
                            $('#sublinea' + i).prop("disabled", true).val(-1);
                            $('#sublinea' + i).select2();
                        }
                    }
                })

            }
        });
    </script>
</body>

</html>