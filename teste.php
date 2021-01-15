<?
require_once('functions.php');
require_once('core/lib/nfe/class.php');

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

use NFePHP\DA\NFe\Danfe;

/*$danfe = new Danfe(file_get_contents('upload/notaFinal.xml'));
file_put_contents('upload/pdfNota.pdf',$danfe->render());*/


$nota = new Nfe($con);

$gerar = $nota->gerarXML();
var_dump($gerar,'<br><br>');
file_put_contents('upload/nota.xml',$gerar['xml']);
var_dump($nota->assinarXML(),'<br><br>');
var_dump($nota->enviarLote(),'<br><br>');
var_dump($nota->consultarRecibo(),'<br><br>');

$resp = $nota->finProcesso();
if($resp['status'] == 1){
    file_put_contents('upload/notaFinal.xml',$resp['xml']);
    $danfe = new Danfe($resp['xml']);
    echo $danfe->render();
}
else
    echo '<script>alert("'.$resp['erro'].'")</script>';


?>