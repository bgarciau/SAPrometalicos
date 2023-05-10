<?php
require('../fpdf/fpdf.php');
include('../php/conexion.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        include('../php/conexion.php');
        if (isset($_GET["numSol"])) {
            $numSol = $_GET["numSol"];
        }
        $soli = $base->query("SELECT * FROM solicitud_compra WHERE pk_num_sol='$numSol'")->fetchAll(PDO::FETCH_OBJ); // se guardan los datos de la solicitud de compra en un PDOStatement
        foreach ($soli as $solis) {
            $numeroDocumento = $solis->numSAP;
            $solicitanteN = $solis->fk_cod_usr;
            $nombreSolicitante = $solis->nom_solicitante;
            $correo = $solis->correo_sol;
        }
        // Logo
        $this->Image('../images/logo2.png', 10, 8, 35);
        // Arial bold 12
        $this->SetFont('Arial', 'B', 9);
        // Movernos a la derecha
        // $this->Cell(80);
        // Título
        $this->Cell(150, 0, 'BASCULAS PROMETALICOS SA', 0, 0, 'C');
        $this->Cell(-60, 0, 'Original ', 0, 0, 'C');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(125, 0, '|  SOLICITUD DE COMPRA', 0, 0, 'C');
        $this->Ln(1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(305, 0, '.....................................................................................................................', 0, 0, 'C');
        // Salto de línea
        $this->Ln(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(116, 0, 'CRA 21 72-04', 0, 0, 'C');
        $this->Cell(20, 0, 'Numero de documento', 0, 0, 'C');
        $this->Cell(40, 0, 'Fecha de documento', 0, 0, 'C');
        $this->Cell(0, 0, 'Pagina', 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(123, 0, '170001 MANIZALES', 0, 0, 'C');
        $this->Cell(8, 0, $numeroDocumento, 0, 0, 'C');
        $this->Cell(48, 0, date("d/m/Y"), 0, 0, 'C');
        $this->Cell(0, 0, '' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(113, 0, 'COLOMBIA', 0, 0, 'C');
        $this->Ln();
        $this->Cell(305, 0, '.....................................................................................................................', 0, 0, 'C');
        // Salto de línea
        $this->Ln(3);
        $this->SetFont('Arial', '', 7);
        $this->Cell(240, 0, 'SolicitanteN', 0, 0, 'C');
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(0, 0, $solicitanteN, 0, 0, 'R');
        $this->Ln(3);
        $this->SetFont('Arial', '', 7);
        $this->Cell(251, 0, 'Nombre de solicitante', 0, 0, 'C');
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(0, 0, $nombreSolicitante, 0, 0, 'R');
        $this->Ln(3);
        $this->SetFont('Arial', '', 7);
        $this->Cell(247, 0, 'Correo electronico', 0, 0, 'C');
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(0, 0, $correo, 0, 0, 'R');
        $this->Ln(2);
        $this->Cell(0, 0, '..............................................................................................................................................................................................................................................................................................', 0, 0, 'C');

        // Salto de línea
        $this->Ln(5);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(213, 219, 219);
$pdf->Cell(6, 10, '', 0, 0, 'C', 1);
$pdf->Cell(20, 10, 'Codigo', 0, 0, 'C', 1);
$pdf->Cell(80, 10, 'Descripcion', 0, 0, 'C', 1);
$pdf->Cell(20, 10, 'Fecha Nec', 0, 0, 'C', 1);
$pdf->Cell(16, 10, 'Proyecto', 0, 0, 'C', 1);
$pdf->Cell(16, 10, 'Precio', 0, 0, 'C', 1);
$pdf->Cell(16, 10, 'Cantidad', 0, 0, 'C', 1);
$pdf->Cell(16, 10, 'Total', 0, 0, 'C', 1);
$pdf->Ln(5);
if (isset($_GET["numSol"])) {
    $numSol = $_GET["numSol"];
}
$lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$numSol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los servicios de la solicitud en la variable 
$i = 1;
$pdf->SetFont('Arial', 'B', 8);
$subtotal=0;
$total=0;
foreach ($lista as $listaa) {
    $pdf->Ln(5);
    if ($i < 10) {
        $pdf->Cell(6, 10, '00' . $i, 0, 0, 'L', false);
    }
    if ($i >= 10) {
        $pdf->Cell(6, 10, '0' . $i, 0, 0, 'L', false);
    }
    if ($i >= 100) {
        $pdf->Cell(6, 10, '' . $i, 0, 0, 'L', false);
    }
    $pdf->Cell(20, 10, $listaa->codigo_articulo, 0, 0, 'L', false);
    $descripcion=$listaa->nom_arse;
    if(strlen($descripcion)>=40){
        $k=0;
        $descripcion2="";
        while($k<=40){
            $descripcion2=$descripcion2.$descripcion[$k];
            $k++;
        }
        $descripcion2=$descripcion2." ...";
        $pdf->Cell(80, 10, $descripcion2, 0, 0, 'L', false);
    }
    else{
        $pdf->Cell(80, 10, $descripcion, 0, 0, 'L', false);
    }
    $pdf->Cell(20, 10, $listaa->fecha_nec, 0, 0, 'C', false);
    $pdf->Cell(16, 10, $listaa->proyecto , 0, 0, 'L', false);
    $precio=$listaa->precio_info -( $listaa->por_desc * $listaa->precio_info / 100);
    $pdf->Cell(16, 10, number_format($precio,2,",","."), 0, 0, 'R', false);
    $pdf->Cell(16, 10, $listaa->cant_nec, 0, 0, 'C', false);
    if($listaa->cant_nec==""){
        $cantidad=1;
    }
    else{
        $cantidad=$listaa->cant_nec;
    }
    $pdf->Cell(16, 10, number_format($precio*$cantidad,2,",","."), 00, 0, 'R', false);
    $subtotal=$subtotal+($precio*$cantidad);
    $total=$total+$listaa->total_ml;
}
$pdf->Ln(10);
$pdf->Cell(156, 10, '', 0, 0, false);
$pdf->Cell(18, 10, 'Sub Total', 0, 0, 'L', false);
$pdf->Cell(18, 10, number_format($subtotal*$cantidad,2,",","."), 00, 0, 'R', false);
$pdf->Ln(5);
$pdf->Cell(156, 10, '', 0, 0, false);
$pdf->Cell(18, 10, 'impuestos', 0, 0, 'L', false);
$pdf->Cell(18, 10, number_format($total-$subtotal,2,",","."), 00, 0, 'R', false);
$pdf->Ln(5);
$pdf->Cell(156, 10, '', 0, 0, false);
$pdf->Cell(18, 10, 'Total', 0, 0, 'L', false);
$pdf->Cell(18, 10, number_format($total,2,",","."), 00, 0, 'R', false);
$pdf->Output();
?>