<?php require('header.php');  

 
    $resp = $con->query('select pagamentoStatus from tbl_configuracao where id = '.$_COOKIE['empresa']);
    $flag = $resp->num_rows > 0;

    switch($_POST['cmd']){
        case 'saque':
            $rJuno = $juno->saque($_POST['saque']);
            if(isset($rJuno->details[0]->message)){
                echo "<script>alert('".$rJuno->details[0]->message."')</script>";
            }
        break;
    }

    $balanco = $juno->balanco();

if($flag && $resp->fetch_assoc()['pagamentoStatus'] == '1' && !is_numeric($_CONF['juno']['status'])):
?>


<div class="mb-3 widget-content w-25 btn btn-success text-left shadow rounded" onclick="$('#mdl-retirar').modal()">
    <div class="text-white">
        <div class="widget-content-left">
            <div class="widget-heading"><h4><strong>Total a receber</strong></h4></div>
        </div>
        <div class="">
            <a class="text-light"><div class="widget-numbers text-white"><span>R$ <?=$balanco->balance;?></span></div></a>
        </div>
    </div>
</div>
<?elseif(is_numeric($_CONF['juno']['status'])):?>
<div class="mb-3 widget-content w-25 bg-light text-left shadow rounded">
    <div class="">
        <div class="">
            <div class=""><strong>Erro ao encontrar a conta de pagamento!</strong><br> verifique sua conta <a href="configuracao.php?p=pagamento" class="">clicando aqui</a></div>
        </div>
    </div>
</div>
<?php endif;?>


<?php include('footer.php');?>

<!-- MODAIS --> 
"
<div class="modal" id="mdl-retirar">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-body">
        
        <div class="row">
            <div class="col text-center">
                <h4><strong>Retirar valor</strong></h4>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col text-center">
                <form method="POST">
                    <b>Valor liberado para saque </b><strong><span class="badge badge-info">R$ <?=number_format($balanco->transferableBalance,2,',',' ')?></span></strong>
                    <div class="input-group mt-3">
                        <input type="hidden" name="cmd" value="saque">
                        <input type="number" name="saque" class="form-control" value="<?=$balanco->transferableBalance;?>" min="0" max="<?=$balanco->transferableBalance;?>" step="0.01">
                        <div class="input-group-append">
                            <button class="btn btn-dark"><i class="fas fa-money-bill-wave"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        

      </div>

    </div>
  </div>
</div>