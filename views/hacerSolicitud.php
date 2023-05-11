<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css"> <!-- se llaman los estilos para el contenido de la pagina -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- se establece el fav de la pagina -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- se usan librerias para usar el select2 que permite agregar un buscador en los select -->
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>

    <!-- ------------------------------------------------------------------------------------------------ -->
    <title>Servicios</title>
</head>

<body>
    <?php

    session_start(); //inica la sesion 
    
    if (!isset($_SESSION['usuario'])) { //si en el inicio de sesion no se ha definido el usuario no deja entrar a este, por lo cual tiene que iniciar sesion
    
        header("location:../index.php");
    }

    include("../php/conexion.php"); //incluye la conexion a la bd
    include("../php/SAP.php"); // incluye la conexion al SAP
    $respuestaServicios = servicios($sesion); //usamos la funcion para llamar los servicios y tomar los valores que se necesitan
    $respuestaProveedor = proveedores($sesion); //usamos la funcion para llamar los prveedores y tomar los valores que se necesitan
    $respuestaIndImp = indImpuestos($sesion); //usamos la funcion para llamar los indicadores de impuestos y tomar los valores que se necesitan
    $respuestaProyecto = proyectos($sesion); //usamos la funcion para llamar los proyectos y tomar los valores que se necesitan
    

    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php'); //se llama el header
            ?>
        </header>
        <div class="contenedor" id="carga" hidden>
            <img id="centrar-carga" src="../images/carga10.gif">
        </div>
        <div class="contenedor" id="principal"> <!-- contenido entre el header y el footer -->
            <table border="1px" id="tabla__general">
                <tr>
                    <td colspan="6"> <!-- tomamos la mitad de laa tabla para los datos del soolicitante-->
                        <div id="div__solicitante"> <!-- div para los datos del solicitante -->

                            <?php

                            $usuario = $_SESSION['usuario']; //usamos el valor guardado en la sesion para cargar los dats del usuario que inicio sesion
                            
                            //tomamos los datos del usuario cuyo codigo es igual al de la sesion
                            $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($user as $duser): //para poder usar los datos
                                ?>
                                <input type="hidden" name="codUsr" id="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                <!-- carga el codigo del usuario pero se esconde prque no es necesario tenerlo a la vista  -->
                                <label>Solicitante:</label>
                                <select name="solicitante" id="sel__solicitante">
                                    <?php
                                    if ($duser->tipo_usuario == 3) {
                                        ?>
                                        <option value="Administrador">Administrador</option>
                                        <?php
                                    } else if ($duser->tipo_usuario == 2) {
                                        ?>
                                            <option value="Empleado">Empleado</option>
                                        <?php
                                    } else {
                                        ?>
                                            <option value="Usuario">Usuario</option>
                                        <?php
                                    }

                                    ?>
                                </select>
                                <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>"
                                    readonly><br>
                                <label for="NombreSolicitante">Nombre Solicitante:</label>
                                <input type="text" name="nomSol" id="nomSol" value="<?php echo $duser->nom_usr ?>"
                                    required><br>
                                <label for="Sucursal">Sucursal:</label>
                                <select class="select_formulario" name="sucursal" id="sucursal">
                                    <!-- <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?>
                                    </option> -->
                                    <option value="Principal">Principal</option>
                                    <!-- <option value="DefinirNuervo">Definir nuevo</option> -->
                                </select><br>
                                <label for="Departamento">Departamento:</label>
                                <select class="select_formulario" name="departamento" id="departamento">
                                    <?php
                                    $dep = $base->query("SELECT * FROM departamento WHERE pk_dep= '<?php $duser->fk_depart ?>'")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($dep as $depa): ?>
                                        <option value="<?php echo $duser->fk_depart ?>"><?php echo $depa->nom_dep ?>
                                        </option>
                                        <?php
                                    endforeach;
                                    ?>
                                    <?php
                                    $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($departamento as $departamentos): ?>
                                        <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select><br>
                                <?php
                            endforeach;
                            ?>
                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" name="correoElectronico" id="correoElectronico"
                                placeholder="correo@correo.com"><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas"><!-- div para las fechas y datos de la solicitud  -->
                            <?php
                            $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                            $num = 1;
                            foreach ($ultimo as $ultimoo):
                                $num++;
                            endforeach; ?>
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text" name="numSol" value="<?php echo $num ?>" readonly><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" name="estado" value="ABIERTO" readonly><br>
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="date" id="fechaDocumento" value="<?php echo date("Y-m-d"); ?>" readonly><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="date" id="fechaNecesaria" placeholder="Fecha necesaria"
                                min="<?= date("Y-m-d") ?>"><br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div_tabla_AS"> <!-- div para la tabla de servicios -->
                            <a><input class="btn_opciones_selected" type="button"
                                    value="servicios"></a><!-- boton para la tabla de servicios  -->
                            <a href="hacerSolicitudArt" onclick="pantallaCarga()"><input class="btn_opciones" type="button"
                                    value="articulos"></a><!-- boton para l tabla de articulos -->

                            <input class="btn-agregar" type="button" value="+" onclick="insertarFila()">
                            <!-- boton para agregar una fila a la tabla con los datos de los servicios -->
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                    <!-- tabla servicios  -->
                                    <table id="tabla__servicios">
                                        <thead>
                                            <th></th>
                                            <th width="30px">#</th>
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
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"> <!-- toma la mitad de la fila -->
                        <div id="div__comentarios"> <!-- div para el propietario y los comentarios -->
                            <label for="Propietario">Propietario:</label>
                            <input type="text" name="propietario" id="propietario" placeholder="Propietario"><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea class="textarea-comentarios" name="comentarios" id="comentarios" rows="4"
                                cols="50" placeholder="comentarios"></textarea>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__enviar"> <!-- div para el boton de guardar solicitud -->
                            <a><input class="btn_guardar" type="button" value="GUARDAR SOLICITUD"
                                    onclick="guardarSolicitud()"></a>
                        </div>
                    </td>

                </tr>
            </table>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php'); //carga el footer
            ?>
        </footer>
    </div>
    <script>
        function pantallaCarga() {
            $('#principal').fadeOut();
            $('#carga').prop("hidden", false);
        }
        const datos = <?php echo json_encode($respuestaServicios); ?>
        // Justo aquí estamos pasando la variable ----^
        // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella

        const valores = datos.value;
        numeroFila = 0;
        insertarFila(); //insertar la fila predeterminada de la tabla
        function insertarFila() { //funcion para insertar fila con los datos de los servicios

            let tblDatos = document.getElementById('tabla__servicios').insertRow(-1); //inserta la fila en la ultima posicion de la tabla
            // Se define cada columna de la fila
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

            // columna 1 se crea un checkbox para confirmar si el usuario desea enviar o no el servicio
            col1.innerHTML = "<input class='checkbox-servicio' type='checkbox' value='si' name='enviar" + numeroFila + "' id='enviar" + numeroFila + "' checked>";
            // clumna 2 se crea un input deshabilitado para mostrar el numero del servicio en la fila
            col2.innerHTML = "<input class='inputTablaNumero' type='text'\n\
                                value='" + (numeroFila + 1) + "' disabled>";
            //columna 3 se crea un select que carga la descripcion de los servicios
            col3.innerHTML = '<select class="select_tabla" name="cod_arse' + numeroFila + '" id="codigoServicio' + numeroFila + '" required></select>';
            const $select_tabla = document.querySelector("#codigoServicio" + numeroFila); //se toma el select que se creo
            const optionServicio = document.createElement('option'); //se crea la opcion por defecto del select
            optionServicio.value = ""; //se deja vacio para poder restringir el envio de la solicitud
            optionServicio.text = "~"; //esto es lo que ve el usuario
            $select_tabla.appendChild(optionServicio); //se agrega la opcion al select
            $('#codigoServicio' + numeroFila).select2(); //se agrega el buscador al select
            //usamos la variable que se definio en php para los servicios
            const datosServicio = <?php echo json_encode($respuestaServicios); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresServicio = datosServicio.value; //usamos solo los valores
            j = 0;
            while (j < 468) { //la cantidad de servicios en el momento
                const option = document.createElement('option'); //por cada servicio se creara una opcion
                option.value = j; //tomara la posicion del servicio para luego poder tomas los demas datos de este
                option.text = valoresServicio[j]['Name']; //se toma el nombre del servicio segun la posicion
                $select_tabla.appendChild(option); //agrega la opcion al select
                j++; //se suma uno para agregar el siguiente servicio
            }
            //columna 4 agregamos un input para la fecha necesaria, con un valor minimo de la fecha actual
            col4.innerHTML = "<input class='inputTablaFecha' type='date' value='' id='fecha_Nec" + numeroFila + "' name='fecha_Nec" + numeroFila + "'\n\
                                                        min='<?= date("Y-m-d") ?>' required></td>";
            //columna 5 agregamos un select para los proveedores
            col5.innerHTML = "<select class='select_tabla' name='proveedor" + numeroFila + "'id='proveedor" + numeroFila + "'></select>";
            const $selectProveedor = document.querySelector("#proveedor" + numeroFila); //tomamos el select que creamos
            const optionProveedor = document.createElement('option'); //creamos la opcion por defecto del select
            optionProveedor.value = ""; //dejamos este valor vacio
            optionProveedor.text = "~"; //esto es lo que ve el usuario
            $selectProveedor.appendChild(optionProveedor); //agregamos la opcion al select de proveedores
            $('#proveedor' + numeroFila).select2(); //cargamos el buscador y el estilo del select2
            //usamos la variable definida en php para cargar las opciones
            const datosProveedor = <?php echo json_encode($respuestaProveedor); ?>
            // Justo aquí estamos pasando la variable ---------^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresProveedor = datosProveedor.value;
            j = 0;
            while (j < 2890) { //proveedores en el momento
                const option = document.createElement('option'); //se crea una opcion por cada proveedor
                option.value = valoresProveedor[j]['CardCode']; //el valor es igual al nombre del proveedor
                option.text = valoresProveedor[j]['CardName']; //el usuario ve el nombre del proveedor
                $selectProveedor.appendChild(option); //agrega la opcion al select
                j++; //se suma uno para agregar el siguiente prooveedor
            }
            //columna 6 agregamos un input para la informacion del precio con un minimo de 0
            col6.innerHTML = "<input class='inputTablaCantidad' type='text' min=0\n\
                                                        id='precio_inf" + numeroFila + "'\n\
                                                        value=0 name='precio_inf" + numeroFila + "'>";
            //columna 7 se crea un input de solo lectura para la cuenta mayor, ya que el valor de este se carga cuando seleccionamos un servicio
            col7.innerHTML = "<input class='inputTabla' type='search'\n\
                                                        name='cuentaMayor" + numeroFila + "' id='cuentaMayor" + numeroFila + "' readonly>";
            //columna 8 se crea un input de solo lectura para el uen, ya que el valor de este se carga cuando seleccionamos un servicio
            col8.innerHTML = "<input class='inputTabla' type='search' value='' id='uen" + numeroFila + "' '\n\
                                                        name='uen" + numeroFila + "' readonly> ";
            //columna 9 se crea un input de solo lectura para la linea, ya que el valor de este se carga cuando seleccionamos un servicio
            col9.innerHTML = "<input class='inputTabla' type='search' value='' id='linea" + numeroFila + "'\n\
                                                        name='lineas" + numeroFila + "'readonly>";
            //columna 10 se crea un input de solo lectura para la sublinea, ya que el valor de este se carga cuando seleccionamos un servicio
            col10.innerHTML = "<input class='inputTabla' type='search' value='' id='sublinea" + numeroFila + "'\n\
                                                        name='sublineas" + numeroFila + "'readonly>";
            //columna 11 crea un select para los prooyectos 
            col11.innerHTML = "<select class='select_tabla' name='proyecto" + numeroFila + "'id='proyecto" + numeroFila + "' readonly></select>";
            const $selectProyecto = document.querySelector("#proyecto" + numeroFila); //tomamos el select que creamos
            const optionProyecto = document.createElement('option'); //crea una opcion
            optionProyecto.value = ""; //valor por defecto
            optionProyecto.text = "~";
            $selectProyecto.appendChild(optionProyecto); //agregamos la opcion al select
            $('#proyecto' + numeroFila).select2(); //cargamos el buscador y el estilo del select2
            //usamos la variable definida en php para cargar los proyectos
            const datosProyecto = <?php echo json_encode($respuestaProyecto); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresProyecto = datosProyecto.value;
            j = 0;
            while (j < 33) { //cantidad de proyectos en el momento
                const option = document.createElement('option'); //creamos una opcion
                option.value = valoresProyecto[j]['Code']; //le asignos el nombre del proyecto como valor
                option.text = valoresProyecto[j]['Name']; //el usuario vera el nombre del proyecto
                $selectProyecto.appendChild(option); //agrega la opcion al select
                j++; //suma uno para agregar el siguiente proyecto
            }
            //columna 12 agrega un input para el porcentaje de descuento
            col12.innerHTML = "<input class='inputTablaCantidad' type='number' min=0\n\
                                                        id='por_dec" + numeroFila + "' name='por_dec" + numeroFila + "' value=0 >";
            //columna 13 crea un select para el indicador de impuestos
            col13.innerHTML = "<select class='select_tabla' name='ind_imp" + numeroFila + "'id='ind_imp" + numeroFila + "' readonly></select>";
            const $selectIndImp = document.querySelector("#ind_imp" + numeroFila); //toma el select que se creo
            const optionIndImp = document.createElement('option'); //crea una opcion
            optionIndImp.value = "";
            optionIndImp.text = "~";
            $selectIndImp.appendChild(optionIndImp); //agrega la opcion al select que creo
            $('#ind_imp' + numeroFila).select2(); //carga el buscador y el estilo del select2
            //usamos la variable definida en php para cargar los indicadores de impuesto
            const datosIndImp = <?php echo json_encode($respuestaIndImp); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresIndImp = datosIndImp.value;
            j = 0;
            while (j < 10) { // numero de indicadores de impuesto en el momento 
                const option = document.createElement('option'); //crea la opcion
                option.value = valoresIndImp[j]['Code'] + "~" + valoresIndImp[j]['Rate']; //le da el nombre del indicador al valor
                option.text = valoresIndImp[j]['Name']; //el usuario ve el nombre del indicador
                $selectIndImp.appendChild(option); //agrega la opcion al select
                j++; //suma uno para agregar el siguiente 
            }
            //columna 14 se crea un input para el total, es de solo lectura porque este se calcula solo
            col14.innerHTML = "<input class='inputTabla' type='search'\n\
                                                        id='total_ml" + numeroFila + "' name='total_ml" + numeroFila + "'\n\
                                                        onclick='ftotal()' readonly>";

            numeroFila++;


            //carga cuando el documento esta listo o luego de agregar una fila para que esta pueda hacer lo siguiente:
            $(document).ready(function () {
                for (i = 0; i < numeroFila; i++) {

                    $('#codigoServicio' + i).change(function (e) { //por cada fila, cuando se seleccione un servicio, este hara cambios en otros campos


                        for (i = 0; i < numeroFila; i++) {
                            //comprueba cual fue la fila que selecciono el servicio para no cambiar las demas, y si su valor no es el vacio
                            if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() != "") {
                                $('#uen' + i).val(valores[$(this).val()]["U_UEN"]).prop("readonly", true); //carga el uen del servicio
                                $('#cuentaMayor' + i).val(valores[$(this).val()]["U_CuentaCosto"]).prop("readonly", true); //carga la cuenta mayor del servicio
                                $('#linea' + i).val(valores[$(this).val()]["U_Linea"]).prop("readonly", true); //carga la linea del servicio
                                $('#sublinea' + i).val(valores[$(this).val()]["U_SubLinea"]).prop("readonly", true); //carga la sublinea del servicio
                            }
                            //comprueba si el valor es vacio, el valor por defecto para vaciar los demas campos
                            if ($(this).val() == document.getElementById('codigoServicio' + i).value && $(this).val() == "") {
                                $('#uen' + i).val(""); //deja el uen vacio
                                $('#cuentaMayor' + i).val(""); //deja la cuenta mayor vacia
                                $('#linea' + i).val(""); //deja la linea vacia
                                $('#sublinea' + i).val(""); //deja la sublinea vacia
                            }

                        }
                    })
                }
            });
        }

        //funcion para cargar el total
        function ftotal() {
            i = 0;
            while (i < numeroFila) { //para cargar el total en cada una de las filas
                const desc = document.getElementById('por_dec' + i).value; //toma el valor del porcenaje de descuento
                const precio = document.getElementById('precio_inf' + i).value; //toma el valor del precio info
                const impuesto = document.getElementById('ind_imp' + i).value; //toma el valor del precio info
                var impuestoPor = impuesto.split('~');
                console.log(impuestoPor[1]);
                totalml = precio - (desc * precio / 100);
                console.log(totalml);
                totalml = totalml + (impuestoPor[1] * totalml / 100)
                console.log(totalml);
                document.getElementById('total_ml' + i).value = totalml; //le asigna al total el precio menos el porcentaje de descuento
                i++;
            }
        }

        //funcion para guardar la solicitud
        function guardarSolicitud() {
            ftotal(); //usa la funcion para cargar los totales de las filas 
            //usamos lo definido en php para los servicios
            const datosServicio = <?php echo json_encode($respuestaServicios); ?>
            // Justo aquí estamos pasando la variable -----------^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresServicio = datosServicio.value;
            //definen arreglos para guardar los datos que se van a obtener de cada fila
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
            // ----------------------------------------------------------------------------------
            for (i = 0; i < numeroFila; i++) { //for para recorrer cada fila
                check = document.getElementById('enviar' + i).checked; //si el chekbox esta selecionado devuelve true 
                if (check == true) { //como esta seleccionado si guarda el servicio
                    console.log("checkbox: SI");
                    codArse[j] = document.getElementById('codigoServicio' + i).value; //toma el valor del codigo del servicio
                    if (codArse[j] == "") { //verifica si se selecciono un servicio
                        //si no se selecciono ningun servicio hace lo siguiente
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar un servicio').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#codigoServicio' + i).focus(); //posiciona la pagina en el campo que falta
                                $('#codigoServicio' + i).select2('open'); //en este caso es un select y la libreria de selec2 nos deja desplegar el select
                            }
                        })
                        cantidad = -100; //ponemos la cantidad en -100 para que no pase los demas condicionales
                        break; //para salir del ciclo que recorre las filas
                        // swal('Error en la fila ' + (i + 1) + ' debe seleccionar un servicio'); //manda un mensaje de alerta con el campo en el que faltan datos
                    }
                    codigoArse[j] = valoresServicio[codArse[j]]['Name']; //cambia el valor del codigo por su nombre
                    fechaNec[j] = document.getElementById('fecha_Nec' + i).value; //toma el valor de la fecha necesaria 
                    if (fechaNec[j] == "") { //si no hay ningun valor hace lo siguiente
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar la fecha necesaria').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                document.getElementById('fecha_Nec' + i).focus(); //posiciona la pagina para que se vea la fecha necesaria
                            }
                        })
                        // alert('Error en la fila ' + (i + 1) + ' debe seleccionar la fecha necesaria'); //muestra un mensaje de alerta
                        cantidad = -100; //ponemos la cantidad en -100 para que no pase los demas condicionales
                        break; //para salir del ciclo que recorre las filas
                    }
                    proveedor[j] = document.getElementById('proveedor' + i).value; //toma el valor del proveedor
                    precioInfo[j] = document.getElementById('precio_inf' + i).value; //toma el precio info
                    cuentaMayor[j] = document.getElementById('cuentaMayor' + i).value; //toma el valor de la cuenta mayor
                    uen[j] = document.getElementById('uen' + i).value; //toma el valor del uen
                    linea[j] = document.getElementById('linea' + i).value; //toma el valor de la linea
                    sublinea[j] = document.getElementById('sublinea' + i).value; //toma el valor de la sublinea
                    proyecto[j] = document.getElementById('proyecto' + i).value; //toma el valor del proyecto
                    if (proyecto[j] == "") { //comprueba si el proyecto esta vacio y hace lo siguiente
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar un proyecto').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#proyecto' + i).focus(); //posiciona la pagina para que se vea el proyectoo
                                $('#proyecto' + i).select2('open'); //abre el select
                            }
                        })
                        // alert('Error en la fila ' + (i + 1) + ' debe seleccionar un proyecto'); //muestra una alerta

                        cantidad = -100; //ponemos la cantidad en -100 para que no pase los demas condicionales
                        break; //para salir del ciclo que recorre las filas
                    }
                    porDesc[j] = document.getElementById('por_dec' + i).value; //toma el valor del porcentaje de descuento
                    indImp[j] = document.getElementById('ind_imp' + i).value; //toma el valor del indicador de impuestos
                    if (indImp[j] == "") { //coomprueba si el indicador de impuestos esta vacio y si lo esta hace lo siguiente
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar el indicador de impuesto').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#ind_imp' + i).focus(); //posiciona la pagina para que se vea el indicador deimpuestos
                                $('#ind_imp' + i).select2('open'); //abre el select
                            }
                        })
                        cantidad = -100; //ponemos la cantidad en -100 para que no pase los demas condicionales
                        break; //para salir del ciclo que recorre las filas
                    }
                    total[j] = document.getElementById('total_ml' + i).value; //toma el valor del total
                    cantidad++; //suma uno a la cantidad para identificar que lleva una fila

                    //muestra los datos en la consola
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
                    j++; //suma uno para seguir con la siguiente fila
                } else { // si el checkbox no esta seleccionado no hace nada con esa fila
                    console.log("checkbox: NO");
                }
            }

            //si todos los datos son correctos hace lo siguiente
            if (cantidad > 0) { //si la cantidad es mayor que cero es porque no tuvo problemas

                //datos de la solicitud
                nomSol = document.getElementById('nomSol').value;
                correoElectronico = document.getElementById('correoElectronico').value;
                propietario = document.getElementById('propietario').value;
                comentarios = document.getElementById('comentarios').value;
                codUsr = document.getElementById('codUsr').value;
                departamento = document.getElementById('departamento').value;
                sucursal = document.getElementById('sucursal').value;
                fechaNecesaria = document.getElementById('fechaNecesaria').value;
                if (fechaNecesaria == 0) { //coomprueba si el indicador de impuestos esta vacio y si lo esta hace lo siguiente
                    //muestra una alerta
                    Swal.fire('Error en la Fecha Necesaria, debe seleccionar la Fecha Necesaria').then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            document.getElementById('fechaNecesaria').focus();
                        }
                    })
                }
                else {
                    fechaDocumento = document.getElementById('fechaDocumento').value;
                    //----------------------------------------------------------------
                    //datos de los servicios, se convierte el array en string y se divide con un _ cada posicion
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
                    //---------------------------------------------------------------

                    console.log("enviar datos");
                    //con ajax vamos a enviar los datos de la solicitud y los servicos a guardarServicio.php
                    Swal.fire({
                        title: 'GUARDAR SOLICITUD',
                        html: '<span class="letra-blanco">¿Esta seguro de que todos los datos son correctos?</span>',
                        textColor: '#ffffff',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ff0000',
                        // cancelButtonColor: '#',
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'CONFIRMAR',
                        cancelButtonText: 'CANCELAR'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                //por la url se pasan todos los datos
                                url: '../crud/guardarServicio.php?codigoArse=' + codigoArse + '&fechaNec=' + fechaNec + '&proveedor=' + proveedor + '&precioInfo=' + precioInfo + '&cuentaMayor=' + cuentaMayor + '&uen=' + uen + '&linea=' + linea + '&sublinea=' + sublinea + '\n\
                                                            &proyecto=' + proyecto + '&porDesc=' + porDesc + '&indImp=' + indImp + '&total=' + total + '&cantidad=' + cantidad + '\n\
                                                            &nomSol=' + nomSol + '&correoElectronico=' + correoElectronico + '&propietario=' + propietario + '&comentarios=' + comentarios + '\n\
                                                            &codUsr=' + codUsr + '&departamento=' + departamento + '&sucursal=' + sucursal + '&fechaNecesaria=' + fechaNecesaria + '&fechaDocumento=' + fechaDocumento,
                                success: function (data) { //cuando guradarServicio.php haya terminado su proceso
                                    // Swal.fire('Su solicitud fue creada:\n"' + data + '"') //se muestra la alerta con el numero de solicitud que se creo
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Su soliciud fue creada:',
                                        html: '<span class="letra-blanco">numero de soliciud: ' + data + '</span>'
                                    }).then((result) => {
                                        /* Read more about isConfirmed, isDenied below */
                                        if (result.isConfirmed) {
                                            window.location = "misSolicitudes.php"; //manda al usuario a misSolicitudes y ahi puede ver su solicitud
                                        }
                                    })
                                    // window.location = "misSolicitudes.php"; //manda al usuario a misSolicitudes y ahi puede ver su solicitud
                                }
                            })
                        }
                    })
                }
            }
        }
    </script>
</body>

</html>