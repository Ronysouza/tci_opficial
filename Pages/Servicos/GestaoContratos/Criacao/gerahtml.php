<?php 
require 'assets/vendor/autoload.php';

$source = 'assets/contratoTemplates/contrato_m1.docx';

$phpWord = \PhpOffice\PhpWord\IOFactory::load($source);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->save("saida.html");

?>

