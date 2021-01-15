<?
    require_once('../../../functions.php');
    require_once('../../lib/juno/class.php');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            if(isset($_GET['produto'])){
                $resp = $con->query('
                    select a.nome,a.patrimonio,a.serie from tbl_produtos a 
                    where a.id = '.$_GET['produto']
                );
                $produto = $resp->fetch_assoc();
            }
            elseif(isset($_GET['cliente'])){
                $resp = $con->query('
                    select b.id,b.patrimonio from tbl_contratoLocacaoMovEquipamentos a
                    right join tbl_produtos b on b.id = a.produto 
                    where a.cancelamento is null and a.cliente = '.$_GET['cliente'].'
                    group by a.produto
                ');
                while($row = $resp->fetch_assoc()){
                    echo '<option value="'.$row['id'].'">'.$row['patrimonio'].'</option>';
                }
            }
            elseif(isset($_GET['ncliente'])){
                $resp = $con->query('
                    select b.id,b.patrimonio from tbl_contratoLocacaoMovEquipamentos a
                    right join tbl_produtos b on b.id = a.produto 
                    where a.cancelamento is not null and a.cliente = '.$_GET['ncliente'].'
                    group by a.produto
                ');
                while($row = $resp->fetch_assoc()){
                    echo '<option value="'.$row['id'].'">'.$row['patrimonio'].'</option>';
                }
            }
        break;
    }
?>