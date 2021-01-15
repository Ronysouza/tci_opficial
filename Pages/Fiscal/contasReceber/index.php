<?php 
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once($_COOKIE['ROOT'].'/includes/header.php');

?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $anterior = 0;
        //var_dump(date('d/m/y',strtotime(($_POST['parcela']*$_POST['intervalo']).' day')));
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
            $query = 'insert into tbl_contasReceber(valor,descricao,vencimento,status,cliente,nDocumento,parcela,intervalo,anterior,totalParcela) values(
                "'.$_POST['valor'].'",
                "'.$_POST['descricao'].'",
                "'.$data.'",
                "'.$_POST['status'].'",
                "'.$_POST['cliente'].'",
                "'.$_POST['nDocumento'].'",
                "'.($i+1).'",
                "'.$_POST['intervalo'].'",
                "'.$anterior.'",
                "'.$_POST['parcela'].'"
            )';
            $con->query($query);
            $anterior = $con->insert_id;
        }
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        $query = 'update tbl_contasReceber set
            nDocumento = "'.$_POST['nDocumento'].'",
            valor = "'.$_POST['valor'].'",
            vencimento = "'.$_POST['vencimento'].'",
            status = "'.$_POST['status'].'",
            descricao = "'.$_POST['descricao'].'"
            where id = '.$_POST['id'].'
        ';
        $con->query($query);
        redirect($con->error);
    }
}elseif(isset($_GET['conc'])){
    $con->query('update tbl_contasReceber set status = "1" where id = '.$_GET['conc']);
    redirect($con->error);

}elseif(isset($_GET['canc'])){
    $con->query('update tbl_contasReceber set status = "2" where id = '.$_GET['canc']);
    redirect($con->error);
}

if(isset($_GET['ind'])){
    $_COOKIE['inicioData'] = $_GET['ind'];
    $_COOKIE['fimData'] = $_GET['fid'];
}
if(isset($_GET['filtro'])){
    $_COOKIE['filtro'] = $_GET['filtro'];
}

