<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('head.php')
        ?>
</head>

<body>
    <?php
    session_start(); //inica la sesion 
    
    if (!isset($_SESSION["usuario"])) { //si en el inicio de sesion no se ha definido el usuario no deja entrar a este, por lo cual tiene que iniciar sesion
    
        header("location:../index.php");
    }
    require('header.php');
    include("../php/conexion.php"); //incluye la conexion a la bd
    include("../php/SAP.php"); //se usa para hacer los get al sap y obtener los datos que necesita la aplicacion
    $respuestaArticulos = articulos($sesion); //usamos la funcion para llamar los articulos y tomar los valores que se necesitan
    $respuestaProveedor = proveedores($sesion); //usamos la funcion para llamar los proveedores y tomar los valores que se necesitan
    $respuestaIndImp = indImpuestos($sesion); //usamos la funcion para llamar los indicadores de impuestos y tomar los valores que se necesitan
    $respuestaUen = uen($sesion); //usamos la funcion para llamar los uen y tomar los valores que se necesitan
    $respuestaLinea = linea($sesion); //usamos la funcion para llamar las lineas y tomar los valores que se necesitan
    $respuestaSubLinea = sublinea($sesion); //usamos la funcion para llamar las sublineas y tomar los valores que se necesitan
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>HACER SOLICITUD ARTICULOS</h3>
        </div>
        <?php
        $usuario = $_SESSION['usuario']; //usamos el valor guardado en la sesion para cargar los dats del usuario que inicio sesion
        //tomamos los datos del usuario cuyo codigo es igual al de la sesion
        $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
        foreach ($user as $duser) { //para poder usar los datos
            ?>
            <div class="row">
                <div class="col bloques py-2">
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="solicitante">SOLICITANTE:</label>
                                <input type="hidden" id="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                <select class="form-select" aria-label="Default select example" id="sel__solicitante">
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
                            </div>
                        </div>
                        <div class="col">
                            <div class="col form-group">
                                <label for="tipo">ROL SOLICITANTE:</label>
                                <input type="text" class="form-control" id="Solicitante"
                                    value="<?php echo $duser->rol_usr ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="nombres">NOMBRE SOLICITANTE:</label>
                                <input type="text" class="form-control" id="nomSol" value="<?php echo $duser->nom_usr ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="sucursal">SUCURSAL:</label>
                                <input type="text" class="form-control" id="sucursal" value="PRINCIPAL">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="departamento">DEPARTAMENTO:</label>
                                <select class="form-select" aria-label="Default select example" id="departamento">
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
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="correo">DIRECCION CORREO ELECTRONICO:</label>
                                <input type="email" class="form-control" id="correoElectronico">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col bloques py-2">
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <?php
                                $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                                $num = 1;
                                foreach ($ultimo as $ultimoo):
                                    $num++;
                                endforeach; ?>
                                <label for="sucursal">N° SOLICITUD DE COMPRA:</label>
                                <input type="text" class="form-control" value="<?php echo $num ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="sucursal">ESTADO:</label>
                                <input type="text" class="form-control" value="ABIERTO" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="sucursal">FECHA DOCUMENTO:</label>
                                <input type="date" class="form-control" id="fechaDocumento"
                                    value="<?php echo date("Y-m-d"); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="col form-group">
                                <label for="sucursal">FECHA NECESARIA:</label>
                                <input type="date" class="form-control" id="fechaNecesaria" min="<?= date("Y-m-d") ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col bloques py-2">
                    <div class="py-2">
                        <a href="hacerSolicitud" class="btn btn-danger" onclick="pantallaCarga()">SERVICIOS</a>
                        <button type="button" class="btn btn-danger">ARTICULOS</button>
                        <button type="button" class="btn btn-success" onclick="insertarFila()"><i class="bi bi-plus-circle">
                                AGREGAR</i></button>
                    </div>
                    <div class="overflow-x-scroll">
                        <table class="table table-bordered table-striped table-hover" id="tablaArticulos">
                            <thead class="table-dark">
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>codigo Articulo</th>
                                    <th>Descripcion Articulo</th>
                                    <th>Proveedor</th>
                                    <th>Fecha Necesaria</th>
                                    <th>Cantidad Necesaria</th>
                                    <th>Precio Info</th>
                                    <th>% Descuento</th>
                                    <th>indicador de impuestos</th>
                                    <th>total ml</th>
                                    <th>UEN</th>
                                    <th>lineas</th>
                                    <th>sublineas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tabla__articulos">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col bloques py-2">
                    <div class="mb-3">
                        <label class="form-label">PROPIETARIO</label>
                        <input type="email" class="form-control" id="propietario">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">COMENTARIOS</label>
                        <textarea class="form-control" id="comentarios" rows="3"></textarea>
                    </div>
                </div>
                <div class="col text-center bloques py-2">
                    <button type="button" class="btn btn-success" onclick="guardarSolicitud()"><i class="bi bi-save">
                            GUARDAR SOLICITUD </i></button>
                            <br><br>
                            <a href="javascript:history.back()" class="btn btn-danger" onclick="pantallaCarga()"><i class="bi bi-arrow-bar-left">VOLVER
                            </i></a>
                </div>
            </div>
            <?php
        }
        ?>
        <br>
    </div>
    <?php
    require('footer.php')
        ?>
