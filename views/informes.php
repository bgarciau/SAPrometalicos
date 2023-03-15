<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }
    include("../php/SAP.php");
    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php');
            $i = 1;
            ?>
        </header>
        <div class="contenedor">
            UEN
            <select class="selectServicio" name="uen<?php echo $i ?>" id="uen<?php echo $i ?>" hidden>
                <option value="<?php echo (-1) ?>" selected>~</option>
                <?php
                $s = 0;
                foreach ($respuestaUen->value as $item):

                    ?>
                    <option value="<?php echo $item->FactorCode ?>"><?php echo "$item->FactorCode | $item->FactorDescription" . PHP_EOL; ?></option>
                    <?php
                    $s++;
                endforeach;
                ?>
            </select><br>
            LINEA
            <select class="selectServicio" name="linea<?php echo $i ?>" id="linea<?php echo $i ?>"></select><br>
            SUBLINEA
            <select class="selectServicio" name="sublinea<?php echo $i ?>" id="sublinea<?php echo $i ?>" hidden>
                <option value="<?php echo (-1) ?>" selected>~</option>
                <?php
                $s = 0; foreach ($respuestaSubLinea->value as $item):

                    ?>
                    <option value="<?php echo $item->FactorCode ?>"><?php echo "$item->FactorCode | $item->FactorDescription" . PHP_EOL; ?></option>
                    <?php
                    $s++;
                endforeach;
                ?>
            </select><br>
            <script>
                i = 1;
                $('#uen' + i).select2();
                // $('#linea' + i).select2();
                // $('#sublinea' + i).select2();

                $(document).ready(function () {

                    $('#uen' + i).change(function (e) {
                        if ($(this).val() == document.getElementById('uen' + i).value && $(this).val() != -1) {
                            $('#linea' + i).val(-1)
                            $('#linea' + i).select2();
                            const datos = <?php echo json_encode($respuestaLinea); ?>
                            // Justo aquí estamos pasando la variable ----^
                            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                            const valores = datos.value;

                            console.log("Los valores son: ", valores);

                            const $select = document.querySelector("#linea" + i);

                            const opcionCambiada = () => {
                                console.log("cambio");
                            };

                            $select.addEventListener("change", opcionCambiada)
                            for (let k = $select.options.length; k >= 0; k--) {
                                $select.remove(k);
                            }
                            j = 0;
                            while (j => 0) {
                                x = valores[j]['FactorCode'] * 10 ** (-1);
                                x = Math.floor(x);
                                if (x == $(this).val()) {
                                    while (x == ($(this).val())) {
                                        const option = document.createElement('option');
                                        option.value = valores[j]['FactorCode'];
                                        option.text = valores[j]['FactorCode']+" | "+valores[j]['FactorDescription'];
                                        $select.appendChild(option);
                                        console.log("valor de linea: ", x);
                                        j++;
                                        x = valores[j]['FactorCode'] * 10 ** (-1);
                                        x = Math.floor(x);
                                    }
                                    j = -100;
                                    $('#uen' + i).select2('close');
                                }
                                j++;
                            }
                            

                        } else {
                            $('#linea' + i).select2('destroy');
                            $('#sublinea' + i).select2('destroy');
                        }

                    })

                    $('#linea' + i).change(function (e) {
                        if ($(this).val() == document.getElementById('linea' + i).value && $(this).val() != -1) {
                            $('#sublinea' + i).val(-1)
                            $('#sublinea' + i).select2();
                            const datos = <?php echo json_encode($respuestaSubLinea); ?>
                            // Justo aquí estamos pasando la variable ----^
                            // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                            const valores = datos.value;

                            console.log("Los valores son: ", valores);

                            const $select = document.querySelector("#sublinea" + i);

                            const opcionCambiada = () => {
                                console.log("cambio");
                            };

                            $select.addEventListener("change", opcionCambiada)
                            for (let k = $select.options.length; k >= 0; k--) {
                                $select.remove(k);
                            }
                            j = 0;
                            while (j => 0) {
                                x = valores[j]['FactorCode'] * 10 ** (-1);
                                x = Math.floor(x);
                                if (x == $(this).val()) {
                                    while (x == ($(this).val())) {
                                        const option = document.createElement('option');
                                        option.value = valores[j]['FactorCode'];
                                        option.text = valores[j]['FactorCode']+" | "+valores[j]['FactorDescription'];
                                        $select.appendChild(option);
                                        console.log("valor de linea: ", x);
                                        j++;
                                        x = valores[j]['FactorCode'] * 10 ** (-1);
                                        x = Math.floor(x);
                                    }
                                    j = -100;
                                    $('#linea' + i).select2('close');
                                }
                                j++;
                            }
                        } else {
                            $('#sublinea' + i).select2('destroy');
                        }
                    })
                });
            </script>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php');
            ?>
        </footer>
    </div>
</body>

</html>