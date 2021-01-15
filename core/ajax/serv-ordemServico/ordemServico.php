<?
    require_once('../../../functions.php');
    require_once('../../lib/juno/class.php');

    #ini_set('display_errors',1);
    #ini_set('display_startup_erros',1);
    #error_reporting(E_ALL);

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $resp = $con->query('
                select a.nome,a.patrimonio,a.serie from tbl_produtos a 
                where a.id = '.$_GET['produto']
            );

            $produto = $resp->fetch_assoc();

echo 'Contrato: 
Equipamento: '.$produto['nome'].'
N° Patrimônio: '.$produto['patrimonio'].' N° Série: '.$produto['serie'].'
Local de instalação: 
Medidor: 
Última medição: Quantidade:
';
            
        break;
        /*case 'POST':
            
        break;*/
    }
?>