<?
    require_once('../../../functions.php');
    require_once('../../lib/juno/class.php');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $resp = $con->query('
                select id,contrato from tbl_contratoLocacao 
                where cliente = '.$_GET['cliente']
            );
            while($row = $resp->fetch_assoc()){
                echo '<option value="'.$row['contrato'].'">'.$row['contrato'].'</option>';
            }
            
        break;
    }
?>