?>
<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Unidades Cadastradas</h5>');
        newWin.document.write(divPrint.outerHTML);
    }

    function calcValor(){
        $('#total').val(parseFloat(parseFloat($('#valor').val())*parseFloat($('#parcela').val())).toFixed(2));
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

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-dollar-sign icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de contas a receber</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de contas a receber
                </div>
            </div>

        </div>
        <div class="page-title-actions">

            <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
            <i class="fas fa-plus"></i>
            </button>

            <div class="d-inline-block dropdown">
                <button class="btn-shadow dropdown-toggle btn btn-info" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Ações
                </button>
                <div class="dropdown-menu dropdown-menu-right" tabindex="-1" role="menu" x_placement="bottom-end">
                    <ul class="nav flex-column">
                        <li class="nav-item">

                            <a class="nav-link text-dark" onclick="imprimir()">
                                Imprimir
                            </a>
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=cfop">
                                Exportar
                            </a>
                        
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- fim cabeçalho-->

<!-- conteúdo -->
<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">

                    <h5 class="card-title">Contas a receber</h5>

                    <div class="row">
                        <div class="col">
                            <input type="text" class="mb-2 form-control" placeholder="Pesquisar" id="campoPesquisa">
                        </div>
                        <div class="col-4">
                            <div class="input-group">
                                <input type="date" class="form-control" id="inicioData">
                                <span class="input-group-text">a</span>
                                <input type="date" class="form-control" id="fimData">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-<?=$_COOKIE['fimData'] != ''?'danger':'info';?>" onclick="location.href='?ind='+$('#inicioData').val()+'&fid='+$('#fimData').val()"><?=$_COOKIE['fimData'] != ''?'Limpar':'Pesquisar';?></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <select id="filtro" class="form-control" onchange="location.href='?filtro='+$('#filtro').val();">
                                <option>Selecione</option>
                                <option value="-1">Todos</option>
                                <option value="0">Em abertos</option>
                                <option value="1">Pagos</option>
                                <option value="2">Cancelados</option>
                                <option value="3">Vencidos</option>
                            </select>
                        </div>
                    </div>

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Cliente</th>
                                <th>Descrição</th>
                                <th style="width:8%">Documento</th>
                                <th style="width:4%">Parcela</th>
                                <th style="width:6%">Valor</th>
                                <th style="width:8%">Validade</th>
                                <th style="width:6%">Status</th>
                                <th class="noPrint" style="width:6%"></th>
                                <th class="noPrint" style="width:6%"></th>
                                <th class="noPrint" style="width:6%"></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $filtro = '';
                                if(isset($_COOKIE['filtro'])){
                                    if($_COOKIE['filtro'] == -1){
                                        $filtro = '';
                                    }elseif($_COOKIE['filtro'] == -2){
                                        $filtro = ' and status = 0 and vencimento < NOW()';
                                    }else{
                                        $filtro = ' and status = '.$_COOKIE['filtro'];
                                    }
                                }
                                $wData = '';
                                if(isset($_COOKIE['inicioData']) && $_COOKIE['inicioData'] != ''){
                                    if($_COOKIE['fimData'] != ''){
                                        $wData = " and vencimento >= '".$_COOKIE['inicioData']."' and vencimento <= '".$_COOKIE['fimData']."'";
                                    }
                                    else{
                                        $wData = " and vencimento = '".$_COOKIE['inicioData']."'";
                                    }
                                }

                                $resp = $con->query('select * from tbl_contasReceber where 1'.$filtro.$wData.' order by status,vencimento');
                                while($row = $resp->fetch_assoc()){
                                    $cliente = $con->query('select razaoSocial_nome from tbl_clientes where id = '.$row['cliente'])->fetch_assoc()['razaoSocial_nome'];
                                    $status = '';
                                    $statusCor = '';
                                    
                                    switch($row['status']){
                                        case '0':
                                            if(strtotime(date('Y/m/d')) > strtotime($row['vencimento'])){
                                                $status = 'Vencido';
                                                $statusCor = 'warning';
                                            }
                                            else{
                                                $status = 'Aberto';
                                                $statusCor = 'primary';
                                            }
                                        break;
                                        case '1':
                                            $status = 'Pago';
                                            $statusCor = 'success';
                                        break;
                                        case '2':
                                            $status = 'Cancelado';
                                            $statusCor = 'danger';
                                        break;
                                    }
                                    echo '
                                        <tr>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$cliente.'</td>
                                            <td>'.$row['descricao'].'</td>
                                            <td>'.$row['nDocumento'].'</td>
                                            <td>'.$row['parcela'].'/'.$row['totalParcela'].'</td>
                                            <td>'.number_format($row['valor'],2,',','').'</td>
                                            <td>'.date('d / m / Y',strtotime($row['vencimento'])).'</td>
                                            
                                    ';
                                    
                                    if($row['status'] == '0'){
                                        echo '
                                            <td class="noPrint text-center"><div class="btn btn-'.$statusCor.' d-flex"><i class="fas fa-money-bill-wave m-auto"></i><span class="ml-2">'.$status.'</span></div></td>
                                            <td class="noPrint text-center"><a href="?conc='.$row['id'].'" class="btn"><i class="fas fa-thumbs-up icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?canc='.$row['id'].'" class="btn"><i class="fas fa-times icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?edt='.$row['id'].'" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
                                        ';
                                    }else{
                                        echo '
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        ';
                                    }

                                    echo '
                                        </tr>
                                    ';
                                }
                        
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>

<!-- modal -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog">
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
                            $resp = $con->query('select * from tbl_contasReceber where id = '.$_GET['edt']);
                            $un = $resp->fetch_assoc();
                        }
                    ?>

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
                            <label for="valor">Valor (parcela)<span class="ml-2 text-danger">*</span></label>
                            <input type="number" step="0.01" value="<?=isset($un['valor'])?$un['valor']:'0.01';?>" min="0.01" class="form-control" name="valor" id="valor" required onchange="calcValor()">
                        </div>
                        <div class="col">
                            <label for="parcela">Parcelas<span class="ml-2 text-danger">*</span></label>
                            <input type="number" step="1" min="1" value="<?=isset($un['parcela'])?$un['parcela']:'1';?>" class="form-control" name="parcela" id="parcela" required onchange="calcValor()" <?=isset($_GET['edt'])?'readonly':'';?>>
                        </div>
                        <div class="col-3">
                            <label for="total">Total</label>
                            <input type="text" value="100.000" class="form-control" id="total" readonly>
                        </div>                  
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="vencimento">Vencimento<span class="ml-2 text-danger">*</span></label>
                            <input type="date" class="form-control" name="vencimento" id="vencimento" value="<?=$un['vencimento']?>" required>
                        </div>
                        <div class="col">
                            <label for="intervalo">Intervalo (dias)</label>
                            <input type="number" class="form-control" name="intervalo" id="intervalo" value="<?=isset($un['intervalo'])?$un['intervalo']:'30';?>" <?=isset($_GET['edt'])?'readonly':'';?>>
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
                            <label for="descricao">Descrição</label>
                            <textarea name="descricao" id="descricao" rows="4" class="form-control" style="resize:none;" maxlength="200"><?=$un['descricao']?></textarea>
                        </div>
                    </div>

                    <input id="needs-validation" class="d-none" type="submit" value="enviar">

                </form>

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