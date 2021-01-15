
<?
require_once('../functions.php');

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

ini_set('max_execution_time', 1800);

/*
    * medidores
    * movimento
    produtos
*/

$arq = fopen('produtos.sql','r');

$con->autocommit(FALSE);


while(!feof($arq)){
    $query1 = utf8_encode(fgets($arq));
    if($query1[0] == "I"){
        $query = $query1.utf8_encode(fgets($arq));
        while($query[strlen($query)-4].$query[strlen($query)-3] != ');'){
            $buffer = utf8_encode(fgets($arq));
            $query .= $buffer;
        }
        $con->query($query);
        if($con->error != '')
            exit($con->error." >> ".$query);
    }
}

if (!$con->commit()) {
    echo "Commit transaction failed";
    exit();
}
else{
    echo "sucesso!";
}

?>