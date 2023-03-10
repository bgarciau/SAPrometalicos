<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
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
            $i=1;
            ?>
        </header>
        <div class="contenedor">
            UEN
            <select class="selectServicio" name="uen<?php echo $i ?>" id="uen<?php echo $i ?>" hidden>
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
            </select><br>
            LINEA
            <select class="selectServicio" name="linea<?php echo $i ?>" id="linea<?php echo $i ?>" hidden>
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
            </select><br>
            SUBLINEA
            <select class="selectServicio" name="sublinea<?php echo $i ?>" id="sublinea<?php echo $i ?>" hidden>
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
            </select><br>
            <script>
                i=1;
                $('#uen' + i).select2();
                // $('#linea' + i).select2();
                // $('#sublinea' + i).select2();

                $(document).ready(function(){
                    
                    $('#uen' + i).change(function(e) {
                        if ($(this).val() == document.getElementById('uen' + i).value && $(this).val() != -1) {
                            $('#linea' + i).val(-1)
                            $('#linea' + i).select2();
                        }
                        else{
                            $('#linea' + i).select2('destroy');
                            $('#sublinea' + i).select2('destroy');
                        }

                    })

                    $('#linea' + i).change(function(e) {
                        if ($(this).val() == document.getElementById('linea' + i).value && $(this).val() != -1) {
                            $('#sublinea' + i).val(-1)
                            $('#sublinea' + i).select2();
                        }
                        else{
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