</body>
<script>
    function pantallaCarga() {
        $('#principal').fadeOut();
        $('#carga').prop("hidden", false);
    }
    numeroFila = 0;
    insertarFila(); //insertar la fila predeterminada de la tabla

    function insertarFila() { //funcion para insertar fila con los datos de los servicios

        let tblDatos = document.getElementById('tabla__articulos').insertRow(-1);//inserta la fila en la ultima posicion de la tabla
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
        let col15 = tblDatos.insertCell(14);

        // columna 1 se crea un checkbox para confirmar si el usuario desea enviar o no el articulo
        col1.innerHTML = "<input class='checkbox-servicio' type='checkbox' value='si' name='enviar" + numeroFila + "' id='enviar" + numeroFila + "' checked>";
        // clumna 2 se crea un input deshabilitado para mostrar el numero del articulo en la fila
        col2.innerHTML = "<input id='numeroF" + numeroFila + "' value='" + (numeroFila + 1) + "' style='width:25px;' disabled>";
        //columna 3 se crea un select que carga el codigo de los articulos
        col3.innerHTML = '<select class="select_tabla" name="codArt' + numeroFila + '" id="codigoArticulo' + numeroFila + '" required></select>';
        const $selectArticulo = document.querySelector("#codigoArticulo" + numeroFila);//toma el select que se creo
        const optionArticulo = document.createElement('option');//crea una opcion
        optionArticulo.value = "";//define el valor de la opcion vacio
        optionArticulo.text = "~";//lo que se ve en la opcion
        $selectArticulo.appendChild(optionArticulo);//agrega la opcion al select
        $('#codigoArticulo' + numeroFila).select2();//carga el buscador y el estilo del select2
        //usa la variable definida en php
        const datosArticulo = <?php echo json_encode($respuestaArticulos); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresArticulos = datosArticulo.value;
        j = 0;
        while (j < 7355) {//cantidad de articulos en el momento
            const option = document.createElement('option'); //crea una opcion
            option.value = j; //le define la posicion como valor para pder usar todos sus datos
            option.text = valoresArticulos[j]['ItemCode']; //muestra el codigo del articulo
            $selectArticulo.appendChild(option);//agrega la opcion al select
            j++;
        }
        //columna 4 se crea un select que carga la descripcion de los articulos
        col4.innerHTML = '<select class="select_tabla" name="descripcion' + numeroFila + '" id="descripcion' + numeroFila + '" required></select>';
        const $selectArticuloDes = document.querySelector("#descripcion" + numeroFila);//toma el select que se creo
        const optionArticuloDes = document.createElement('option');//crea una opcion
        optionArticuloDes.value = "";//valor por defecto
        optionArticuloDes.text = "~";//el usuario ve esto
        $selectArticuloDes.appendChild(optionArticuloDes);//agrega la oopcion al select
        $('#descripcion' + numeroFila).select2();//carga el buscador y el estilo del select2
        j = 0;
        while (j < 7355) {//cantidad de articulos en el momento
            const option = document.createElement('option');//crea una opcion
            option.value = j;//le define la posicion como valor para pder usar todos sus datos
            option.text = valoresArticulos[j]['ItemName'];//muestra el nombre del articulo
            $selectArticuloDes.appendChild(option);//agrega la opcion al select
            j++;
        }
        //columna 5 agregamos un select para los proveedores
        col5.innerHTML = "<select class='select_tabla' name='proveedor" + numeroFila + "'id='proveedor" + numeroFila + "'></select>";
        const $selectProveedor = document.querySelector("#proveedor" + numeroFila);//toma el select que se creo
        const optionProveedor = document.createElement('option');//crea una opcion
        optionProveedor.value = "";//valor por defecto
        optionProveedor.text = "~";//el usuario ve esto
        $selectProveedor.appendChild(optionProveedor);//agrega la oopcion al select
        $('#proveedor' + numeroFila).select2();//carga el buscador y el estilo del select2
        //usa la variable definida en php
        const datosProveedor = <?php echo json_encode($respuestaProveedor); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresProveedor = datosProveedor.value;
        j = 0;
        while (j < 2890) {//cantidad de proveedores en el momento
            const option = document.createElement('option');//crea una opcion
            option.value = valoresProveedor[j]['CardCode'];//define el valor con el nombre del proveedor
            option.text = valoresProveedor[j]['CardName'];//define el texto con el nombre del proveedor
            $selectProveedor.appendChild(option);//agrega la opcion al select
            j++;
        }
        //columna 6 agrega un input para la fecha necesaria, con un valor minimo de la fecha actual
        col6.innerHTML = "<input class='inputTablaFecha' type='date' value='' id='fecha_Nec" + numeroFila + "' name='fecha_Nec" + numeroFila + "'\n\
                            min='<?= date("Y-m-d") ?>' required></td>";
        //columna 7 agrega un input para la cantidad necesaria
        col7.innerHTML = "<input class='inputTablaCantidad'  type='number' min=1\n\
                            id='cant_nec" + numeroFila + "' name='cant_nec" + numeroFila + "' >";
        //columna 8 agregamos un input para la informacion del precio con un minimo de 0
        col8.innerHTML = "<input class='inputTablaCantidad' type='number' min=0\n\
                                                        id='precio_inf" + numeroFila + "'\n\
                                                        value=0 name='precio_inf" + numeroFila + "'>";
        //columna 9 agrega un input para el porcentaje de descuento
        col9.innerHTML = "<input class='inputTablaCantidad' type='number' min=0 \n\
                            id='por_desc" + numeroFila + "' name='por_desc" + numeroFila + "' value=0 >";
        //columna 10 crea un select para el indicador de impuestos
        col10.innerHTML = "<select class='select_tabla' name='ind_imp" + numeroFila + "'id='ind_imp" + numeroFila + "'></select>";
        const $selectIndImp = document.querySelector("#ind_imp" + numeroFila);//se toma el select que se creo
        const optionIndImp = document.createElement('option');//se crea una opcion
        optionIndImp.value = "";//valor por defecto
        optionIndImp.text = "~";//texto por defectp
        $selectIndImp.appendChild(optionIndImp);//agrega la opcion
        $('#ind_imp' + numeroFila).select2();//carga el buscador y el estilo del select2
        //usa la variable definida en php
        const datosIndImp = <?php echo json_encode($respuestaIndImp); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresIndImp = datosIndImp.value;
        j = 0;
        while (j < 10) { //cantidad de indicadores en el momento
            const option = document.createElement('option');//crea una opcion
            option.value = valoresIndImp[j]['Code'] + "~" + valoresIndImp[j]['Rate'];//define el valor con el nombre del indicadr de impuesto
            option.text = valoresIndImp[j]['Name'];//define el texto con el nombre del indicadr de impuesto
            $selectIndImp.appendChild(option);//agrega la opcion al select
            j++;
        }
        //columna 11 se crea un input para el total, es de solo lectura porque este se calcula solo
        col11.innerHTML = "<input class='inputTabla' type='search'\n\
        id='total_ml" + numeroFila + "' name='total_ml" + numeroFila + "'\n\
        onclick='ftotal()' readonly>";
        //columna 12 se crea un select que carga los uen
        col12.innerHTML = "<select class='select_tabla' name='uen" + numeroFila + "'id='uen" + numeroFila + "'></select>";
        const $selectUen = document.querySelector("#uen" + numeroFila);
        const optionUen = document.createElement('option');
        optionUen.value = "";
        optionUen.text = "~";
        $selectUen.appendChild(optionUen);
        $('#uen' + numeroFila).select2();
        const datosUen = <?php echo json_encode($respuestaUen); ?>
            // Justo aquí estamos pasando la variable ----^
            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
            const valoresUen = datosUen.value;
        j = 0;
        while (j < 80) {
            const option = document.createElement('option');
            option.value = valoresUen[j]['FactorCode'];
            option.text = valoresUen[j]['FactorCode'] + " | " + valoresUen[j]['FactorDescription'];
            $selectUen.appendChild(option);
            j++;
        }
        //columna 13 se crea un select que carga las lineas luego de seleccionar el uen
        col13.innerHTML = "<select class='select_tabla' name='linea" + numeroFila + "'\n\
                                                                id='linea"+ numeroFila + "' disabled></select>";
        //columna 14 se crea un select que carga las sublineas luego de seleccionar la linea
        col14.innerHTML = "<select class='select_tabla' name='sublinea" + numeroFila + "'\n\
                                                                id='sublinea"+ numeroFila + "' disabled></select>";

        col15.innerHTML = "<td><input type='button' class='borrar' style='background-color:red; color:white' value='x' /></td>";
        numeroFila++;

        //carga cuando el documento esta listo o luego de agregar una fila para que esta pueda hacer lo siguiente:
        $(document).ready(function () {
            numero = 1;
            for (i = 0; i < numeroFila; i++) {
                if (document.getElementById('numeroF' + i)) {
                    console.log("si numero");
                    //si se selecciono un codigo de articulo
                    $('#numeroF' + i).val(numero);
                    numero++;
                }
                else {
                    console.log("no");
                }
            }
            for (i = 0; i < numeroFila; i++) {

                $('#codigoArticulo' + i).change(function (e) { //por cada fila, cuando se seleccione un codigo de articulo, este hara cambios en otros campos

                    for (i = 0; i < numeroFila; i++) {
                        if (document.getElementById('codigoArticulo' + i)) {
                            console.log("si");
                            //si se selecciono un codigo de articulo
                            if ($(this).val() == document.getElementById('codigoArticulo' + i).value && $(this).val() != "") {
                                $('#descripcion' + i).val($(this).val()); //se carga su descripicion
                                $('#descripcion' + i).select2();
                            }
                            //si se elige la opcion por defecto
                            if ($(this).val() == document.getElementById('codigoArticulo' + i).value && $(this).val() == "") {
                                $('#descripcion' + i).val($(this).val());//carga la opcion por defecto de la descripcion
                                $('#descripcion' + i).select2();
                            }
                        }
                        else {
                            console.log("no");
                        }
                    }
                })


                $('#descripcion' + i).change(function (e) { //por cada fila, cuando se seleccione una descripcion del articulo, este hara cambios en otros campos

                    for (i = 0; i < numeroFila; i++) {
                        if (document.getElementById('descripcion' + i)) {
                            console.log("si");
                            //si se selecciona una descripcion
                            if ($(this).val() == document.getElementById('descripcion' + i).value && $(this).val() != "") {
                                $('#codigoArticulo' + i).val($(this).val());//carga el codigo del aticulo
                                $('#codigoArticulo' + i).select2();
                            }
                            //si se selecciona la opcion por defecto
                            if ($(this).val() == document.getElementById('descripcion' + i).value && $(this).val() == "") {
                                $('#codigoArticulo' + i).val($(this).val());//se carga el valor por defecto
                                $('#codigoArticulo' + i).select2();
                            }
                        }
                    }
                })

                $('#uen' + i).change(function (e) { //por cada fila, cuando se seleccione un uen, este hara cambios en otros campos
                    for (i = 0; i < numeroFila; i++) {
                        if (document.getElementById('uen' + i)) {
                            console.log("si");
                            //al selecionar un uen
                            if ($(this).val() == document.getElementById('uen' + i).value && $(this).val() != "") {
                                $('#linea' + i).prop("disabled", false).prop("required", false);//activa la columna de la linea
                                $('#linea' + i).select2();//carga el buscaodr y el estilo del select2
                                const datos = <?php echo json_encode($respuestaLinea); ?>
                                // Justo aquí estamos pasando la variable ----^
                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                const valores = datos.value;

                                const $select = document.querySelector("#linea" + i);//se toma el selector de la linea de la misma fila que el uen

                                const opcionCambiada = () => {
                                    console.log("cambio");
                                };

                                $select.addEventListener("change", opcionCambiada)
                                for (let k = $select.options.length; k >= 0; k--) {//elimina todo lo que tenga el select
                                    $select.remove(k);
                                }

                                //se toma la sublinea
                                const $select2 = document.querySelector("#sublinea" + i);

                                const opcionCambiada2 = () => {
                                    console.log("cambio");
                                };

                                //se borran los valores de la sublinea
                                $select2.addEventListener("change", opcionCambiada2)
                                for (let k = $select2.options.length; k >= 0; k--) {
                                    $select2.remove(k);
                                }

                                const option = document.createElement('option');//crea una opcion
                                option.value = "";//valor por defeto
                                option.text = "~";//texto que ve ek suaurio
                                $select.appendChild(option);//se agrega la opcion al select
                                $('#uen' + i).select2();//carga el buscador y lo estilos del select2
                                j = 0;
                                while (j >= 0 && j < 360) {//se bucan datos segun el uen para aplicar el filtro segun el codigo
                                    x = valores[j]['FactorCode'] * 10 ** (-1);//deja los primeros 3 dijitos del codigo de la linea 
                                    x = Math.floor(x);//redondea el valor de x por abajo 
                                    //compara el valr de la linea con el del uen
                                    if (x == $(this).val()) {// si se encuentra uno igual
                                        $select.remove(0);//quita los valores por defecto
                                        const option = document.createElement('option');//crea una opcion
                                        option.value = "NO";//crea una opcion en cero para que se pueda comprobar si se eligio o no
                                        option.text = "Seleccione";//dice seleccione cuando si carga algo con el codigo del selec
                                        $select.appendChild(option);//agrega la opcion al select
                                        //preguna si el valor de x es igual al del uen mientras se encuentren iguales
                                        while (x == ($(this).val())) {
                                            const option = document.createElement('option');//crea una opcion
                                            option.value = valores[j]['FactorCode'];//le asigna como valor el codigo de la linea
                                            option.text = valores[j]['FactorCode'] + " | " + valores[j]['FactorDescription'];//le asigna como texto el codigo y la descripcion de la linea
                                            $select.appendChild(option);//agrega la opcion en el select de la linea
                                            j++;
                                            //se toman los 3 primeros numeros de la siguiente linea
                                            x = valores[j]['FactorCode'] * 10 ** (-1);
                                            x = Math.floor(x);
                                        }
                                        j = -100; //sale del ciclo luego de encontrar las lineas
                                    }
                                    j++;
                                }
                                //si se cambia el uen por el valor por defecto
                            } else if ($(this).val() == document.getElementById('uen' + i).value && $(this).val() == "") {
                                //se toma la linea
                                console.log("uen por defecto");
                                const $select = document.querySelector("#linea" + i);

                                const opcionCambiada = () => {
                                    console.log("cambio");
                                };

                                //se borran los valores de la linea
                                $select.addEventListener("change", opcionCambiada)
                                for (let k = $select.options.length; k >= 0; k--) {
                                    $select.remove(k);
                                }

                                //se toma la sublinea
                                const $select2 = document.querySelector("#sublinea" + i);

                                const opcionCambiada2 = () => {
                                    console.log("cambio");
                                };

                                //se borran los valores de la sublinea
                                $select2.addEventListener("change", opcionCambiada2)
                                for (let k = $select2.options.length; k >= 0; k--) {
                                    $select2.remove(k);
                                }
                                //se ocultan las opciones para que el usuario escoga el uen
                                $('#linea' + i).prop("disabled", true).val(-1);
                                $('#linea' + i).select2();
                                $('#sublinea' + i).prop("disabled", true).val(-1);
                                $('#sublinea' + i).select2();
                            }
                        }
                    }
                })

                $('#linea' + i).change(function (e) { //por cada fila, cuando se seleccione una linea, este hara cambios en otros campos
                    for (i = 0; i < numeroFila; i++) {
                        if (document.getElementById('linea' + i)) {
                            console.log("si");
                            //si se selecciona una linea y no es la por defecto
                            if ($(this).val() == document.getElementById('linea' + i).value && $(this).val() != "") {
                                //se habilita la sublinea
                                $('#sublinea' + i).prop("disabled", false).prop("required", false);
                                $('#sublinea' + i).select2();

                                //usa la variable que se definio en php para la sublinea
                                const datos = <?php echo json_encode($respuestaSubLinea); ?>
                                // Justo aquí estamos pasando la variable ----^
                                // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                const valores = datos.value;

                                //toma el select de la sublinea
                                const $select = document.querySelector("#sublinea" + i);

                                const opcionCambiada = () => {
                                    console.log("cambio");
                                };

                                //elimina todo lo que tenga el select
                                $select.addEventListener("change", opcionCambiada)
                                for (let k = $select.options.length; k >= 0; k--) {
                                    $select.remove(k);
                                }
                                //se crea la opcion por defecto
                                const option = document.createElement('option');
                                option.value = "";
                                option.text = "~";
                                option.selected;
                                $select.appendChild(option);
                                j = 0;
                                while (j >= 0 && j < 648) {//recorre los datos de la sublinea
                                    //tomamos los 4 primeros digitos
                                    x = valores[j]['FactorCode'] * 10 ** (-1);
                                    x = Math.floor(x);
                                    //si el vallor de x es igual al valor de la linea seleccionada psa la condicion
                                    if (x == $(this).val()) {
                                        $select.remove(0);//se quita la opcion por defecto
                                        const option = document.createElement('option');//agrega opcion para que el usuaio tenga que elegior una sublinea
                                        option.value = "NO";
                                        option.text = "Seleccione";
                                        $select.appendChild(option);
                                        //mientras x sea igual al valor de la linea es porque la sublinea hace parte de esta 
                                        while (x == ($(this).val())) {
                                            //se agrega la linea
                                            const option = document.createElement('option');
                                            option.value = valores[j]['FactorCode'];
                                            option.text = valores[j]['FactorCode'] + " | " + valores[j]['FactorDescription'];
                                            $select.appendChild(option);
                                            //se calcula el siguiente valor
                                            j++;
                                            x = valores[j]['FactorCode'] * 10 ** (-1);
                                            x = Math.floor(x);
                                        }
                                        j = -100;

                                    }
                                    $('#linea' + i).select2('close');
                                    j++;
                                }

                                //si la linea esta con el valor por defecto
                            } else if ($(this).val() == document.getElementById('linea' + i).value && $(this).val() == "") {
                                //toma la sublinea
                                const $select = document.querySelector("#sublinea" + i);

                                const opcionCambiada = () => {
                                    console.log("cambio");
                                };
                                //elimina todas las opciones de la sublinea
                                $select.addEventListener("change", opcionCambiada)
                                for (let k = $select.options.length; k >= 0; k--) {
                                    $select.remove(k);
                                }
                                //deshabilita la sublinea
                                $('#sublinea' + i).prop("disabled", true).val(-1);
                                $('#sublinea' + i).select2();
                            }
                        }
                    }
                })

            }
        });
    }
    $(document).on('click', '.borrar', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        numero = 1;
        for (i = 0; i < numeroFila; i++) {
            if (document.getElementById('numeroF' + i)) {
                console.log("si numero");
                //si se selecciono un codigo de articulo
                $('#numeroF' + i).val(numero);
                numero++;
            }
            else {
                console.log("no");
            }
        }
    });
    //funcion para calcular el total en cada fila
    function ftotal() {
        i = 0;
        while (i < numeroFila) { //para cargar el total en cada una de las filas
            if (document.getElementById('codigoArticulo' + i)) {
                console.log("si");
                const desc = document.getElementById('por_desc' + i).value; //toma el valor del porcenaje de descuento
                const precio = document.getElementById('precio_inf' + i).value; //toma el valor del precio info
                const impuesto = document.getElementById('ind_imp' + i).value; //toma el valor del precio info
                const cantidad = document.getElementById('cant_nec' + i).value; //toma el valor del precio info
                var impuestoPor = impuesto.split('~');
                console.log(impuestoPor[1]);
                totalml = precio - (desc * precio / 100);
                console.log(totalml);
                totalml = totalml + (impuestoPor[1] * totalml / 100)
                console.log(totalml);
                document.getElementById('total_ml' + i).value = totalml * cantidad; //le asigna al total el precio menos el porcentaje de descuento
            }
            i++;
        }
    }
    //funcion para guardar la solicitud 
    function guardarSolicitud() {
        ftotal();//calcula el total de todas las filas
        j = 0;
        //definimos los arrays para guardar los valores de cada fila
        cantidad = 0;
        codArse = [];
        fechaNec = [];
        proveedor = [];
        cant_nec = [];
        precioInfo = [];
        porDesc = [];
        indImp = [];
        total = [];
        uen = [];
        linea = [];
        sublinea = [];

        //recorre cada fila
        for (i = 0; i < numeroFila; i++) {
            if (document.getElementById('codigoArticulo' + i)) {
                console.log("enviar");
                check = document.getElementById('enviar' + i).checked; //devuelve true si el check esta seleccionado
                if (check == true) {//si esta seleccionado se guardan los datos de la fila
                    console.log("checkbox: SI");
                    codArse[j] = document.getElementById('codigoArticulo' + i).value;//toma el codigo del articulo
                    if (codArse[j] == "") {//si no se selecciono ningun codigo
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar un articulo').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#codigoArticulo' + i).focus();//acomoda la pagina en la casilla del codigo del articulo
                                $('#codigoArticulo' + i).select2('open');//despliega las opciones 
                            }
                        })
                        cantidad = -100;
                        break;

                    }
                    fechaNec[j] = document.getElementById('fecha_Nec' + i).value;//toma la fecha necesaria
                    if (fechaNec[j] == "") {//si la fecha necesaria esta vacia
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar la fecha necesaria').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                document.getElementById('fecha_Nec' + i).focus();//acomoda la pagina en la casilla
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    proveedor[j] = document.getElementById('proveedor' + i).value;//toma el proveedor
                    cant_nec[j] = document.getElementById('cant_nec' + i).value;//toma la cantidad necesaria
                    if (cant_nec[j] == "") {//si la cantidad necesaria esta vacia
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar la cantidad necesaria').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                document.getElementById('cant_nec' + i).focus();//se fija la pagina en la casilla      
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    precioInfo[j] = document.getElementById('precio_inf' + i).value;//toma el valor del precio info
                    porDesc[j] = document.getElementById('por_desc' + i).value;//toma el valor del porcentaje de descuento
                    indImp[j] = document.getElementById('ind_imp' + i).value;//toma el valor del indicador de impuesto
                    if (indImp[j] == "") {//si el indicadr esta vacio
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar el indicador de impuesto').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#ind_imp' + i).focus();//fija la pagina en la casilla del indicador
                                $('#ind_imp' + i).select2('open');//despliega las opciones del select
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    total[j] = document.getElementById('total_ml' + i).value;//toma el valor del total
                    uen[j] = document.getElementById('uen' + i).value;//toma el valor del uen
                    if (uen[j] == "") {//si el uen esta vacio
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar el uen').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#uen' + i).focus();//fija la pagina en la casilla del uen
                                $('#uen' + i).select2('open');//despliega las opcines del select
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    linea[j] = document.getElementById('linea' + i).value;//toma el valor de la linea
                    if (linea[j] == "NO") {//si no se ha seleccionado ninguna fila
                        //mmuestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar la linea').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#linea' + i).focus();//fija la pagina en la casila del la linea
                                $('#linea' + i).select2('open');//despliega las opciones del select
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    sublinea[j] = document.getElementById('sublinea' + i).value;//toma el valor de la sublinea
                    if (sublinea[j] == "NO") {//si no se ha seleccionado una sublinea
                        //muestra una alerta
                        Swal.fire('Error en la fila ' + (i + 1) + ' debe seleccionar la sublinea').then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $('#sublinea' + i).focus();//fija la pagina en la casilla de la sublinea
                                $('#sublinea' + i).select2('open');//despliega las opciones del select
                            }
                        })
                        cantidad = -100;
                        break;
                    }
                    cantidad++;
                    //imprime los valores en la consola
                    console.log("fila: ", j);
                    console.log("codigoArse: ", codArse[j]);
                    console.log("fechaNec: ", fechaNec[j]);
                    console.log("proveedor: ", proveedor[j]);
                    console.log("cantidad necesaria: ", cant_nec[j]);
                    console.log("precio info: ", precioInfo[j]);
                    console.log("porcentaje descuento   : ", porDesc[j]);
                    console.log("indicador de impuesto: ", indImp[j]);
                    console.log("total ml: ", total[j]);
                    console.log("uen: ", uen[j]);
                    console.log("linea: ", linea[j]);
                    console.log("sublinea: ", sublinea[j]);
                    console.log("cantidad: ", cantidad);
                    j++;
                } else {
                    console.log("checkbox: NO");
                }
            }
        }
        fechaNecesaria = document.getElementById('fechaNecesaria').value;
        if (fechaNecesaria == "") {//si no se ha seleccionado una sublinea
            //muestra una alerta
            Swal.fire('Error en la solicitud' + (i + 1) + ' debe seleccionar la fecha necesaria').then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#fechaNecesaria').focus();//fija la pagina en la casilla de la subline
                }
            })
            cantidad = -100;
        }
        if (cantidad > 0) { //si a cantidad es mayor que cero es porque no se ttuvo ningun problema en los datos de cada fila

            //datos de la solicitud
            nomSol = document.getElementById('nomSol').value;
            console.log("nomSol: ", nomSol);
            correoElectronico = document.getElementById('correoElectronico').value;
            console.log("correoElectronico: ", correoElectronico);
            propietario = document.getElementById('propietario').value;
            console.log("propietario: ", propietario);
            comentarios = document.getElementById('comentarios').value;
            console.log("comentarios: ", comentarios);
            codUsr = document.getElementById('codUsr').value;
            console.log("codUsr: ", codUsr);
            departamento = document.getElementById('departamento').value;
            console.log("departamento: ", departamento);
            sucursal = document.getElementById('sucursal').value;
            console.log("sucursal: ", sucursal);
            fechaDocumento = document.getElementById('fechaDocumento').value;
            console.log("fechaDocumento: ", fechaDocumento);
            //-----------------------------------------------
            //datos de los articulos
            //los convierte en string
            codArse = codArse.join('_').toString();
            fechaNec = fechaNec.join('_').toString();
            proveedor = proveedor.join('_').toString();
            cant_nec = cant_nec.join('_').toString();
            precioInfo = precioInfo.join('_').toString();
            porDesc = porDesc.join('_').toString();
            indImp = indImp.join('_').toString();
            total = total.join('_').toString();
            uen = uen.join('_').toString();
            linea = linea.join('_').toString();
            sublinea = sublinea.join('_').toString();
            // console.log("a"+proveedor+"a");
            //-------------------------------con ajax se envian los datos por url a guardarArticulo.php 
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
                    $('#principal').fadeOut();
                    $('#carga').prop("hidden", false);
                    $.ajax(
                        {
                            url: '../crud/guardarArticulo.php?codigoArse=' + codArse + '&fechaNec=' + fechaNec + '&proveedor=' + proveedor + '&cantNec=' + cant_nec + ' &precioInfo=' + precioInfo + '&uen=' + uen + '&linea=' + linea + '&sublinea=' + sublinea + '\n\
                                                            &porDesc=' + porDesc + '&indImp=' + indImp + '&total=' + total + '&cantidad=' + cantidad + '\n\
                                                            &nomSol=' + nomSol + '&correoElectronico=' + correoElectronico + '&propietario=' + propietario + '\n\
                                                            &comentarios=' + comentarios + '&codUsr=' + codUsr + '&departamento=' + departamento + '&sucursal=' + sucursal + '&fechaNecesaria=' + fechaNecesaria + '&fechaDocumento=' + fechaDocumento,
                            success: function (data) { //si todoo estuvo correcto
                                //se muestra una alerta con el numero de la solicitud que se creo
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Su soliciud fue creada:',
                                    html: '<span class="letra-blanco">numero de soliciud: ' + data + '</span>'
                                }).then((result) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (result.isConfirmed) {
                                        window.location = "misSolicitudes.php?xtabla=tarticulos"; //manda al usuario a misSolicitudes y ahi puede ver su solicitud
                                    }
                                })
                            }
                        })
                }
            })
        }
    }        
</script>

</html>