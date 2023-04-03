<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css"> <!-- se llaman los estilos para el contenido de la pagina -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../images/fav.png"/>  <!-- se establece el fav de la pagina -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- se usan librerias para usar el select2 que permite agregar un buscador en los select -->
    <link href="../css/select2/select2.min.css" rel="stylesheet" /> 
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>
    <!-- ------------------------------------------------------------------------------------------------ -->
    <title>Hacer solicitud</title>
</head>

<body>
    <?php

    session_start();  //inica la sesion 

    if (!isset($_SESSION['usuario'])) { //si en el inicio de sesion no se ha definido el usuario no deja entrar a este, por lo cual tiene que iniciar sesion

        header("location:../index.php");
    }

    include("../php/conexion.php"); //incluye la conexion a la bd
    include("../php/SAP.php"); // incluye la conexion al SAP
    $respuestaServicios=servicios($sesion);//usamos la funcion para llamar los servicios y tomar los valores que se necesitan
    $respuestaProveedor=proveedores($sesion);//usamos la funcion para llamar los prveedores y tomar los valores que se necesitan
    $respuestaIndImp=indImpuestos($sesion);//usamos la funcion para llamar los indicadores de impuestos y tomar los valores que se necesitan
    $respuestaProyecto=proyectos($sesion);//usamos la funcion para llamar los proyectos y tomar los valores que se necesitan


    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php'); //se llama el header
            ?>
        </header>
        <div class="contenedor"> <!-- contenido entre el header y el footer -->
            <table border="5px" id="tabla__general">
                    <tr>
                        <td colspan="6"> <!-- tomamos la mitad de laa tabla para los datos del soolicitante-->
                            <div id="div__solicitante"> <!-- div para los datos del solicitante -->

                                <?php

                                $usuario = $_SESSION['usuario']; //usamos el valor guardado en la sesion para cargar los dats del usuario que inicio sesion

                                //tomamos los datos del usuario cuyo codigo es igual al de la sesion
                                $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); foreach ($user as $duser)://para poder usar los datos
                                    ?>
                                    
                                    <input type="hidden" name="codUsr" id="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="solicitante" id="sel__solicitante">
                                        <option value="<?php echo $duser->tipo_usuario ?>"><?php echo $duser->tipo_usuario ?>
                                        </option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>"
                                        required><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" name="nomSol" id="nomSol" value="<?php echo $duser->nom_usr ?>" required><br>
                                    <label for="Sucursal">Sucursal:</label>
                                    <select class="select_formulario" name="sucursal" id="sucursal" >
                                        <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?>
                                        </option>
                                        <option value="Principal">Principal</option>
                                        <option value="DefinirNuervo">Definir nuevo</option>
                                    </select><br>
                                    <label for="Departamento">Departamento:</label>
                                    <select class="select_formulario" name="departamento" id="departamento">
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
                                <input type="text" name="correoElectronico" id="correoElectronico" placeholder="correo@correo.com"><br>
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
                                <input class="btn_opciones_selected" type="button" value="servicios">
                                <a href="hacerSolicitudArt.php"><input class="btn_opciones" type="button" value="articulos"></a>
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
                                            insertarFila(); //insertar la fila predeterminada de la tabla
                                            function insertarFila() {

                                                let tblDatos = document.getElementById('tabla__servicios').insertRow(-1);
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

                                                col1.innerHTML = "<input class='checkbox-servicio' type='checkbox' value='si' name='enviar" + numeroFila + "' id='enviar" + numeroFila + "' checked>";
                                                col2.innerHTML = "<input class='inputTablaNumero' type='text'\n\
                                                                 value='" + (numeroFila + 1) + "' disabled>";
                                                col3.innerHTML = '<select class="select_tabla" name="cod_arse' + numeroFila + '" id="codigoServicio' + numeroFila + '" required></select>';
                                                const $select_tabla = document.querySelector("#codigoServicio" + numeroFila);
                                                const optionServicio = document.createElement('option');
                                                optionServicio.value = "";
                                                optionServicio.text = "~";
                                                $select_tabla.appendChild(optionServicio);
                                                $('#codigoServicio' + numeroFila).select2();
                                                const datosServicio = <?php echo json_encode($respuestaServicios); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresServicio = datosServicio.value;
                                                j = 0;
                                                while (j < 468) {
                                                    const option = document.createElement('option');
                                                    option.value = j;
                                                    option.text = valoresServicio[j]['Name'];
                                                    $select_tabla.appendChild(option);
                                                    j++;
                                                }
                                                col4.innerHTML = "<input class='inputTablaFecha' type='date' value='' id='fecha_Nec" + numeroFila + "' name='fecha_Nec" + numeroFila + "'\n\
                                                        min='<?= date("Y-m-d") ?>' required></td>";
                                                col5.innerHTML = "<select class='select_tabla' name='proveedor" + numeroFila + "'id='proveedor" + numeroFila + "'></select>";
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
                                                j = 0;
                                                while (j < 2890) {
                                                    const option = document.createElement('option');
                                                    option.value = valoresProveedor[j]['CardName'];
                                                    option.text = valoresProveedor[j]['CardName'];
                                                    $selectProveedor.appendChild(option);
                                                    j++;
                                                }
                                                col6.innerHTML = "<input class='inputTablaCantidad' type='number' min=0\n\
                                                        id='precio_inf" + numeroFila + "'\n\
                                                        value=0 name='precio_inf" + numeroFila + "'>";
                                                col7.innerHTML = "<input class='inputTabla' type='search'\n\
                                                        name='cuentaMayor" + numeroFila + "' id='cuentaMayor" + numeroFila + "' readonly>";
                                                col8.innerHTML = "<input class='inputTabla' type='search' value='' id='uen" + numeroFila + "' '\n\
                                                        name='uen" + numeroFila + "' readonly> ";
                                                col9.innerHTML = "<input class='inputTabla' type='search' value='' id='linea" + numeroFila + "'\n\
                                                        name='lineas" + numeroFila + "'readonly>";
                                                col10.innerHTML = "<input class='inputTabla' type='search' value='' id='sublinea" + numeroFila + "'\n\
                                                        name='sublineas" + numeroFila + "'readonly>";
                                                col11.innerHTML = "<select class='select_tabla' name='proyecto" + numeroFila + "'id='proyecto" + numeroFila + "' readonly></select>";
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
                                                j = 0;
                                                while (j < 33) {
                                                    const option = document.createElement('option');
                                                    option.value = valoresProyecto[j]['Name'];
                                                    option.text = valoresProyecto[j]['Name'];
                                                    $selectProyecto.appendChild(option);
                                                    j++;
                                                }
                                                col12.innerHTML = "<input class='inputTablaCantidad' type='number' min=0\n\
                                                        id='por_dec" + numeroFila + "' name='por_dec" + numeroFila + "' value=0 >";
                                                col13.innerHTML = "<select class='select_tabla' name='ind_imp" + numeroFila + "'id='ind_imp" + numeroFila + "' readonly></select>";
                                                const $selectIndImp = document.querySelector("#ind_imp" + numeroFila);
                                                const optionIndImp = document.createElement('option');
                                                optionIndImp.value = "";
                                                optionIndImp.text = "~";
                                                $selectIndImp.appendChild(optionIndImp);
                                                $('#ind_imp' + numeroFila).select2();
                                                const datosIndImp = <?php echo json_encode($respuestaIndImp); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresIndImp = datosIndImp.value;
                                                j = 0;
                                                while (j < 10) {
                                                    const option = document.createElement('option');
                                                    option.value = valoresIndImp[j]['Name'];
                                                    option.text = valoresIndImp[j]['Name'];
                                                    $selectIndImp.appendChild(option);
                                                    j++;
                                                }
                                                col14.innerHTML = "<input class='inputTabla' type='search'\n\
                                                        id='total_ml" + numeroFila + "' name='total_ml" + numeroFila + "'\n\
                                                        onclick='ftotal()' readonly>";

                                                numeroFila++;



                                                $(document).ready(function () {

                                                    for (i = 0; i < numeroFila; i++) {

                                                        $('#codigoServicio' + i).change(function (e) {


                                                            for (i = 0; i < numeroFila; i++) {
                                                                if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() != "") {
                                                                    $('#uen' + i).val(valores[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                                    $('#cuentaMayor' + i).val(valores[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                                    $('#linea' + i).val(valores[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                                    $('#sublinea' + i).val(valores[$(this).val()]["U_SubLinea"]).prop("readonly", true);
                                                                }
                                                                if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() == "") {
                                                                    $('#uen' + i).val("");
                                                                    $('#cuentaMayor' + i).val("");
                                                                    $('#linea' + i).val("");
                                                                    $('#sublinea' + i).val("");
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

                                            function guardarSolicitud() {
                                                ftotal();
                                                <?php
                                                $tipo = "servicio";
                                                $numSolicitud = 1;
                                                $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ); foreach ($ultimo as $ultimoo):
                                                    $numSolicitud++;
                                                endforeach;
                                                ?>
                                                const datosServicio = <?php echo json_encode($respuestaServicios); ?>
                                                // Justo aquí estamos pasando la variable ----^
                                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                const valoresServicio = datosServicio.value;
                                                console.log("numero de solicitud", <?php echo $numSolicitud ?>)
                                                j = 0;
                                                cantidad = 0;
                                                codArse = [];
                                                codigoArse = [];
                                                fechaNec = [];
                                                proveedor = [];
                                                precioInfo = [];
                                                cuentaMayor = [];
                                                uen = [];
                                                linea = [];
                                                sublinea = [];
                                                proyecto = [];
                                                porDesc = [];
                                                indImp = [];
                                                total = [];
                                                for (i = 0; i < numeroFila; i++) {
                                                    check = document.getElementById('enviar' + i).checked;
                                                    if (check == true) {
                                                        console.log("checkbox: SI");
                                                        codArse[j] = document.getElementById('codigoServicio' + i).value;
                                                        if (codArse[j] == "") {
                                                            alert('Error en la fila ' + (i + 1) + ' debe seleccionar un servicio');
                                                            $('#codigoServicio' + i).focus();
                                                            $('#codigoServicio' + i).select2('open');
                                                            cantidad = -100;
                                                            break;
                                                        }
                                                        codigoArse[j] = valoresServicio[codArse[j]]['Name'];
                                                        fechaNec[j] = document.getElementById('fecha_Nec' + i).value;
                                                        if (fechaNec[j] == "") {
                                                            alert('Error en la fila ' + (i + 1) + ' debe seleccionar la fecha necesaria');
                                                            document.getElementById('fecha_Nec' + i).focus();
                                                            cantidad = -100;
                                                            break;
                                                        }
                                                        proveedor[j] = document.getElementById('proveedor' + i).value;
                                                        precioInfo[j] = document.getElementById('precio_inf' + i).value;
                                                        cuentaMayor[j] = document.getElementById('cuentaMayor' + i).value;
                                                        uen[j] = document.getElementById('uen' + i).value;
                                                        linea[j] = document.getElementById('linea' + i).value;
                                                        sublinea[j] = document.getElementById('sublinea' + i).value;
                                                        proyecto[j] = document.getElementById('proyecto' + i).value;
                                                        if (proyecto[j] == "") {
                                                            alert('Error en la fila ' + (i + 1) + ' debe seleccionar un proyecto');
                                                            $('#proyecto' + i).focus();
                                                            $('#proyecto' + i).select2('open');
                                                            cantidad = -100;
                                                            break;
                                                        }
                                                        porDesc[j] = document.getElementById('por_dec' + i).value;
                                                        indImp[j] = document.getElementById('ind_imp' + i).value;
                                                        if (indImp[j] == "") {
                                                            alert('Error en la fila ' + (i + 1) + ' debe seleccionar el indicador de impuesto');
                                                            $('#ind_imp' + i).focus();
                                                            $('#ind_imp' + i).select2('open');
                                                            cantidad = -100;
                                                            break;
                                                        }
                                                        total[j] = document.getElementById('total_ml' + i).value;
                                                        cantidad++;

                                                        console.log("fila: ", j);
                                                        console.log("codigoArse: ", codigoArse[j]);
                                                        console.log("fechaNec: ", fechaNec[j]);
                                                        console.log("proveedor: ", proveedor[j]);
                                                        console.log("precio info: ", precioInfo[j]);
                                                        console.log("cuenta mayor: ", cuentaMayor[j]);
                                                        console.log("uen: ", uen[j]);
                                                        console.log("linea: ", linea[j]);
                                                        console.log("sublinea: ", sublinea[j]);
                                                        console.log("proyecto: ", proyecto[j]);
                                                        console.log("porcentaje descuento   : ", porDesc[j]);
                                                        console.log("indicador de impuesto: ", indImp[j]);
                                                        console.log("total ml: ", total[j]);
                                                        console.log("cantidad: ", cantidad);
                                                        j++;
                                                    } else {
                                                        console.log("checkbox: NO");
                                                    }
                                                }

                                                if (cantidad > 0) {

                                                    nomSol = document.getElementById('nomSol').value;
                                                    correoElectronico = document.getElementById('correoElectronico').value;
                                                    propietario = document.getElementById('propietario').value;
                                                    comentarios = document.getElementById('comentarios').value;
                                                    codUsr = document.getElementById('codUsr').value;
                                                    departamento = document.getElementById('departamento').value;
                                                    sucursal = document.getElementById('sucursal').value;
                                                    codigoArse = codigoArse.join('_').toString();
                                                    fechaNec = fechaNec.join('_').toString();
                                                    proveedor = proveedor.join('_').toString();
                                                    precioInfo = precioInfo.join('_').toString();
                                                    cuentaMayor = cuentaMayor.join('_').toString();
                                                    uen = uen.join('_').toString();
                                                    linea = linea.join('_').toString();
                                                    sublinea = sublinea.join('_').toString();
                                                    proyecto = proyecto.join('_').toString();
                                                    porDesc = porDesc.join('_').toString();
                                                    indImp = indImp.join('_').toString();
                                                    total = total.join('_').toString();

                                                    console.log("enviar datos");
                                                    $.ajax(
                                                        {
                                                            url: 'guardarServicio.php?codigoArse=' + codigoArse + '&fechaNec=' + fechaNec + '&proveedor=' + proveedor + '\n\
                                                            &precioInfo='+ precioInfo + '&cuentaMayor=' + cuentaMayor + '&uen=' + uen + '&linea=' + linea + '&sublinea=' + sublinea + '\n\
                                                            &proyecto='+ proyecto + '&porDesc=' + porDesc + '&indImp=' + indImp + '&total=' + total + '&cantidad=' + cantidad +'\n\
                                                            &nomSol=' + nomSol+ '&correoElectronico=' + correoElectronico+ '&propietario=' + propietario+ '&comentarios=' + comentarios+ '&codUsr=' + codUsr+ '&departamento=' + departamento + '&sucursal=' + sucursal ,
                                                            success: function (data) {
                                                                alert('Su solicitud fue creada:\n"' + data + '"')
                                                                window.location="misSolicitudes.php";

                                                                // $('#guardarS').click();
                                                            }
                                                        }
                                                    )
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div id="div__comentarios">
                                <label for="Propietario">Propietario:</label>
                                <input type="text" name="propietario" id="propietario" placeholder="Propietario"><br>
                                <label for="Comentarios">Comentarios:</label>
                                <textarea name="comentarios" id="comentarios" rows="4" cols="50" placeholder="comentarios"></textarea>
                            </div>
                        </td>
                        <td colspan="6">
                            <div id="div__enviar">
                                <a><input class="btn_guardar" type="button" value="GUARDAR SOLICITUD"
                                        onclick="guardarSolicitud()"></a>
                                <input class="btn_guardar" type="submit" value="GUARDAR SOLICITUD" name="guardarS"
                                    onclick="ftotal()" id="guardarS" hidden>
                            </div>
                        </td>

                    </tr>
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