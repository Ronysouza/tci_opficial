<?
    require_once('../../../functions.php');
    require_once('../../lib/juno/class.php');

    #ini_set('display_errors',1);
    #ini_set('display_startup_erros',1);
    #error_reporting(E_ALL);

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $resp = $con->query('
                select a.*, b.nome, b.unidadeEstoque, b.estoque, c.simbolo from tbl_gradeProdutosItens a 
                inner join tbl_produtos b on b.id = a.item
                inner join tbl_unidades c on c.id = b.unidadeEstoque 
                where a.grade = '.$_GET['grade']
            );
            while($row = $resp->fetch_assoc()){
                $estFinal = $row['estoque'] - $row['fator'];
                
                if($estFinal < 0){
                    $bgEst = "danger";
                }
                elseif($estFinal == 0){
                    $bgEst = "warning";
                }
                else{
                    $bgEst = "info";
                }

                echo '
                    <tr>
                        <td>'.$row['id'].'</td>
                        <td>'.$row['nome'].'</td>
                        <td>'.$row['simbolo'].'</td>
                        <td>'.$row['estoque'].'</td>
                        <td fator="'.$row['fator'].'">'.$row['fator'].'</td>
                        <td><span class="badge badge-'.$bgEst.'">'.$estFinal.'</span></td>
                    </tr>
                ';
            }
        break;
        /*case 'POST':
            
        break;*/
    }
?>