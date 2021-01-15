<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    switch($cmd){
        case 'add':
            $anterior = 0;
            $erro = false;
            if($_POST['pagamento']['habilitar'] == 1){
                $cliente = $con->query('select razaoSocial_nome,cnpj_cpf from tbl_clientes where id = '.$_POST['cliente'])->fetch_assoc();
                $pagamento = array(
                    "charge" => array(
                        "description" => $_POST['descricao'],
                        "dueDate" => $_POST['vencimento'],
                        "maxOverdueDays" => $_POST['pagamento']['overdueDays'],
                        "fine" => $_POST['pagamento']['multa'],
                        "interest" => $_POST['pagamento']['juros'],
                        "discountAmount" => $_POST['pagamento']['descontoValor'],
                        "discountDays" => $_POST['pagamento']['descontoDias'],
                        "paymentTypes" => array(
                            /*"BOLETO",*/
                            "CREDIT_CARD"
                        ),
                        "split" => array()
            
                    ),
                    "billing" => array(
                        "name" => $cliente['razaoSocial_nome'],
                        "document" => $cliente['cnpj_cpf']
                    )
                );

                if($_POST['parcela'] > 1){
                   $pagamento['charge']['installments'] = $_POST['parcela'];
                   $pagamento['charge']['totalAmount'] = $_POST['valor'];
                }
                else{
                    $pagamento['charge']['amount'] = $_POST['valor'];
                }
                
                $resp = $juno->criarCobrancas($pagamento);
                
                if(isset($resp->details)){
                    echo '<script>
                        alert("'.$resp->details[0]->message.'");
                        location.href="?";
                    </script>';
                }
                $cobranca = $resp->_embedded->charges;
            }
            else{
                $cobranca = false;
            }

            for($i = 0; $i < $_POST['parcela']; $i++){
                if($_POST['intervalo'] == '30'){
                    $dia = substr($_POST['vencimento'],8,2);
                    $data = strtotime(substr($_POST['vencimento'],0,7).'-01 + '.($i).' month');
                    $dVen = $dia > date('t',$data)?date('t',$data):$dia;
                    $data = date('Y-m-',$data).$dVen;
                }
                else{
                    $data = date('d/m/Y',strtotime($_POST['vencimento'].' + '.($i*$_POST['intervalo']).' day'));
                }
                if($cobranca){
                    $query = 'insert into tbl_contasReceber(valor,descricao,vencimento,status,cliente,nDocumento,parcela,intervalo,anterior,totalParcela,referenciaPagamento,checkoutUrl,installmentLink,boletos,empresa) values(
                        "'.$_POST['valor'].'",
                        "'.$_POST['descricao'].'",
                        "'.$cobranca[$i]->dueDate.'",
                        "'.$_POST['status'].'",
                        "'.$_POST['cliente'].'",
                        "'.$_POST['nDocumento'].'",
                        "'.($i+1).'",
                        "'.$_POST['intervalo'].'",
                        "'.$anterior.'",
                        "'.$_POST['parcela'].'",
                        "'.$cobranca[$i]->id.'",
                        "'.$cobranca[$i]->checkoutUrl.'",
                        "'.$cobranca[$i]->installmentLink.'",
                        "'.$cobranca[$i]->link.'",
                        "'.$_COOKIE['empresa'].'"
                    )';
                }
                else{
                    $query = 'insert into tbl_contasReceber(valor,descricao,vencimento,status,cliente,nDocumento,parcela,intervalo,anterior,totalParcela,empresa) values(
                        "'.$_POST['valor'].'",
                        "'.$_POST['descricao'].'",
                        "'.$data.'",
                        "'.$_POST['status'].'",
                        "'.$_POST['cliente'].'",
                        "'.$_POST['nDocumento'].'",
                        "'.($i+1).'",
                        "'.$_POST['intervalo'].'",
                        "'.$anterior.'",
                        "'.$_POST['parcela'].'",
                        "'.$_COOKIE['empresa'].'"
                    )';
                }
                $con->query($query);
                $anterior = $con->insert_id;
            }
            if($con->error == "" && $cobranca){
                echo '<script>location.href="fisc-contasReceber.php?edt&boletos='.urlencode($cobranca[0]->checkoutUrl).'&cartao='.$cobranca[0]->id.'&empresa='.$_COOKIE['empresa'].'"</script>';
            }
            else{
                echo '<script>location.href="fisc-contasReceber.php?s"</script>';
            }
        break;
    }
}

