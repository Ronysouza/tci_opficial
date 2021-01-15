<?
    require_once('../../../functions.php');
    require_once('../../lib/juno/class.php');
    
    if(isset($_POST['estado']))
        $resp = $con->query('select id,nome from tbl_municipios where idUF = "'.strtoupper($_POST['estado']).'"');
    elseif(isset($_POST['cidade']))
        $resp = $con->query('select id,nome from tbl_municipios where nome = '.$_POST['cidade'].'');

    foreach($resp as $row){
        echo '<option value="'.$row['id'].'-'.$row['nome'].'">'.utf8_encode($row['nome']).'</option>';
    }

?>