<?php include('header.php'); ?>

<?
    #var_dump($_POST);
    switch($_POST['cmd']){
        case 'add':
            $con->query('INSERT INTO `tbl_remessa`(`data`, `status`, `usuario`, `descricao`, `cliente`) VALUES (
                "'.date('Y-m-d').'",
                "0",
                "'.$_SESSION['id'].'",
                "'.$_POST['descricao'].'",
                "'.$_POST['cliente'].'"
            )');
            $lastid = $con->insert_id;
            for($i = 0; $i < sizeof($_POST['id']); $i++){
                $con->query('INSERT INTO `tbl_remessaItem`(`produto`, `quantia`, `remessa`) VALUES (
                    "'.$_POST['id'][$i].'",
                    "'.$_POST['qtd'][$i].'",
                    "'.$lastid.'"
                )');
            }
            redirect($con->error);
        break;
        case 'edt':
            $con->query('UPDATE tbl_remessa set descricao = "'.$_POST['descricao'].'", cliente = "'.$_POST['cliente'].'" where id = '.$_POST['codigo']);
            $con->query('DELETE from tbl_remessaItem where remessa = '.$_POST['codigo']);
            for($i = 0; $i < sizeof($_POST['id']); $i++){
                $con->query('INSERT INTO `tbl_remessaItem`(`produto`, `quantia`, `remessa`) VALUES (
                    "'.$_POST['id'][$i].'",
                    "'.$_POST['qtd'][$i].'",
                    "'.$_POST['codigo'].'"
                )');
            }
            redirect($con->error);
        break;
    }
    if($_GET['del']){
        $con->query('update tbl_remessa set status = -1 where id = '.$_GET['del']);
        $con->query('update tbl_remessaItem set status = 0 where remessa = '.$_GET['del']);
        redirect($con->error);
    }
    elseif($_GET['fin']){
        $con->query('update tbl_remessa set status = 1 where id = '.$_GET['fin']);
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

    function salvarProduto(){
        const buffer = $('#produto').val().split(' - ');
        if(buffer.length < 3){
            alert('Selecione o produto');
            return;
        }
        
        $('#linhaProdutos').append(`
            <div class="row mb-2">
                <div class="col">
                    <input type="hidden" name="id[]" value="`+buffer[0]+`">
                    <input type="text" class="form-control" name="produto[]" value="`+buffer[2]+`" readonly>
                </div>
                <div class="col-3">
                    <input type="number" class="form-control" name="qtd[]" value="`+$('#quantia').val()+`">
                </div>
                <div class="col-1">
                    <span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
                </div>
            </div>
        `);

        $('#produto').val('');
        $('#quantia').val(0);
        $('#produto').focus();
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
                <i class="fas fa-suitcase icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Remessa</span>
                <div class="page-title-subheading">
                    Campo para controle de remessa
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=unidades">
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

                    <h5 class="card-title">Remessa</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th>Descrição</th>
                                <th style="width:14%">Data</th>
                                <th style="width:6%">Status</th>
                                <th>Usuário</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_remessa where status > -1 order by id desc');
                            
                                $grupoNome = '';
                                while($row = $resp->fetch_assoc()){
                                    if($grupoNome != $row['grupoNome']){
                                        $grupoNome = $row['grupoNome'];
                                        echo '<tr><th colspan="7" class="bg-light text-dark text-center">'.ucfirst($grupoNome).'</th></tr>';
                                    }
                                    $nomeBase = $con->query('select nome from tbl_unidades where grupoNome = "'.$grupoNome.'" and base = 1')->fetch_assoc()['nome'];
                                    
                                    if($row['cliente'] != -1)
                                        $usuario = $con->query('select nome from tbl_usuario where id = '.$row['cliente'])->fetch_assoc()['nome'];
                                    else
                                        $usuario = "Todos";
                                    $finalizar = $row['status'] == 0? '<a href="?fin='.$row['id'].'" class="btn"><i class="fas fa-check text-success"></i></a>':'';
                                    switch($row['status']){
                                        case '0':
                                            $status = 'Em aberto';
                                        break;
                                        case '1':
                                            $status = 'Finalizado';
                                        break;
                                        case '-1':
                                            $status = 'Cancelado';
                                        break;
                                    }
                                    echo '
                                        <tr>
                                            <td>'.$row['descricao'].'</td>
                                            <td>'.date('d / m / Y',strtotime($row['data'])).'</td>
                                            <td>'.$status.'</td>
                                            <td>'.$usuario.'</td>
                                            <td class="noPrint text-center">'.$finalizar.'</td>
                                            <td class="noPrint text-center"><a target="_blank" href="est-remessaImprimir.php?prt='.$row['id'].'" class="btn"><i class="fas fa-print icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?edt='.$row['id'].'" class="btn"><i class="fas fa-edit icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?del='.$row['id'].'" class="btn"><i class="fas fa-trash icon-gradient bg-happy-itmeo"></i></a></td>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar nova remessa</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" autocomplete="off">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_remessa where id = '.$_GET['edt']);
                            $un = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="codigo" id="codigo">

                    <div class="row">
                        <div class="col">
                            <label for="descricao">Descrição<span class="ml-2 text-danger">*</span></label>
                            <input type="text" value="<?php echo $un['descricao']; ?>" class="form-control mb-3" name="descricao" id="descricao" maxlength="120" required>
                        </div>
                        <div class="col">
                            <label for="Cliente">Cliente</label>
                            <select name="cliente" id="cliente" class="form-control">
                                <option value="-1" <?=!isset($un['cliente'])||$un['cliente']==-1?'selected':''?>>Selecione o cliente</option>
                                <?php
                                    $resp = $con->query('select id,nome from tbl_usuario');

                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'" '.($un['cliente']==$row['id']?'selected':'').'>'.$row['nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="divider mb-3"></div>

                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <input type="text" placeholder="Nome do produto" class="form-control w-50" id="produto" maxlength="60" list="listProd">
                                <input type="number" placeholder="Quantia" id="quantia" class="form-control" min="0">
                                <div class="input-group-append" onclick="salvarProduto()">
                                        <span class="btn btn-success">Salvar</span>
                                </div>
                            </div>
                            <datalist id="listProd">
                                <?
                                    $resp = $con->query('select id,referencia,nome from tbl_produtos');
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].' - '.str_replace('-','',$row['referencia']).' - '.$row['nome'].'">';
                                    }
                                ?>
                            </datalist>
                        </div>
                    </div>

                    <div class="divider mb-3"></div>

                    <div class="row">
                        <div class="col" id="linhaProdutos">
                            <?
                                $resp = $con->query('select * from tbl_remessaItem where remessa = '.$_GET['edt']);
                                if($resp){
                                    while($row = $resp->fetch_assoc()){
                                        $produto = $con->query('select nome from tbl_produtos where id = '.$row['produto'])->fetch_assoc();
                                        echo '
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <input type="hidden" name="id[]" value="'.$row['produto'].'">
                                                    <input type="text" class="form-control" name="produto[]" value="'.$produto['nome'].'" readonly>
                                                </div>
                                                <div class="col-3">
                                                    <input type="number" class="form-control" name="qtd[]" value="'.$row['quantia'].'">
                                                </div>
                                                <div class="col-1">
                                                    <span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            ?>
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