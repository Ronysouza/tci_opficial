<?
    require_once('../../../functions.php');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            echo json_encode($con->query('select area,tipo,descricao,respLegal,docResp,dataResp from tbl_configuracao where id = '.$_GET['id'])->fetch_assoc());
        break;
        case 'POST':
            $query = 'update tbl_configuracao set 
                area = "'.$_POST['area'].'", 
                tipo = "'.$_POST['tipo'].'", 
                descricao = "'.$_POST['descricao'].'", 
                respLegal = "'.$_POST['respLegal'].'",
                docResp = "'.$_POST['docResp'].'",
                dataResp = "'.$_POST['aniversarioResp'].'"    
            where id = '.$_POST['empresa'];
            $con->query($query);
            echo $con->error == ""? true:false;
        break;
    }
?>