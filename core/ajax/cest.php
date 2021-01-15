<?php
    require_once('../../functions.php');

    $resp = $con->query('select cest,descricao from tbl_ncm_cest where ncm = "'.$_GET['ncm'].'"');
    $ret = [];
    while($row = $resp->fetch_assoc()){
        echo '<option value=\''.implode(' - ',$row).'\'>';
    }
?>