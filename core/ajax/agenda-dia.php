<?php
    if(false){
        ini_set('display_errors',1);
        ini_set('display_startup_erros',1);
        error_reporting(E_ALL);
    }

    if(isset($_GET['dia'])){
        $adicionar = $_GET['ano'].'-'.$_GET['mes'].'-'.$_GET['dia'];
        require_once('../../functions.php');
    }
    else{
        $adicionar = $nAno.'-'.$nMes.'-'.$nDia;
    }
?>
<a href="?novo&data=<?php echo $adicionar;?>">
    <button class="btn btn-info mb-2">
        <i class="far fa-calendar-plus mr-3"></i>
        <span class="float-right">Visualizar</span>
    </button>
</a>
<div id="tabelaDia" class="border">
    <table class="table table-bordered">
        <tbody>   
            <?php
                for($i = 1; $i < 25; $i++){
                    $meioDia = $i == 12 ? 'class="bg-light"':'';
                    $agenda2 = $con->query('select a.razaoSocial_nome as nome from tbl_agenda b inner join tbl_clientes a on b.responsavel = a.id where data="'.$adicionar.'" and hora like "'.$i.':%"');
                    $compromissos = "";
                    while($row = $agenda2->fetch_assoc()){
                        $compromissos .= '
                            <div class="row">
                                <div class="col mb-2 mt-2">
                                    '.$row['nome'].'
                                </div>
                            </div>
                        ';
                    }
                    echo '
                        <tr '.$meioDia.'>
                            <td style="width:10%">'.$i.':00</td>
                            <td>'.$compromissos.'</td>
                        </tr>
                    ';
                }
            ?>
        </tbody>
    </table>
</div>