<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>
    <title>Hacer solicitud</title>
</head>

<body>
    <?php

    session_start();

    if (!isset($_SESSION['usuario'])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");
    include("../php/SAP.php");

    if (isset($_POST["guardarS"])) {
        $tipo = "servicio";
        $cantidad = 0;
        $j = 0;
        $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
        $num = 1;
        foreach ($ultimo as $ultimoo):
            $num++;
        endforeach;


        while ($j < 1) {
            $cod_arse = $_POST["cod_arse$j"];
            if ($cod_arse == -1) {
            } else {
                $codSol = $num;
                $codArse[$j] = $_POST["cod_arse$j"];
                $code[$j] = $respuestaServicios->value[$codArse[$j]]->Name;
                $fechaNec[$j] = $_POST["fecha_Nec$j"];
                $proveedor[$j] = $_POST["proveedor$j"];
                $precioInfo[$j] = $_POST["precio_inf$j"];
                $cuentaMayor[$j] = $_POST["cuentaMayor$j"];
                $uen[$j] = $_POST["uen$j"];
                $linea[$j] = $_POST["lineas$j"];
                $sublinea[$j] = $_POST["sublineas$j"];
                $proyecto[$j] = $_POST["proyecto$j"];
                $porDesc[$j] = $_POST["por_dec$j"];
                $indImp[$j] = $_POST["ind_imp$j"];
                $total[$j] = $_POST["total_ml$j"];
                $cantidad++;

                if ($cantidad > 0) {
                    $sql = "INSERT INTO list_arse (fk_num_sol,fk_cod_arse,fecha_nec,fk_prov,precio_info,cuenta_mayor,uen,linea,sublinea,proyecto,por_desc,ind_imp,total_ml) 
                        VALUES(:_numSol,:_codArse,:_fechaNec,:_proveedor,:_precioInfo,:_cuentaMayor,:_uen,:_linea,:_sublinea,:_proyecto,:_porDesc,:_indImp,:_total)";

                    $serv = $base->prepare($sql);

                    $serv->execute(array(":_numSol" => $codSol, ":_codArse" => $code[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_precioInfo" => $precioInfo[$j], ":_cuentaMayor" => $cuentaMayor[$j], ":_uen" => $uen[$j], ":_linea" => $linea[$j], ":_sublinea" => $sublinea[$j], ":_proyecto" => $proyecto[$j], ":_porDesc" => $porDesc[$j], ":_indImp" => $indImp[$j], ":_total" => $total[$j]));
                }
            }
            $j++;
        }
        if ($cantidad > 0) {
            $codSol = $num;
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
            header("location:misSolicitudes.php?SolCreada=$num");
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
            <table border="5px" id="tabla__general">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <tr>
                        <td colspan="6">
                            <div id="div__solicitante">

                                <?php
                                include("../php/conexion.php");

                                $usuario = $_SESSION['usuario'];

                                $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($user as $duser):
                                    ?>
                                    <input type="hidden" name="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="solicitante" id="sel__solicitante">
                                        <option value="<?php echo $duser->fk_tipo_usr ?>"><?php echo $duser->fk_tipo_usr ?>
                                        </option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>"
                                        required><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" name="nomSol" value="<?php echo $duser->nom_usr ?>" required><br>
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
                                        $dep = $base->query("SELECT * FROM departamento WHERE pk_dep= '<?php $duser->fk_depart ?>'")->fetchAll(PDO::FETCH_OBJ); foreach ($dep as $depa): ?>
                                            <option value="<?php echo $duser->fk_depart ?>"><?php echo $depa->nom_dep ?>
                                            </option>
                                            <?php
                                        endforeach;
                                        ?>
                                        <?php
                                        $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ); foreach ($departamento as $departamentos): ?>
                                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select><br>
                                    <?php
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
                                $num = 1; foreach ($ultimo as $ultimoo):
                                    $num++;
                                endforeach; ?>
                                <label for="Nsolicitud">N° solicitud de compra:</label>
                                <input type="text" name="numSol" value="<?php echo $num ?>" readonly><br>
                                <label for="Estado">Estado:</label>
                                <input type="text" name="estado" value="ABIERTO" readonly><br>
                                <label for="FechaContabilizacion">Fecha documento:</label>
                                <input type="text" name="fechaDocumento" value="<?php echo date("d-m-y") ?>"
                                    readonly><br>
                                <label for="FechaContabilizacion">Fecha necesaria:</label>
                                <input type="date" name="fechaNecesaria" placeholder="Fecha necesaria"
                                    min="<?= date("Y-m-d") ?>"><br>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12">
                            <div id="div__tablaServicios">
                                <a href=""><input class="btn_sel" type="button" value="servicios"></a>
                                <a href="hacerSolicitudArt.php"><input class="btn_sel" type="button"
                                        value="articulos"></a>
                                <input class="btn-agregar-servicio" type="button" value="+" onclick="insertarFila()">
                                <div class="outer_wrapper">
                                    <div class="table_wrapper">
                                        <!-- tabla servicios  -->
                                        <table id="tabla__servicios">
                                            <thead>
                                                <th></th>
                                                <th width="15px">#</th>
                                                <th>Descripcion servicio</th>
                                                <th>Fecha Necesaria</th>
                                                <th>Proveedor</th>
                                                <th>Precio Info</th>
                                                <th>Cuenta de Mayor</th>
                                                <th>UEN</th>
                                                <th>lineas</th>
                                                <th>sublineas</th>
                                                <th>proyecto</th>
                                                <th>% Descuento</th>
                                                <th>indicador de impuestos</th>
                                                <th>total ml</th>
                                            </thead>
                                            <?php
                                            $i = 0;
                                            ?>
                                            <tbody id="body-servicios">
                                            
                                            </tbody>
                                        </table>
                                        <script>
                                            const datos = <?php echo json_encode($respuestaServicios); ?>
                                            // Justo aquí estamos pasando la variable ----^
                                            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella

                                            const valores = datos.value;
                                            console.log("valores: ", valores);
                                            numeroFila = 0;
                                            // insertarFila();//insertar la fila predeterminada de la tabla
                                            function insertarFila() {
                                                let tblDatos = document.getElementById('tabla__servicios').insertRow();
                                                let col1 = tblDatos.insertCell(0);
                                                let col2 = tblDatos.insertCell(1);
                                                let col3 = tblDatos.insertCell(2);
                                                let col4 = tblDatos.insertCell(3);
                                                let col5 = tblDatos.insertCell(4);
                                                let col6 = tblDatos.insertCell(5);
                                                let col7 = tblDatos.insertCell(6);
                                                let col8 = tblDatos.insertCell(7);
                                                let col9 = tblDatos.insertCell(8);
                                                let col10 = tblDatos.insertCell(9);
                                                let col11 = tblDatos.insertCell(10);
                                                let col12 = tblDatos.insertCell(11);
                                                let col13 = tblDatos.insertCell(12);
                                                let col14 = tblDatos.insertCell(13);

                                                col1.innerHTML = "<input class='btn-eliminar-servicio' type='button' value='x'>";
                                                col2.innerHTML = "<input class='inputTablaNumero' type='text'\n\
                                                                 value='"+ (numeroFila + 1) + "' disabled>";
                                                col3.innerHTML = '<select class="selectServicio" name="cod_arse' + numeroFila +'" id="codigoServicio'+numeroFila+'" required></select>';
                                                const $selectServicio = document.querySelector("#codigoServicio" + numeroFila);
                                                const optionServicio = document.createElement('option');
                                                optionServicio.value = "";
                                                optionServicio.text = "nada";
                                                $selectServicio.appendChild(optionServicio);
                                                $('#codigoServicio' + numeroFila).select2();
                                                const datosServicio = <?php echo json_encode($respuestaServicios); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresServicio = datosServicio.value;
                                                j=0;
                                                while (j < 468) {
                                                            const option = document.createElement('option');
                                                            option.value = j;
                                                            option.text = valoresServicio[j]['Name'];
                                                            $selectServicio.appendChild(option);
                                                            j++;
                                                    }
                                                col4.innerHTML = "<input class='inputTablaFecha' type='text' value='' id='fecha_Nec"+numeroFila+"' name='fecha_Nec"+numeroFila+"'\n\
                                                        min='<?= date("Y-m-d") ?>' required></td>";
                                                col5.innerHTML =  "<select class='selectServicio' name='proveedor" + numeroFila + "'id='proveedor" + numeroFila + "' disabled></select>";
                                                const $selectProveedor = document.querySelector("#proveedor" + numeroFila);
                                                const optionProveedor = document.createElement('option');
                                                optionProveedor.value = "";
                                                optionProveedor.text = "~";
                                                $selectProveedor.appendChild(optionProveedor);
                                                $('#proveedor' + numeroFila).select2();
                                                const datosProveedor = <?php echo json_encode($respuestaProveedor); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresProveedor = datosProveedor.value;
                                                j=0;
                                                while (j < 2890) {
                                                            const option = document.createElement('option');
                                                            option.value = valoresProveedor[j]['CardName'];
                                                            option.text = valoresProveedor[j]['CardName'];
                                                            $selectProveedor.appendChild(option);
                                                            j++;
                                                    }
                                                col6.innerHTML = "<input class='inputTablaCant' type='number'\n\
                                                        id='precio_inf"+numeroFila+"'\n\
                                                        name='precio_inf"+numeroFila+"' disabled>";
                                                col7.innerHTML ="<input class='inputTabla' type='search'\n\
                                                        name='cuentaMayor"+numeroFila+"' id='cuentaMayor"+numeroFila+"' disabled>";
                                                col8.innerHTML = "<input class='inputTabla' type='search' value='' id='uen"+numeroFila+"' '\n\
                                                        name='uen"+numeroFila+"' disabled> ";
                                                col9.innerHTML = "<input class='inputTabla' type='search' value='' id='lineas"+numeroFila+"'\n\
                                                        name='lineas"+numeroFila+"'disabled>";
                                                col10.innerHTML = "<input class='inputTabla' type='search' value='' id='sublineas"+numeroFila+"'\n\
                                                        name='sublineas"+numeroFila+"'disabled>";
                                                col11.innerHTML = "<select class='selectProyecto' name='proyecto" + numeroFila + "'id='proyecto" + numeroFila + "' disabled></select>";
                                                const $selectProyecto = document.querySelector("#proyecto" + numeroFila);
                                                const optionProyecto = document.createElement('option');
                                                optionProyecto.value = "";
                                                optionProyecto.text = "~";
                                                $selectProyecto.appendChild(optionProyecto);
                                                $('#proyecto' + numeroFila).select2();
                                                const datosProyecto = <?php echo json_encode($respuestaProyecto); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresProyecto = datosProyecto.value;
                                                j=0;
                                                while (j < 33) {
                                                            const option = document.createElement('option');
                                                            option.value = valoresProyecto[j]['Code'];
                                                            option.text = valoresProyecto[j]['Name'];
                                                            $selectProyecto.appendChild(option);
                                                            j++;
                                                    }
                                                col12.innerHTML = "<input class='inputTablaCant' type='number'\n\
                                                        id='por_dec"+numeroFila+"' name='por_dec"+numeroFila+"' disabled>";
                                                col13.innerHTML =  "<select class='selectServicio' name='ind_imp" + numeroFila + "'id='ind_imp" + numeroFila + "' disabled></select>";
                                                const $selectIndImp = document.querySelector("#ind_imp" + numeroFila);
                                                const optionIndImp = document.createElement('option');
                                                optionIndImp.value = "";
                                                optionIndImp.text = "~";
                                                $selectIndImp.appendChild(optionIndImp);
                                                $('#proyecto' + numeroFila).select2();
                                                const datosIndImp = <?php echo json_encode($respuestaIndImp); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresIndImp = datosIndImp.value;
                                                j=0;
                                                while (j < 10) {
                                                            const option = document.createElement('option');
                                                            option.value = valoresIndImp[j]['Code'];
                                                            option.text = valoresIndImp[j]['Name'];
                                                            $selectIndImp.appendChild(option);
                                                            j++;
                                                    }
                                                col14.innerHTML = "<input class='inputTabla' type='search'\n\
                                                        id='total_ml"+numeroFila+"' name='total_ml"+numeroFila+"'\n\
                                                        onclick='ftotal()' disabled readonly>";

                                                numeroFila++;
                    
                                            $(document).ready(function () {

                                                for (i = 0; i < numeroFila; i++) {
                                                    $('#codigoServicio' + i).select2();
                                                    $('#proveedor' + i).select2();
                                                    $('#proyecto' + i).select2();
                                                    $('#ind_imp' + i).select2();
                                                    $('#codigoServicio' + i).change(function (e) {


                                                        for (i = 0; i < numeroFila; i++) {
                                                            if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() != "") {
                                                                console.log("fila: ",i);
                                                                console.log("valor servicio: ",$(this).val());
                                                                $('#proyecto' + i).prop("required", true).prop("disabled", false);
                                                                $('#ind_imp' + i).prop("required", true).prop("disabled", false);
                                                                $('#fecha_Nec' + i).prop("required", true).prop("disabled", false);
                                                                $('#proveedor' + i).prop("disabled", false);
                                                                $('#precio_inf' + i).prop("disabled", false);
                                                                $('#cuentaMayor' + i).prop("disabled", false).prop("readonly", true);
                                                                $('#uen' + i).prop("disabled", false).prop("readonly", true);
                                                                $('#lineas' + i).prop("disabled", false).prop("readonly", true);
                                                                $('#sublineas' + i).prop("disabled", false).prop("readonly", true);
                                                                $('#por_dec' + i).prop("disabled", false);
                                                                $('#total_ml' + i).prop("disabled", false);
                                                                document.getElementById('fecha_Nec' + i).type = 'date';
                                                                $('#uen' + i).val(valores[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                                $('#cuentaMayor' + i).val(valores[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                                $('#lineas' + i).val(valores[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                                $('#sublineas' + i).val(valores[$(this).val()]["U_SubLinea"]).prop("readonly", true);
                                                            }
                                                            if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() == "") {
                                                                $('#proyecto' + i).prop("disabled", true).prop("required", false);
                                                                $('#ind_imp' + i).prop("disabled", true).prop("required", false);
                                                                $('#fecha_Nec' + i).prop("disabled", true).prop("required", false);
                                                                $('#proveedor' + i).prop("disabled", true).val(-1);
                                                                $('#proveedor' + i).select2();
                                                                $('#precio_inf' + i).prop("disabled", true);
                                                                $('#cuentaMayor' + i).prop("disabled", true).prop("readonly", true);
                                                                $('#uen' + i).prop("disabled", true);
                                                                $('#lineas' + i).prop("disabled", true);
                                                                $('#sublineas' + i).prop("disabled", true);
                                                                $('#por_dec' + i).prop("disabled", true);
                                                                $('#total_ml' + i).prop("disabled", true);
                                                                document.getElementById('fecha_Nec' + i).type = 'text';
                                                                $('#uen' + i).val("");
                                                                $('#cuentaMayor' + i).val("");
                                                                $('#lineas' + i).val("");
                                                                $('#sublineas' + i).val("");
                                                            }

                                                        }
                                                    })
                                                }
                                            });
                                            }
                                            function ftotal() {
                                                i = 0;
                                                while (i < numeroFila) {
                                                    const desc = document.getElementById('por_dec' + i).value;
                                                    const precio = document.getElementById('precio_inf' + i).value;
                                                    document.getElementById('total_ml' + i).value = precio - (desc * precio / 100);
                                                    i++;
                                                }
                                            }
                                           
                                        </script>
                                        <?php
                                        $i = $i + 1;
                                        ?>
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
                                <a><input class="btn_env" type="submit" value="GUARDAR SOLICITUD" name="guardarS"
                                        onclick="ftotal()"></a>
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
</body>

</html>