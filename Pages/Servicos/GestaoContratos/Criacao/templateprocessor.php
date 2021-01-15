<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);


#header ('Content-type: text/html; charset=iso-8859-1');

$idCliente          = $_REQUEST['id'];
$arquivoProcessado  = $_REQUEST['arquivo'];



/* 
contrato_m1.docx
Lista de vari�veis para substitui��o
${razaoSocial}
${endereco}
${numero}
${bairro}
${cep}
${cidade}
${uf}
${cnpj}
${proprietario}
${nacionalidade}
${estadocivil}
${profissao}
${rg}
${cpf}
${proprietarioRua}
${proprietarioNum}
${proprietarioBairro}
${proprietarioCep}
${proprietarioCidade}
${proprietarioUF};

${DevRazaoSocial}
${DevEndereco}
${DevNumero}
${DevBairro}
${DevCEP}
${DevCidade}
${DevUF}
${DevCnpj}
${DevPropNome}
${DevPropNacional}
${DevPropEstCivil}
${DevPropProf}
${DevPropRg}
${DevPropCpf}
${DevPropRua}
${DevPropNum}
${DevPropBairro}
${DevPropCep}
${DevPropCidade}
${DevPropUF}

procedimento para o processamento 
*/

include '../../../../con.php';

#busca os dados do cliente para inser��o no contrato
$query = "select * from tbl_clientes where id='".$idCliente."'";
                    
$result = $con->query($query);
if($result->num_rows > 0) {  
  $row = $result->fetch_object(); 
}else{
    $clienteNome = "Cliente n&atilde;o encontrado.";
}

include '../../../../assets/vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

#faz o clone do arquivo para processamento
$source     = '../../../../assets/contratoTemplates/'.$arquivoProcessado;
$tempSaida  = '../../../../assets/contratoTemplates/temp/'.$arquivoProcessado;



$templateProcessor = new TemplateProcessor($source);

#gera a matriz das informa��es


$templateProcessor->setValues(array(
    'razaoSocial'       => htmlspecialchars($row->razaoSocial_nome),
    'endereco'          => htmlspecialchars($row->logradouro),
    'numero'            => htmlspecialchars($row->numero). htmlspecialchars($row->complemento) ? " - " . htmlspecialchars($row->complemento) : " ",
    'bairro'            => htmlspecialchars($row->bairro),
    'cep'               => htmlspecialchars($row->cep),
    'cidade'            => htmlspecialchars($row->cidade),
    'uf'                => htmlspecialchars($row->estado),
    'cnpj'              => htmlspecialchars($row->cnpj_cpf),
    'proprietario'      => htmlspecialchars($row->nomeResponsavel),
    'nacionalidade'     => '',
    'estadocivil'       => '',
    'profissao'         => '',
    'rg'                => '',
    'cpf'               => htmlspecialchars($row->cpfResponsavel),
    'proprietarioRua'   => '',
    'proprietarioNum'   => '',
    'proprietarioBairro' => '',
    'proprietarioCep'   => '',
    'proprietarioCidade' => '',
    'proprietarioUF'    => '',
    'DevRazaoSocial'    => '',
    'DevEndereco'       => '',
    'DevNumero'         => '',
    'DevBairro'         => '',
    'DevCEP'            => '',
    'DevCidade'         => '',
    'DevUF'             => '',
    'DevCnpj'           => '',
    'DevPropNome'       => '',
    'DevPropNacional'   => '',
    'DevPropEstCivil'   => '',
    'DevPropProf'       => '',
    'DevPropRg'         => '',
    'DevPropCpf'        => '',
    'DevPropRua'        => '',
    'DevPropNum'        => '',
    'DevPropBairro'     => '',
    'DevPropCep'        => '',
    'DevPropCidade'     => '',
    'DevPropUF'         => ''
));
  


#chmod($tempSaida, 0777);

$templateProcessor->saveAs($tempSaida);

  #agora faz a leitura do arquivo processado
  $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempSaida);

  $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
  
  $objWriter->save($tempSaida.".html");

  echo file_get_contents($tempSaida.".html");







?>

