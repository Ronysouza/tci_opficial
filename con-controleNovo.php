<?php include('header.php'); ?>

<?php
    if($_POST['cmd'] == "add"){
        $con->autocommit(false);
        $empresa = $_COOKIE['empresa'];
        
        for($i = 0; $i < sizeof($_POST['count']); $i++){
            $query = 'INSERT INTO `tbl_contratoLocacaoMedidores`(`empresa`, `contrato`, `produto`, `instalacao`, `medicao`, `qtdMedicao`, `qtdAcrescimo`, `qtdDesconto`, `qtdEfetiva`, `observacao`, `qtdMedicaoAnterior`) VALUES (
                "'.$empresa.'",
                "'.$_POST['contrato'][$i].'",
                "'.$_POST['produto'][$i].'",
                "'.$_POST['instalacao'][$i].'",
                "'.$_POST['medicao'][$i].'",
                "'.$_POST['qtdMedicao'][$i].'",
                NULL,
                NULL,
                "'.($_POST['qtdMedicao'][$i]-$_POST['qtdMedicaoAnterior'][$i]).'",
                "'.$_POST['observacao'][$i].'",
                "'.$_POST['qtdMedicaoAnterior'][$i].'"
            )';
            $con->query($query);
            if($con->error != '') echo "<script>location.href='?e'</script>";
        }
        if(!$con->commit()) {
            echo "<script>location.href='?e'</script>";
        }
        else{
            echo "<script>location.href='con-controle.php?s'</script>";
        }
    }

    if(isset($_GET['cliente'])){
        $ultimoCorte = isset($_GET['uc'])? $_GET['uc'] : $con->query('select b.medicao from tbl_contratoLocacao a
            inner join tbl_contratoLocacaoMedidores b on b.contrato = a.contrato 
            where cliente = '.$_GET['cliente'].'
            order by b.id desc    
        ')->fetch_assoc()['medicao'];

        $proximoCorte = isset($_GET['pc'])?$_GET['pc'] : date('Y-m-d',strtotime($ultimoCorte.' + 1 month'));
    }
?>
<script>
    function filtrarData(){
        location.href = "con-controleNovo.php?cliente=<?=$_GET['cliente']?>&uc="+$('#dataInicio').val()+"&pc="+$('#dataFim').val();
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

            <form method="POST">
                <input type="hidden" name="cmd" value="add">

                <div class="row">
                    <div class="col">
                        <a href="con-controle.php" class="btn btn-info mb-3"><i class="fas fa-chevron-left"></i></a>
                    </div>
                    <div class="col">
                        <button class="btn btn-success float-right"><span><i class="fas fa-check"></i></span>&ensp;<strong>Finalizar</strong></button>
                    </div>
                </div>

                <div class="card main-card mb-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <select class="form-control select2" onchange="location.href='?cliente='+$(this).val()">
                                    <option value="0">Selecione</option>
                                    <?
                                        $resp = $con->query('select b.id,b.razaoSocial_nome as nome from tbl_contratoLocacao a
                                            inner join tbl_clientes b on b.id = a.cliente
                                            group by a.cliente
                                        ');

                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'" '.($_GET['cliente'] == $row['id']?'selected':'').'>'.$row['id'].' - '.$row['nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <input type="text" class="mb-2 form-control" placeholder="Pesquisar" id="campoPesquisa">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <div class="input-group w-25">
                                    <input type="date" id="dataInicio" class="form-control" value="<?=$ultimoCorte;?>">
                                    <input type="date" id="dataFim" class="form-control" value="<?=$proximoCorte?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-info text-white" onclick="filtrarData()"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table mb-0 table-striped table-hover" id="tablePrint">
                            <thead >
                                <tr>
                                    <th style="width:2%"></th>
                                    <th>Dia vencimento</th>
                                    <th>Patrimônio</th>
                                    <th>Data de medição</th>
                                    <th>Contador anterior</th>
                                    <th>Contador atual</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($_GET['cliente']) && $_GET['cliente'] != 0){

                                        $resp = $con->query('select a.*,b.*,c.nome as equipamento,c.patrimonio,d.produto as idEquipamento from tbl_contratoLocacaoMovEquipamentos d
                                            left join tbl_contratoLocacaoEquipamentos a on a.contrato = d.contrato and a.produto = d.produto
                                            inner join tbl_contratoLocacao b on b.contrato = d.contrato
                                            left join tbl_produtos c on c.id = d.produto
                                            where d.cancelamento is null and b.dataFim > now() and d.cliente = '.$_GET['cliente'].'
                                            group by d.contrato,d.produto
                                            order by d.contrato
                                        ');

                                        
                                        $doc = json_decode(utf8_decode(file_get_contents('http://35.226.232.212:8080/docmps/contador/listarContadores/webservice/uniserv@2019/uniserv/'.$_GET['cliente'].'/'.date('dmY',strtotime($ultimoCorte)).'/'.date('dmY',strtotime($proximoCorte)))));

                                        $contador = array();
                                        foreach($doc as $linha){
                                            if($linha->descricao == 'life'){
                                                $contador[$linha->patrimonio] = $linha->contador;
                                            }
                                        }

                                        $grupoNome = '';
                                        $cont = 1;
                                        while($row = $resp->fetch_assoc()){
                                            $med = $con->query('select qtdMedicao,instalacao,medicao from tbl_contratoLocacaoMedidores where contrato = "'.$row['contrato'].'" and produto = "'.$row['idEquipamento'].'" order by id desc')->fetch_assoc();                                        
                                            
                                            $instalacao = $med['instalacao'];
                                            $medidor = $med['qtdMedicao'];
                                            $dataMedicao = $med['medicao'];
                                            
                                            echo '
                                                <tr>
                                                    <td>'.str_pad($cont++,3,"0",STR_PAD_LEFT).'</td>
                                                    <td>'.$row['diaVencimento'].'</td>
                                                    <td><strong>'.$row['patrimonio'].'</strong></td>
                                                    <td>'.date('d / m / Y',strtotime($dataMedicao)).'</td>
                                                    <td>'.$medidor.'</td>
                                                    <td>
                                                        <input type="hidden" name="count[]" value="1">
                                                        <input type="hidden" name="contrato[]" value="'.$row['contrato'].'">
                                                        <input type="hidden" name="produto[]" value="'.$row['idEquipamento'].'">
                                                        <input type="hidden" name="instalacao[]" value="'.$instalacao.'">
                                                        <input type="text" name="qtdMedicao[]" value="'.($contador[$row['patrimonio']] | 0).'" class="form-control w-50">
                                                        <input type="hidden" name="qtdMedicaoAnterior[]" value="'.$medidor.'">
                                                        <input type="hidden" name="medicao[]" value="'.date('Y-m-d').'">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="observacao[]" value="" class="form-control" maxlength="100">
                                                    </td>
                                                </tr>
                                            ';
                                        }
                                    }
                                    else{
                                        echo '<tr><td colspan="8" class="text-center text-muted"><strong>Selecione o cliente</strong></td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </form>

        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>

<!-- modal -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo cliente</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_unidades where id = '.$_GET['edt']);
                            $un = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

                    <div class="row">
                        <div class="col">
                            <label for="nome">Nome da unidade<span class="ml-2 text-danger">*</span></label>
                            <input type="text" value="<?php echo $un['nome']; ?>" class="form-control mb-3" name="nome" id="nome" maxlength="60" required>
                        </div>
                        <div class="col-2">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" <?php echo $un['status'] == 1?'selected':'';?>>Ativo</option>
                                <option value="0" <?php echo $un['status'] == 0?'selected':'';?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="divider mb-3"></div>

                    <label>Grupo da unidade</label>
                    <div class="row">
                        <div class="col">
                            <input type="text" placeholder="Nome do grupo" value="<?php echo $un['grupoNome'];?>" class="form-control mb-3" name="grupoUnidadeNome" id="grupoUnidadeNome" maxlength="60">
                        </div>
                        <div class="col-5">
                            <select class="form-control mb-3" id="grupoUnidade" onchange="selecionaGrupo(this)">
                                <option selected></option>
                                <?php
                                    $resp = $con->query('select grupoNome from tbl_unidades group by grupoNome order by grupoNome');
                                    
                                    while($row = $resp->fetch_assoc()){

                                        echo '<option value="'.$row['grupoNome'].'">'.$row['grupoNome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="divider mb-3"></div>

                    <div class="row">
                        <div class="col">
                            <label for="simbolo">Símbolo<span class="ml-2 text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" name="simbolo" id="simbolo" value="<?php echo $un['simbolo'];?>" maxlength="4" required>
                        </div>
                        <div class="col">
                            <label for="unBase">Unidade base</label>
                            <select class="form-control mb-3" name="unBase" id="unBase">
                                <option value="1" <?php echo $un['base'] == 1?'Selected':'';?>>Sim</option>
                                <option value="0" <?php echo $un['base'] == 0?'Selected':'';?>>Não</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="convUnBase">Conversão para unid. base<span class="ml-2 text-danger">*</span></label>
                            <input type="number" class="form-control mb-3" name="convUnBase" id="convUnBase" value="<?php echo isset($_GET['edt'])?$un['convBase']:'';?>" required>
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
            <div class="modal-footer">
                <p class="text-start"><span class="ml-2 text-danger">*</span> Campos obrigatórios</p>
                <button type="button" class="btn btn-secondary" onclick="location.href='?'">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('needs-validation').click();"><?php echo isset($_GET['edt'])? 'Atualizar':'Salvar';?></button>
            </div>
        </div>
    </div>
</div>
<!-- fim modal -->

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