?>
<script>
    function calcValor(){
        $('#total').val(parseFloat(parseFloat($('#valor').val())*parseFloat($('#parcela').val())).toFixed(2));
    }

    function calcValorParcela(){
        $('#valor').val(parseFloat(parseFloat($('#total').val())/parseFloat($('#parcela').val())).toFixed(2));
    }

    $(document).ready(function(){
        $('.nav-link[data-target]').click(function(){
            $('.nav-link[data-target]').removeClass('active');
            $('.tab-pane').removeClass('active');
            $('.tab-pane').addClass('fade');
            $(this).addClass('active');
            $($(this).attr('data-target')).removeClass('fade');
            $($(this).attr('data-target')).addClass('active');
        });
    });
</script>

<!-- conteúdo -->
<div class="content container">

<?php
    if(isset($_GET['edt'])){
        $resp = $con->query('select * from tbl_contasReceber where id = '.$_GET['edt']);
        $un = $resp->fetch_assoc();
    }
?>


<h3><strong>Nova conta a receber</strong></h3>

<?if(isset($_GET['edt'])):?>
<div class="row mt-4 mb-4">
    <div class="col d-flex">
        <!--<a target="_blank" href="<?=$un['boletos']?>" class="btn btn-light ml-auto d-flex"><i class="fas fa-file-pdf m-auto"></i><span class="ml-2">Boletos</span></a>
        <a target="_blank" href="<?=$un['installmentLink']?>" class="btn btn-light ml-2 d-flex"><i class="fas fa-file-pdf m-auto"></i><span class="ml-2">Parcela</span></a>-->
        <a target="_blank" href="pagamento.php?e=<?=$un['empresa']?>&c=<?=$un['referenciaPagamento']?>" class="btn btn-light ml-auto d-flex"><i class="fas fa-credit-card m-auto"></i><span class="ml-2">Cartão</span></a>
    </div>
