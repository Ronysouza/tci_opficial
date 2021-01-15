<?php include('header.php'); ?>

<?php

if(isset($_GET['conc'])){
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
<script src="assets/scripts/lib/copy/clipboard.min.js"></script>
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

    function copiar(){
        $('#copy').select();
        //$('#copy').setSelectionRange(0, 99999);
        document.execCommand("copy");
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

            <a class="btn-shadow mr-3 btn btn-dark" id="btn-modal" href="fisc-contasReceberPagamento.php?">
                <i class="fas fa-plus"></i>
            </a>

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
                                            <td class="noPrint text-center"><div class="badge badge-'.$statusCor.' d-flex"><i class="fas fa-money-bill-wave m-auto"></i><span class="ml-2">'.$status.'</span></div></td>
                                            <td class="noPrint text-center"><a href="?conc='.$row['id'].'" class="btn"><i class="fas fa-thumbs-up icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?canc='.$row['id'].'" class="btn"><i class="fas fa-times icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="fisc-contasReceberPagamento.php?edt='.$row['id'].'" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
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
            <div class="modal-body">
                <div class="row">
                    <div class="col text-center">
                        <h3>Pagamento gerado com sucesso</h3>
                    </div>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col d-flex">
                        <!--<a class="btn btn-light mr-2 ml-auto w-50 d-flex" href="<?=urldecode($_GET['boletos'])?>" target="_blank"><i class="fas fa-file-pdf mt-auto mb-auto"></i><span class="ml-auto mr-auto">Boletos</span></a>-->
                        <a class="btn btn-light mr-auto w-100 d-flex" href="pagamento.php?e=<?=$_GET['empresa']?>&c=<?=$_GET['cartao']?>" target="_blank"><i class="fas fa-credit-card mt-auto mb-auto"></i><span class="ml-auto mr-auto">Cartão</span></a>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" id="copy" class="form-control" value="<?='http://'.$_SERVER['HTTP_HOST']?>/pagamento.php?e=<?=$_GET['empresa']?>&c=<?=$_GET['cartao']?>" readonly>
                            <div class="input-group-append bg-light" onclick="copiar()">
                                <div class="btn d-flex border"><i class="fas fa-copy m-auto"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="location.href='?'">Fechar</button>
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

<?php if(isset($_GET['edt'])) echo "<script>$('#mdl-cliente').modal()</script>"; ?>