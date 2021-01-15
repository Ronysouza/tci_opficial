<?php
    require_once('../../functions.php');

    $resp = $con->query('select id,nome from tbl_grupo where grupo = '.$_GET['grupo']);
    echo '<option selected disabled>Selecione</option>';
    while($row = $resp->fetch_assoc()){
        echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
    }
?>