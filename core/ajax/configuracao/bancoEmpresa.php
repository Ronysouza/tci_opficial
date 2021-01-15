<?
    require_once('../../../functions.php');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            echo json_encode($con->query('select * from tbl_banco where empresa = '.$_GET['id'])->fetch_assoc());
        break;
        case 'POST':
            $resp = $con->query('select id from tbl_banco where empresa = '.$_POST['empresa']);
            $compCaixa = isset($_POST['compCaixa']) && $_POST['compCaixa'] != ''?$_POST['compCaixa']:-1;
            if($resp->num_rows == 0){
                $query = 'insert into tbl_banco(banco,agencia,conta,responsavel,documento,empresa,complementoCaixa) values(
                    "'.$_POST['banco'].'",
                    "'.$_POST['agencia'].'",
                    "'.$_POST['conta'].'",
                    "'.$_POST['responsavel'].'",
                    "'.$_POST['documento'].'",
                    "'.$_POST['empresa'].'",
                    "'.$compCaixa.'"
                )';
                $con->query($query);
                echo $con->error == ""? true:false;
            }
            else{
                $query = 'update tbl_banco set
                    banco = "'.$_POST['banco'].'",
                    agencia = "'.$_POST['agencia'].'",
                    conta = "'.$_POST['conta'].'",
                    responsavel = "'.$_POST['responsavel'].'",
                    documento = "'.$_POST['documento'].'",
                    complementoCaixa = "'.$compCaixa.'"
                    where empresa = '.$_POST['empresa'].'
                ';
                $con->query($query);
                echo $con->error == ""? true:false;
            }
        break;
    }
?>