<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $query = 'insert into tbl_unidades(nome,status,grupoNome,simbolo,base,convBase) values(
            "'.$_POST['nome'].'",
            "'.$_POST['status'].'",
            "'.$_POST['grupoUnidadeNome'].'",
            "'.$_POST['simbolo'].'",
            "'.$_POST['unBase'].'",
            "'.$_POST['convUnBase'].'"
        )';
        $con->query($query);
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        $query = 'update tbl_unidades set
            nome = "'.$_POST['nome'].'",
            status = "'.$_POST['status'].'",
            grupoNome = "'.$_POST['grupoUnidadeNome'].'",
            simbolo = "'.$_POST['simbolo'].'",
            base = "'.$_POST['unBase'].'",
            convBase = "'.$_POST['convUnBase'].'"
            where id = '.$_POST['id'].'
        ';
        $con->query($query);
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_unidades set status = 0 where id = '.$_GET['del']);
    #redirect($con->error);
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

    function selecionaGrupo(self){
        $('#grupoUnidadeNome').val($(self).val());
        $('#unBase').val(0);
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
                <i class="fas fa-balance-scale-left icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de unidades de medidas</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de unidades de medidas
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

                    <h5 class="card-title">Unidades de medidas</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:26%">Nome</th>
                                <th style="width:14%">Símbolo</th>
                                <th style="width:6%">Base</th>
                                <th>Conversão</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_unidades where status = 1 order by grupoNome, base desc');
                            
                                $grupoNome = '';
                                while($row = $resp->fetch_assoc()){
                                    if($grupoNome != $row['grupoNome']){
                                        $grupoNome = $row['grupoNome'];
                                        echo '<tr><th colspan="7" class="bg-light text-dark text-center">'.ucfirst($grupoNome).'</th></tr>';
                                    }
                                    $nomeBase = $con->query('select nome from tbl_unidades where grupoNome = "'.$grupoNome.'" and base = 1')->fetch_assoc()['nome'];
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['simbolo'].'</td>
                                            <td>'.($row['base'] == 1?'Sim':'Não').'</td>
                                            <td>1 '.$row['nome'].' = '.$row['convBase'].' '.$nomeBase.'</td>
                                            <td class="noPrint text-center"><a href="?edt='.$row['id'].'" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
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