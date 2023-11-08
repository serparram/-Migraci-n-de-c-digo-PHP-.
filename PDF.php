<?php
require_once 'config.php';
require_once 'fpdf/fpdf.php';
require "conversor.php";
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 052', PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);




setlocale(LC_ALL, '');



$titulo= 'RECIBO  DE CARGO';

$folio='ret_id';

$idCertificado=$_POST["idCertificado"];





$sql = "SELECT *,date_format(fechaEmision,'%d/%m/%Y')as fecha  from certificados where idCertificados='".$idCertificado."'";

$res = mysqli_query($conexion, $sql) or die( mysqli_error($conexion));

$comprobante = mysqli_fetch_assoc($res);



$montopalabras=convertir($comprobante['montoDonacion']); 



$raiz = '';

$ruta = 'planchetas/';

$resultadoQuery =mysqli_num_rows($res);



if($resultadoQuery==0){

?>

    <script type="text/javascript">

        alert('Este certificado no ha sido emitido. Intente Nuevamente.');

        window.document.location="verPDF.php";

    </script>

    

    <?php

}else{



class PDF extends FPDF

{

    // Cabecera de página

    function Header()

    {

        global $opc;

        // Logo

        $this->Image('refugio.png',40 ,20, 70 , 20);

        

        $this->Ln(35);

        // Arial bold 15

        $this->SetFont('Arial','',20);

        $this->Cell(120);

        $this->Cell (60, 5, utf8_decode('N°'.$_POST["idCertificado"]),0, 1, 'C');

        $this->SetFont('Arial','B',10);



        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('FUNDACIÓN REFUGIO DE CRISTO'),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('OTRAS ACTIVIDADES DE ATENCIÓN EN INSTITUCIONES'),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('R.U.T: 70.015.560-9'),0, 1, 'C');

        $this->SetFont('Arial','',10);

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('Personería Juridica: D.S. Ministerio Justicia N°2410'),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('de 15 de Mayo de 1953 '),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('Representante Legal: Eduardo Kovacs Amengual'),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('R.U.T: 5.138.743-0'),0, 1, 'C');

        $this->Cell(30);

        $this->SetFont('Arial','B',10);

        $this->Cell (40, 5, utf8_decode('Casa Matriz: Victoria N° 2370 - Fono 322 213 431 - VALPARAÍSO '),0, 1, 'C');

        $this->Cell(30);

        $this->SetFont('Arial','',10);

        $this->Cell (40, 5, utf8_decode('Sucursal: Mall Marina Arauco - Av. Libertad 1348 - Local B-8, 2do Piso'),0, 1, 'C');

        $this->Cell(30);

        $this->Cell (40, 5, utf8_decode('Plaza Rosa de los Vientos - Viña del Mar'),0, 1, 'C');

        // Título        

        //$this->Cell (0, 20, utf8_decode('N°'.$_POST["idCertificado"]),0, 0, 'R');

        

        //$this->Image('images/natales.jpg',170,10,30,0);

        // Salto de línea

        $this->Ln(10);

    }

    

    // Pie de página

    function Footer()

    {

        // Posición: a 1,5 cm del final

        $this->SetY(-15);

        // Arial italic 8

        $this->SetFont('Arial','I',8);

        // Número de página

        //$this->Cell(0,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');

    }

    

    function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J')

    {

        // Calculate MultiCell with automatic or explicit line breaks height

        // $border is un-used, but I kept it in the parameters to keep the call

        //  to this function consistent with MultiCell()

        $cw = &$this->CurrentFont['cw'];

        if($w==0)

            $w = $this->w-$this->rMargin-$this->x;

        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;

        $s = str_replace("\r",'',$txt);

        $nb = strlen($s);

        if($nb>0 && $s[$nb-1]=="\n")

            $nb--;

        $sep = -1;

        $i = 0;

        $j = 0;

        $l = 0;

        $ns = 0;

        $height = 0;

        while($i<$nb)

        {

            // Get next character

            $c = $s[$i];

            if($c=="\n")

            {

                // Explicit line break

                if($this->ws>0)

                {

                    $this->ws = 0;

                    $this->_out('0 Tw');

                }

                //Increase Height

                $height += $h;

                $i++;

                $sep = -1;

                $j = $i;

                $l = 0;

                $ns = 0;

                continue;

            }

            if($c==' ')

            {

                $sep = $i;

                $ls = $l;

                $ns++;

            }

            $l += $cw[$c];

            if($l>$wmax)

            {

                // Automatic line break

                if($sep==-1)

                {

                    if($i==$j)

                        $i++;

                    if($this->ws>0)

                    {

                        $this->ws = 0;

                        $this->_out('0 Tw');

                    }

                    //Increase Height

                    $height += $h;

                }

                else

                {

                    if($align=='J')

                    {

                        $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;

                        $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));

                    }

                    //Increase Height

                    $height += $h;

                    $i = $sep+1;

                }

                $sep = -1;

                $j = $i;

                $l = 0;

                $ns = 0;

            }

            else

                $i++;

        }

        // Last chunk

        if($this->ws>0)

        {

            $this->ws = 0;

            $this->_out('0 Tw');

        }

        //Increase Height

        $height += $h;

        

        return $height;

    }

}



