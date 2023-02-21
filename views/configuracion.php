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
    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">   
        <div id="div__agregarU">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h2>CONFIGURACION</h2>
        <H3>CAMBIAR DATOS</H3>
                <input class="inputc" type="checkbox" name="solicitante" checked>
                <input class="inputA" type="text" name="solicitante" placeholder="Solicitante">
                <input class="inputc" type="checkbox" name="numeroSol" checked>
                <input class="inputA" type="text" name="nomeroSol" placeholder="N° solicitud"><br>
                <input class="inputc" type="checkbox" name="NombreSolicitante" checked>
                <input class="inputA" type="text" name="NombreSolicitante" placeholder="Nmbre Solicitante">
                <input class="inputc" type="checkbox" name="estado" checked>
                <input class="inputA" type="text" name="estado" placeholder="Estado"><br>
                <input class="inputc" type="checkbox" name="sucursal" checked>
                <input class="inputA" type="text" name="sucursal" placeholder="Sucursal">
                <input class="inputc" type="checkbox" name="fechaContabilizacion" checked>
                <input class="inputA" type="text" name="fechaContabiliacion" placeholder="Fecha contabilizacion"><br>
                <input class="inputc" type="checkbox" name="departamento" checked>
                <input class="inputA" type="text" name="departamento" placeholder="Departamento">
                <input class="inputc" type="checkbox" name="Valido hasta" checked>
                <input class="inputA" type="text" name="validoHasta" placeholder="Valido hasta"><br>
                <input class="inputc" type="checkbox" name="envCorreo" checked>
                <input class="inputA" type="text" name="envCorreo" placeholder="Direccion de correo electronico">
                <input class="inputc" type="checkbox" name="fechaDoc" checked>
                <input class="inputA" type="text" name="fechaDoc" placeholder="Fecha documento"><br>
                <input class="inputc" type="checkbox" name="fechaNec" checked>
                <input class="inputA" type="text" name="fechaNec" placeholder="Fecha necesaria"><br>
                <a><input class="btn_env" type="submit" value="+AGREGAR" name="addParametro"></a><br>
                <a><input class="btn_env" type="button" value="CONFIRMAR" name="confirmar"></a><br>
                <br><br>
                <h3>CAMBIAR FOOTER</h3>
                <footer>
                <div class="wrapper2">
                       <textarea class="textareaF" name="tfooter" cols="30" rows="10">
Cra 21 N° 72-04 Zona Industrial Alta suiza Manizales, Colombia
2023 
Todos los derechos reservados</textarea>
                </div>
                </footer>
                <a><input class="btn_env" type="button" value="CONFIRMAR" name="confirmar"></a><br>
                <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>

        </div>
    </div> 
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>