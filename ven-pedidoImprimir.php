<?
require_once('core/lib/dompdf/autoload.inc.php');

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

use Dompdf\Dompdf;

$dompdf = new Dompdf();

require_once('_con.php');
$pedido = $con->query('select * from tbl_pedido where id = '.$_GET['print'])->fetch_assoc();
$empresa = $con->query('select * from tbl_configuracao')->fetch_assoc();
$cliente = $con->query('select * from tbl_clientes where id = '.$pedido['cliente'])->fetch_assoc();

$file = '
<html>
<head>
    <link href="./main.css" rel="stylesheet">
    <link href="./assets/css/print.css" rel="stylesheet">
</head>
<body>

    <style>
        .linha{
            border-color: black;
            border-bottom-style: solid;
            border-bottom-width: 2px;
        }
        
    </style>

    <div class="content p-2">
        
        <div class="row linha border-bottom pb-1">
            <div class="col">
                Pedido <strong>#<span>'.str_pad($_GET['print'],5,0,STR_PAD_LEFT).'</span></strong>
            </div>
            <div class="col text-right">
                Emissão: <strong><span>'.date('d/m/Y',strtotime($pedido['data'])).'</span></strong> - <strong><span>'.$pedido['hora'].'</span></strong>
            </div>
        </div>

        <div class="row linha pt-1 pb-1">
            <div class="col">
                Impresso em <span>'.date('d/m/Y').'</span>
            </div>
        </div>

        <div class="row linha pt-3">
            <!--<div class="col-4">
                <img src="" alt="">
            </div>-->
            <div class="col pb-2">
                <strong>
                    <span>'.ucfirst($empresa['razao_social']).'</span><br>
                    <span>'.ucfirst($empresa['cnpj']).'</span><br>
                    <span>'.ucfirst($empresa['telefone']).'</span><br>
                    <span>'.$empresa['endereco'].', '.$empresa['numero'].', '.$empresa['bairro'].', '.$empresa['cep'].', '.$empresa['cidade'].'-'.$empresa['estado'].'</span>
                    <br><br>
                    Cliente: <span>'.$cliente['razaoSocial_nome'].'</span><br>
                    Endereço: <span>'.$cliente['logradouro'].', '.$cliente['bairro'].', '.$cliente['cep'].', '.$cliente['cidade'].'-'.$cliente['estado'].'</span>
                </strong>
            </div>
        </div>';

if($pedido['condicao'] != ''){
    $file .= '
        <div class="row linha pt-3">
            <div class="col pb-2">
            <strong>Condições de pagamento: </strong><span>'.$pedido['condicao'].'</span>
            </div>
        </div>
    ';
}

$file .= '<div class="row linha">
            <div class="col">
                <table class="table">
                    <tr>
                        <th>Produto</th>
                        <th>Referência</th>
                        <th>Quantidade</th>
                        <th>UN</th>
                        <th>Preço bruto</th>
                        <th>Subtotal</th>
                    </tr>            
                ';
                            $resp = $con->query('select * from tbl_pedidoItem where pedido = '.$_GET['print']);
                            
                            $filew = '';
                            $qtdItens = 0;
                            $totalBruto = 0;
                            while($item = $resp->fetch_assoc()){
                                $produto = $con->query('select * from tbl_produtos where id = '.$item['produto'])->fetch_assoc();
                                $unidadeEstoque = $con->query('select simbolo from tbl_unidades where id = '.$produto['unidadeEstoque'])->fetch_assoc();

                                $file .= '
                                    <tr>
                                        <td>'.$produto['nome'].'</td>
                                        <td>'.$produto['referencia'].'</td>
                                        <td>'.$item['quantidade'].'</td>
                                        <td>'.$unidadeEstoque['simbolo'].'</td>
                                        <td>'.number_format($produto['valor'],2,',','.').'</td>
                                        <td>'.number_format($produto['valor']*$item['quantidade'],2,',','.').'</td>
                                    </tr>
                                ';

                                $qtdItens++;
                                $totalBruto += floatval($produto['valor']*$item['quantidade']);
                            }
                        
$file .=           '
                </table>
            </div>
        </div>

    </div>

</body>
</html>
';

/*

<br><br><br><br>
<div class="row">
    <div class="col text-center">
        <span>___________________________________ , _______/_______/_______</span><br>
        '.$cliente['razaoSocial_nome'].'
    </div>
</div>

*/

#ob_start();
#include 'ven-pedidoImpressao.php';
#$file = ob_get_clean();

/*$opts = array('http' =>
    array(
        'method'  => 'GET',
        'content' => http_build_query(array('print' => $_GET['print']))
    )
);

$file = file_get_contents('ven-pedidoImpressao.php',false,stream_context_create($opts));*/

#var_dump($file);

$dompdf->loadHtml($file);

$dompdf->setPaper('A4');

$dompdf->render();
$dompdf->stream();
?>