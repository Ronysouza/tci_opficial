<?php 
require '../../../../core/lib/dompdf/autoload.inc.php';
require '../../../../con.php';

//obtem as informa��es do banco de dados
#busca se ja existe algum contrato cadastrado no banco
$busca_contrato = "select * from tbl_doc_contrato where id = '".$_REQUEST['id']."'";
$exec_contratos = $con->query($busca_contrato);
$contrato       = $exec_contratos->fetch_object();

$conteudo       = base64_decode($contrato->conteudo);


// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', TRUE);
// instantiate and use the dompdf class
$dompdf = new Dompdf($options);
$dompdf->loadHtml($conteudo);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');


// Render the HTML as PDF
$dompdf->render();
ob_end_clean();
// Output the generated PDF to Browser
#$dompdf->stream("sample.pdf", array("Attachment"=>0));
$dompdf->stream("Contrato.pdf");

?>