</div>
<?endif;?>
<form method="post">
    <div class="nav nav-tabs mb-0">
        <li class="nav-item">
            <a class="nav-link active" data-target="#geral">Geral</a>
        </li>
        <?if(!isset($_GET['edt'])):?>
        <li class="nav-item">
            <a class="nav-link"  data-target="#cobranca">Cobranca</a>
        </li>
        <?endif;?>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane active pt-3" id="geral">
            
            <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
            <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

            <div class="row mb-3">
                <div class="col">
                    <label for="cliente">Cliente<span class="ml-2 text-danger">*</span></label>
                    <select name="cliente" id="cliente" class="form-control" <?=isset($_GET['edt'])?'readonly':'';?>>
                        <?
                            $resp = $con->query('select id,razaoSocial_nome from tbl_clientes where tipoCliente = "on"');
                            while($row = $resp->fetch_assoc()){
                                echo '<option value="'.$row['id'].'" '.($un['cliente'] == $row['id']?'selected':'').'>'.$row['razaoSocial_nome'].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="nDocumento">N° Documento</label>
                    <input type="text" class="form-control" name="nDocumento" id="nDocumento" maxlength="20" value="<?=$un['nDocumento']?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="total">Total<span class="ml-2 text-danger">*</span></label>
                    <input type="text" value="<?=($un['valor'] * $un['totalParcela'])?>" class="form-control" name="valor" id="total" onchange="calcValorParcela()" <?=isset($_GET['edt'])?'readonly':'';?>>
                </div>   
                <div class="col">
                    <label for="parcela">Parcelas<span class="ml-2 text-danger">*</span></label>
                    <input type="number" step="1" min="1" value="<?=isset($un['parcela'])?$un['parcela']:'1';?>" class="form-control" name="parcela" id="parcela" required onchange="calcValorParcela()" <?=isset($_GET['edt'])?'readonly':'';?>>
                </div>
                <div class="col">
                    <label for="valor">Valor (parcela)</label>
                    <input type="number" step="0.01" value="<?=isset($un['valor'])?$un['valor']:'0.01';?>" min="0.01" class="form-control" id="valor" readonly>
                </div>             
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="vencimento">Vencimento<span class="ml-2 text-danger">*</span></label>
                    <input type="date" class="form-control" name="vencimento" id="vencimento" value="<?=$un['vencimento']?>" required <?=isset($_GET['edt'])?'readonly':'';?>>
                </div>
                <div class="col">
                    <label for="intervalo">Intervalo (dias)</label>
                    <input type="number" class="form-control" name="intervalo" id="intervalo" value="<?=isset($un['intervalo'])?$un['intervalo']:'30';?>" <?=/*isset($_GET['edt'])*/true?'readonly':'';?>>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="status">Status<span class="ml-2 text-danger">*</span></label>
                    <select name="status" id="status" class="form-control">
                            <option value="0" <?=$un['status'] == 0?'selected':'';?>>Em andamento</option>
                            <option value="1" <?=$un['status'] == 1?'selected':'';?>>Finalizado</option>
                            <option value="2" <?=$un['status'] == 2?'selected':'';?>>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="descricao">Descrição<span class="ml-2 text-danger">*</span></label>
                    <textarea name="descricao" id="descricao" rows="4" class="form-control" style="resize:none;" maxlength="200" required <?=isset($_GET['edt'])?'readonly':'';?>><?=$un['descricao']?></textarea>
                </div>
            </div>

        </div>
        <div class="tab-pane fade pt-3" id="cobranca">
            <?if(is_numeric($_CONF['juno']['status']) && $_CONF['debug'] == "1"):?>
                <div class="alert alert-danger">
                    <strong>Erro!</strong> Você não possui permissão <a href="configuracao.php?p=geral" class="alert-link">clique aqui</a> e desative o sistema de homologação.
                </div>
            <?elseif($_CONF['juno']['status'] == "AWAITING_DOCUMENTS"):?>
                <div class="alert alert-warning">
                    <strong>Atenção!</strong> Você precisa enviar os documentos <a href="configuracao.php?p=pagamento" class="alert-link">clicando aqui</a> para confirmar sua identidade.
                </div>
            <?elseif(is_numeric($_CONF['juno']['status'])):?>
                <div class="alert alert-danger">
                    <strong>Erro!</strong> Você não possui permissão <a href="configuracao.php?p=pagamento" class="alert-link">clique aqui</a> e verifique se todas as informações estão corretas.
                </div>
            <?endif;?>
            
            <div class="row">
                <div class="col-4">
                    <label for="pagamento[habilitar]">Habilitar cobrança</label>
                    <select name="pagamento[habilitar]" class="form-control" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                        <option value="0">Desativado</option>
                        <option value="1">Ativo</option>
                    </select>
                </div>
                <div class="col-4">
                    <label for="pagamento[liberacao]">Liberação adiantada do valor</label>
                    <select name="pagamento[liberacao]" class="form-control" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <label for="pagamento[over]">Dias permitidos após o vencimento</label>
                    <input type="number" name="pagamento[overdueDays]" min="0" class="form-control" value="0" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                </div>
                <div class="col">
                    <label for="pagamento[multa]">Multa %</label>
                    <input type="number" name="pagamento[multa]" min="0" max="20" step="0.01" class="form-control" value="0" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                </div>
                <div class="col">
                    <label for="pagamento[juros]">Juros %</label>
                    <input type="number" name="pagamento[juros]" min="0" max="20" step="0.01" class="form-control" value="0" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <label for="pagamento[descontoValor]">Valor de Desconto %</label>
                    <input type="number" name="pagamento[descontoValor]" min="0" max="20" step="0.01" class="form-control" value="0" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                </div>
                <div class="col">
                    <label for="pagamento[descontoDias]">Dias de Desconto</label>
                    <input type="number" name="pagamento[descontoDias]" min="-1" max="20" step="0.01" class="form-control" value="-1" <?=$_CONF['juno']['status']!='VERIFIED'?'disabled':''?>>
                </div>
            </div>

        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <a href="fisc-contasReceber.php" class="btn btn-dark"><i class="fas fa-chevron-left"></i></a>
            <input id="needs-validation" class="btn btn-success ml-3" type="submit" value="Finalizar">
        </div>
    </div>

</form>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>

<div id="toast-container" class="toast-top-center">
    <div id="toast-success" class="toast toast-success" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Sucesso!</div>
    </div>
    <div id="toast-error" class="toast toast-error hidden" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Erro!</div>
    </div>
    <?php
        if(isset($_GET['s']))
            echo "<script>loadToast(true);</script>";
        else if(isset($_GET['e']))
            echo "<script>loadToast(false);</script>";
    ?>
</div>

<?php if(isset($_GET['edt'])) echo "<script>$('#btn-modal').click()</script>"; ?>