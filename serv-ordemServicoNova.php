<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        switch($_POST['tipoOS']){
            # GERAL
            case '0':
                $query = 'insert into tbl_ordemServico(cliente,descricao,solicitacao,prevEntrega,status,tipoOS) values(
                    '.(isset($_POST['cliente'])?$_POST['cliente']:0).',
                    "'.(isset($_POST['descricao'])?$_POST['descricao']:"").'",
                    "'.(isset($_POST['solicitacao'])?$_POST['solicitacao']:date('Y-m-d !')).'",
                    "'.(isset($_POST['previsao'])?$_POST['previsao']:date('Y-m-d !',strtotime(date('Y-m-d').' + 1 week'))).'",
                    '.(isset($_POST['status'])?$_POST['status']:1).',
                    '.($_POST['tipoOS']).'
                )';
                var_dump($_POST,'<br><br>',$query);
                $con->query($query);
                redirect($con->error);
            break;
            # INSTALAÇÃO
            case '1':
                $query = 'insert into tbl_ordemServico(cliente,descricao,solicitacao,prevEntrega,status,tipoOS,patrimonio) values(
                    '.(isset($_POST['cliente'])?$_POST['cliente']:0).',
                    "'.(isset($_POST['descricao'])?$_POST['descricao']:"").'",
                    "'.(isset($_POST['solicitacao'])?$_POST['solicitacao']:date('Y-m-d !')).'",
                    "'.(isset($_POST['previsao'])?$_POST['previsao']:date('Y-m-d !',strtotime(date('Y-m-d').' + 1 week'))).'",
                    '.(isset($_POST['status'])?$_POST['status']:1).',
                    '.($_POST['tipoOS']).',
                    '.($_POST['substituicaoPatrimonio']).'
                )';
                var_dump($_POST,'<br><br>',$query);
                $con->query($query);
                $query = 'insert into tbl_contratoLocacaoMovEquipamentos(empresa,contrato,produto,cliente,instalacao,localizacao) values(
                    '.$_COOKIE['empresa'].',
                    "'.$_POST['contrato'].'",
                    "'.$_POST['patrimonio'].'",
                    "'.$_POST['cliente'].'",
                    "'.$_POST['previsao'].'",
                    "'.$_POST['localizacao'].'"
                )';
                $con->query($query);
                redirect($con->error);
            break;
            # CANCELAMENTO
            case '2':
                $query = 'insert into tbl_ordemServico(cliente,descricao,solicitacao,prevEntrega,status,tipoOS,patrimonio) values(
                    '.(isset($_POST['cliente'])?$_POST['cliente']:0).',
                    "'.(isset($_POST['descricao'])?$_POST['descricao']:"").'",
                    "'.(isset($_POST['solicitacao'])?$_POST['solicitacao']:date('Y-m-d !')).'",
                    "'.(isset($_POST['previsao'])?$_POST['previsao']:date('Y-m-d !',strtotime(date('Y-m-d').' + 1 week'))).'",
                    '.(isset($_POST['status'])?$_POST['status']:1).',
                    '.($_POST['tipoOS']).',
                    '.($_POST['patrimonio']).'
                )';
                var_dump($_POST,'<br><br>',$query);
                $con->query($query);
                $query = 'update tbl_contratoLocacaoMovEquipamentos set cancelamento = "'.$_POST['previsao'].'" where cancelamento is null and  produto = "'.$_POST['patrimonio'].'")';
                $con->query($query);
                redirect($con->error);
            break;
            # SUBSTITUIR
            case '3':
                $query = 'insert into tbl_ordemServico(cliente,descricao,solicitacao,prevEntrega,status,tipoOS,patrimonio,substituicao) values(
                    '.(isset($_POST['cliente'])?$_POST['cliente']:0).',
                    "'.(isset($_POST['descricao'])?$_POST['descricao']:"").'",
                    "'.(isset($_POST['solicitacao'])?$_POST['solicitacao']:date('Y-m-d !')).'",
                    "'.(isset($_POST['previsao'])?$_POST['previsao']:date('Y-m-d !',strtotime(date('Y-m-d').' + 1 week'))).'",
                    '.(isset($_POST['status'])?$_POST['status']:1).',
                    '.($_POST['tipoOS']).',
                    '.($_POST['patrimonio']).',
                    '.($_POST['substituicaoPatrimonio']).'

                )';
                $con->query($query);
                $query = 'insert into tbl_contratoLocacaoMovEquipamentos(empresa,contrato,produto,cliente,instalacao,localizacao) values(
                    '.$_COOKIE['empresa'].',
                    "'.$_POST['contrato'].'",
                    "'.$_POST['substituicaoPatrimonio'].'",
                    "'.$_POST['cliente'].'",
                    "'.$_POST['previsao'].'",
                    "'.$_POST['localizacao'].'"
                )';
                $con->query($query);
                $query = 'update tbl_contratoLocacaoMovEquipamentos set cancelamento = "'.$_POST['previsao'].'" where cancelamento is null and  produto = "'.$_POST['patrimonio'].'")';
                $con->query($query);
                redirect($con->error);
            break;
        }
    }
    elseif($cmd == "edt"){
        $query = 'update tbl_ordemServico set
            cliente = '.$_POST['cliente'].',
            descricao = "'.$_POST['descricao'].'",
            solicitacao = "'.$_POST['solicitacao'].'",
            prevEntrega = "'.$_POST['previsao'].'",
            status = '.$_POST['status'].'
            where id = '.$_POST['id'].'
        ';
        $con->query($query);
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_ordemServico set status = -1 where id = '.$_GET['del']);
    redirect($con->error);
}