// Creación del objeto de la clase heredada

$pdf = new PDF('P','mm','Legal');

//Define márgen izq, sup, y der.

$pdf->SetMargins(20, 10, 10);

$pdf->AliasNbPages();

// Página #1

$pdf->AddPage();

//Sub título

//$pdf->SetFont('Arial','B',11);

//$pdf->Cell(0, 10, 'Individualización de la ubicación física de las especies','', 0, 'C');

//$pdf->Ln(10);



// Encabezado

$pdf->SetFont('Arial','B',12);

$pdf->MultiCell(190,5,utf8_decode('CERTIFICADO QUE ACREDITA DONACION ART. 46 D.L. 3063 DE 1979'), 0, 'C');

$pdf->Cell(15,5,'',0,1);

$pdf->SetFont('Arial','',11);

$pdf->MultiCell(190,5,utf8_decode('Acredita donación de dinero para los fines de atender niños de familia de mayor necesidad y/o  

programas de acción social de igual finalidad.'));



$pdf->Ln(10);

$pdf->SetFont('Arial','',11);

$pdf->Cell(40, 5, utf8_decode('Nombre o razón social del donante: '.$comprobante["nombreSocio"]), 0, 1);

$pdf->Cell(40, 5, '', 0, 1);

$pdf->Ln(6);

$pdf->SetFont('Arial','',11);

$pdf->Cell(40, 5, utf8_decode('Rol Unico Tributario: '.$comprobante["rut"]), 0, 1);

$pdf->Cell(0, 5, '', 0, 1);

$pdf->Ln(6);

$pdf->SetFont('Arial','',11);

$pdf->Cell(40, 5, utf8_decode('Domicilio: '.$comprobante["domicilioSocio"]), 0, 1);

$pdf->Cell(40, 5, '', 0, 1);

$pdf->Ln(6);

$pdf->SetFont('Arial','',11);

$pdf->Cell(40, 5, utf8_decode('Actividad o giro: '.$comprobante["descripcionGiro"]), 0, 1);

$pdf->Ln(6);

$pdf->Cell(0, 5, '', 0, 1);

$pdf->Cell(150, 5, 'Representante Legal: '.$comprobante["representanteLegal"], 0, 0);

$pdf->Cell(0, 5, 'R.U.T: '.$comprobante['rutRepresentanteLegal'], 0, 1);

$pdf->Cell(0, 5, '', 0, 1);

$pdf->Ln(6);

$pdf->Cell(80, 5, utf8_decode('Monto de la donación $: ').number_format($comprobante["montoDonacion"], 0, ',', '.'), 0, 0);



$pdf->MultiCell(0, 5, 'Son: '.$montopalabras, 0, 1);

$pdf->Cell(0, 5, '', 0, 1);

$pdf->Ln(2);

$pdf->Cell(40, 5, utf8_decode('Recibo N°: '.$comprobante["numeroRecibo"]), 0, 1);

#$pdf->Cell(0, 5, '', 0, 1);

$pdf->Ln(8);

$pdf->Cell(40, 5, utf8_decode('Fecha de donación: '.$comprobante["fechaDonacion"]), 0, 1);

$pdf->Ln(8);

$pdf->Cell(80, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Nombre: Carolina Diaz Lobos ', 0, 1);
$pdf->Cell(95, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Administradora General', 0, 1);
$pdf->Cell(0, 5, '', 0, 1);
$pdf->Ln(8);

$pdf->Cell(80, 5, '', 0, 0);
#$pdf->Cell(0, 5, 'Firma: ', 0, 1);
#$pdf-> Image('CD_FUNDACION.jpg', 113, 278, 65);
#$pdf-> Image('CD_FUNDACION.jpg', 115, 280, 60 , 38);

$pdf->Ln(8);

$pdf->Cell(140, 5, utf8_decode('Fecha de la emisión: '.$comprobante["fecha"]), 0, 0);

$pdf->Ln(10);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(0, 5, utf8_decode('** ESTE CERTIFICADO ES UNA COPIA DEL DOCUMENTO ORIGINAL Y NO DA DERECHO A NINGÚN BENEFICIO TRIBUTARIO **'), 0, 1, 'C');

//$pdf->Cell(40, 5, 'Fecha: ', 0, 0);

//$pdf->Cell(0, 5, substr($comprobante['horainicio'],0,5), 0, 1);



//$pdf->Cell(40, 5, 'Monto de la donacion $:', 0, 0);

//$pdf->Cell(0, 5, substr($comprobante['horafin'],0,5), 0, 1);



//$pdf->Cell(40, 5, 'Son: ', 0, 0);

//$pdf->Cell(0, 5, $comprobante['fpago'], 0, 1);



//$pdf->Cell(40, 5, 'Recibo N: ', 0, 0);

//$pdf->Cell(0, 5, $comprobante['precio'], 0, 1);

$pdf->Cell(15,5,'',0,1);

$pdf->SetFont('Arial','',10);

//$pdf->MultiCell(190,5,utf8_decode('Para confirmar la reserva debe acercarse a la oficina mas cercana para realizar el pago correspondiente. Recuerde acompañar este comprobante de reserva.'));

//$pdf->Cell(0,20,'Correo contacto: xxxxxx@xxx.cl - Fono: +56 61 2209500',0, 0, 'C');



$pdf->Ln(25);



// Firmas y Timbres

$pdf->SetFont('Arial','',10);

$h=5;



//$pdf->Cell(186, $h, '_______________________________________________________________', 0, 1, 'C');



//$pdf->Cell(40, $h, '', 0, 0);

//$pdf->Cell(42, $h, 'FIRMA RESPONSABLE:', 0, 0);

//$pdf->Cell(50, $h, $usu['nombre_custodio'], 0, 0);

//$pdf->Cell(10, $h, 'RUT:', 0, 0);

//$pdf->Cell(20, $h, $usu['rut_custodio'], 0, 1);

//$pdf->Ln(15);



// Hasta aquí Y tiene una altura de 80m.m (cabecera)

//$y = $pdf->GetY();

//$pdf->Cell(0, 5, 'Y='.$y, 1, 1);



   

/*responsable*/

/*$responsable = "SELECT responsable FROM inventario 

            WHERE departamento='".$comprobante."' AND unidad='".$Direccion."' AND dependencia='".$Departamento."' AND sector='".$Seccion."' 

            AND oficina IN ('".$Oficina."') AND descripcion != '**NULO**'

            ORDER BY codigo_del_bien";

$resultresponsable = mysqli_query($conexion, $responsable) or die(mysqli_error($conexion));

$fila = mysqli_fetch_assoc($resultresponsable);

$res=$fila['responsable'];*/



// Títulos del General


/*
require_once('tcpdf/tcpdf.php');
$certificate = 'file://fpdf/tcpdf.crt';
$info = array(
  'Name' => 'TCPDF',
  'Location' => 'Office',
  'Reason' => 'Testing TCPDF',
  'ContactInfo' => 'http://www.tcpdf.org',
  );
$pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
$text = 'This is a <b color="#FF0000">digitally signed document</b> using the default (example) <b>tcpdf.crt</b> certificate.<br />To validate this signature you have to load the <b color="#006600">tcpdf.fdf</b> on the Arobat Reader to add the certificate to <i>List of Trusted Identities</i>.<br /><br />For more information check the source code of this example and the source code documentation for the <i>setSignature()</i> method.<br /><br /><a href="http://www.tcpdf.org">www.tcpdf.org</a>';
$pdf->writeHTML($text, true, 0, true, 0);
$pdf->Image('tcpdf/examples/images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');
$pdf->setSignatureAppearance(180, 60, 15, 15);
$pdf->addEmptySignatureAppearance(180, 80, 15, 15);
*/






$timestamp = date("d_m_Y_H_i_s");

$nombreCompletoDecreto = 'CertificadoDonacion-'.$_POST["idCertificado"].'.pdf';



// Genera PDF según $opc

if( $opc == 'preview' ){

    // Sale PDF en "vista previa"

    $pdf->Output('I', $nombreCompletoDecreto);

    

}else{

    // Sale PDF en "modo descarga e impresión"

    $pdf->Output('I', $nombreCompletoDecreto);

    // Guarda copia del PDF en "directorio local"

    //$pdf->Output('F', $raiz.$ruta.$nombreCompletoDecreto);

}

// set document signature
$pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set certificate file
$certificate = 'file://'.realpath('tcpdf.crt');

// create content for signature (image and/or text)
$pdf->Image('images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');

// define active area for signature appearance
$pdf->setSignatureAppearance(70, 270, 50, 10);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
$pdf->addEmptySignatureAppearance(70, 270, 50, 10);

}

mysqli_close($conexion);





?>