?>
<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Clientes Cadastrados</h5>');
        newWin.document.write(divPrint.outerHTML);
        //await new Promise(r => setTimeout(r, 150));
        //newWin.print();
        //newWin.close();
    }

    function selectPatrimonio(self){
       /*if($(self).val() != 0){
            $('#linhaComplementar').show();
            $.get('core/ajax/serv-ordemServico/ordemServico.php?produto='+$(self).val(),function(resp){
                $('#complementar').text(resp);
            }).catch(err => console.log('err:',err));
        }
        else{
            $('#linhaComplementar').hide();
        }*/
    }

    function tipoos(self){
        tipo = $(self).val();
        switch(tipo){
            case '0':
                $('#divPatrimonio').hide();
                $('#divSetaSubstituicao').attr('style','display:none');
                $('#divSubstituicao').hide();
                $('#linhaComplementar').hide();
                $('#divContrato').hide();
                $('#divLocalizacao').hide();
                break;
            case '1':
                $('#divPatrimonio').hide();
                $('#divSetaSubstituicao').attr('style','display:none');
                $('#divSubstituicao').show();
                $('#linhaComplementar').show();
                $('#divContrato').show();
                $('#divLocalizacao').show();
                break;
            case '2':
                $('#divPatrimonio').show();
                $('#divSetaSubstituicao').attr('style','display:none');
                $('#divSubstituicao').hide();
                $('#linhaComplementar').show();
                $('#divContrato').hide();
                $('#divLocalizacao').hide();
                break;
            case '3':
                $('#divPatrimonio').show();
                $('#divSetaSubstituicao').attr('style','display:flex');
                $('#divSubstituicao').show();
                $('#linhaComplementar').show();
                $('#divContrato').hide();
                $('#divLocalizacao').hide();
                break;
            default:
                
                break;
        }
    }

    function selectCliente(self){
        $.get('core/ajax/serv-ordemServico/produto.php?cliente='+$(self).val(),function(resp){
            $('#patrimonio').empty();
            $('#patrimonio').append('<option value="0">Selecione</option>');
            $('#patrimonio').append(resp);
        }).catch(err => console.log(err));
        $.get('core/ajax/serv-ordemServico/produto.php?ncliente='+$(self).val(),function(resp){
            $('#substituicaoPatrimonio').empty();
            $('#substituicaoPatrimonio').append('<option value="0">Selecione</option>');
            $('#substituicaoPatrimonio').append(resp);
        }).catch(err => console.log(err));
        $.get('core/ajax/serv-ordemServico/contrato.php?cliente='+$(self).val(),function(resp){
            $('#contrato').empty();
            $('#contrato').append('<option value="0">Selecione</option>');
            $('#contrato').append(resp);
        }).catch(err => console.log(err));
    }

    $(document).ready(function(){
        $("#campoPesquisa").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tablePrint tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<!-- conteúdo -->
<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="row mb-3">
                <div class="col">
                    <a href="serv-ordemServico.php" class="btn btn-info"><i class="fas fa-chevron-left"></i></a>
                </div>
                <div class="col">
                    <span class="btn btn-success float-right" onclick="$('#formOrdemServicoNova').submit()">Salvar</span>
                </div>
            </div>

            <div class="card main-card mb-3">
                <div class="card-body">

                    <form method="post" id="formOrdemServicoNova">

                        <?php
                            if(isset($_GET['edt'])){
                                $resp = $con->query('select * from tbl_ordemServico where id = '.$_GET['edt']);
                                $ordemServico = $resp->fetch_assoc();
                            }
                        ?>

                        <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                        <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

                        <div class="row">
                            <div class="col">
                                <label for="cliente">Cliente<span class="ml-2 text-danger">*</span></label>
                                <select class="form-control mb-3 select2" name="cliente" id="cliente" onchange="selectCliente(this)" required>
                                    <option <?php echo isset($_GET['edt'])? '':'selected';?> disabled>Selecione o cliente</option>
                                    <?php
                                        $resp = $con->query('select id, razaoSocial_nome from tbl_clientes where tipoCliente="on"');
                                        while($row = $resp->fetch_assoc()){
                                            $selected = $ordemServico['cliente'] == $row['id']? 'selected':'';
                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['razaoSocial_nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="status">Status</label>
                                <select class="form-control mb-3" name="status" id="status">
                                    <option value="1" <?php echo $ordemServico['status'] == '2' || !isset($_GET['edt'])? 'selected':'';?>>Aguardando</option>
                                    <option value="2" <?php echo $ordemServico['status'] == '2'? 'selected':'';?>>Em andamento</option>
                                    <option value="3" <?php echo $ordemServico['status'] == '3'? 'selected':'';?>>Aguardando aprovação</option>
                                    <option value="4" <?php echo $ordemServico['status'] == '4'? 'selected':'';?>>Finalizado</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="tipoOS">Tipo O.S.</label>
                                <select name="tipoOS" id="tipoOS" class="form-control" onchange="tipoos(this)">
                                    <option value="0" <?php echo $ordemServico['tipoOS'] == '0' || !isset($_GET['edt'])? 'selected':'';?>>Geral</option>
                                    <option value="1" <?php echo $ordemServico['tipoOS'] == '1'? 'selected':'';?>>Instalação</option>
                                    <option value="2" <?php echo $ordemServico['tipoOS'] == '2'? 'selected':'';?>>Cancelamento</option>
                                    <option value="3" <?php echo $ordemServico['tipoOS'] == '3'? 'selected':'';?>>Substituição</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="solicitacao">Data de Solicitação</label>
                                <input type="date" value="<?php echo $ordemServico['solicitacao'];?>" class="form-control mb-3" name="solicitacao" id="solicitacao" required>
                            </div>
                            <div class="col">
                                <label for="previsao">Previsão de entrega</label>
                                <input type="date" value="<?php echo $ordemServico['prevEntrega'];?>" class="form-control mb-3" name="previsao" id="previsao" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col" id="divPatrimonio" style="display:none">
                                <label for="patrimonio">Patrimônio</label>
                                <select class="form-control select2" name="patrimonio" id="patrimonio" onchange="selectPatrimonio(this)">
                                    <option value="0">Selecione</option>
                                </select>
                            </div>
                            <div class="col-1" id="divSetaSubstituicao" style="display:none;">
                                <span class="m-auto text-info" style="font-size:28px"><i class="fas fa-angle-double-right"></i></span>
                            </div>
                            <div class="col" id="divSubstituicao" style="display:none">
                                <label for="substituicao">Patrimônio</label>
                                <select class="form-control select2" name="substituicaoPatrimonio" id="substituicaoPatrimonio" onchange="selectPatrimonio(this)">
                                    <option value="0">Selecione</option>
                                </select>
                            </div>
                            <div class="col" id="divContrato" style="display:none">
                                <label for="contrato">Contrato</label>
                                <select class="form-control select2 w-25" name="contrato" id="contrato">
                                    <option value="0">Selecione</option>
                                </select>
                            </div>
                            <div class="col-4" id="divLocalizacao" style="display:none">
                                <label for="localizacao">Localização</label>
                                <input type="text" class="form-control" name="localizacao" id="localizacao">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control mb-3" name="descricao" id="descricao" rows="4" style="resize:none;" required><?php echo $ordemServico['descricao'];?></textarea>
                            </div>
                        </div>

                        <div class="row" id="linhaComplementar" style="display:none;">
                            <div class="col">
                                <label for="complementar">Complementar</label>
                                <textarea class="form-control mb-3" name="complementar" id="complementar" rows="6" style="resize:none;"><?php echo $ordemServico['complementar'];?></textarea>
                            </div>
                        </div>

                        <input id="needs-validation" class="d-none" type="submit" value="enviar">

                    </form>
                    <script>
                    $(document).ready(function(){
                        $("#cpfResponsavel").mask('000.000.000-00', {reverse: true});
                        $("#telefoneEmpresa").mask('(99) 99999-9999');
                        $("#telefoneWhatsapp").mask('(99) 99999-9999');
                        $("#cep").mask('99999-999');
                    });
                    </script>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>