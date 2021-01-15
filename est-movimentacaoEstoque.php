<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $query = 'INSERT INTO `tbl_locaisEstoque`(`nome`, `local`, `empresa`, `status`) VALUES (
            "'.$_POST['nome'].'",
            "'.$_POST['local'].'",
            "'.$_POST['empresa'].'",
            "'.$_POST['status'].'"
        )';
        $con->query($query);
        redirect($con->error);
    }
    elseif($cmd = "movimentacao"){
        $produto = explode(' - ',$_POST['produto'])[0];
        $query = 'INSERT INTO `tbl_estoque`(`quantia`, `produto`, `local`, `operacao`, `motivo`, `data`) VALUES (
            "'.$_POST['quantia'].'",
            "'.$produto.'",
            "'.$_POST['estoque'].'",
            "'.$_POST['operacao'].'",
            "'.$_POST['motivo'].'",
            "'.date('Y-m-d').'"
        )';
        $con->query($query);
        if($_POST['operacao'] == 'e'){
            $con->query('update tbl_produtos set estoque = estoque + "'.$_POST['quantia'].'" where id = '.$produto);
        }
        else{
            $con->query('update tbl_produtos set estoque = estoque - "'.$_POST['quantia'].'" where id = '.$produto);
        }
        redirect($con->error);
    }
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
                <i class="fas fa-cubes icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Movimentação de estoque</span>
                <div class="page-title-subheading">
                    Campo para movimentação de estoque
                </div>
            </div>

        </div>
        <div class="page-title-actions">

            <!--<button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
            <i class="fas fa-plus"></i>
            </button>-->

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

                    <h5 class="card-title">Movimentações</h5>
                    <!--<input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">-->
                    
                    <form method="post" autocomplete="off">
                        <input type="hidden" name="cmd" value="movimentacao">
                        <div class="input-group mt-4 mb-4">
                            <select name="operacao" class="form-control">
                                <option selected disabled>Operação</option>
                                <option value="e">Entrada</option>
                                <option value="s">Saída</option>
                            </select>
                            <input type="text" class="form-control w-25" list="listProd" placeholder="Insira o produto" name="produto">
                            <datalist id="listProd">
                                <?
                                    $resp = $con->query('select id,nome from tbl_produtos where status = 1');
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].' - '.$row['nome'].'">';
                                    }
                                ?>
                            </datalist>
                            <select name="estoque" class="form-control">
                                <?
                                    $resp = $con->query('select id,nome from tbl_locaisEstoque where empresa = '.$_COOKIE['empresa'].' and status = 1');
                                    $cont = 0;
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'" '.($cont++==0?'selected':'').'>'.$row['nome'].'</option>';
                                    }
                                ?>
                            </select>
                            <input type="number" step="0.0001" min="0.0001" class="form-control" placeholder="Quantia" name="quantia">
                            <input type="text" class="form-control" placeholder="Motivo" name="motivo">
                            <div class="input-group-append">
                                <button class="btn btn-secondary">Registrar</button>
                            </div>
                        </div>
                    </form>

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Produto</th>
                                <th>Quantia</th>
                                <th>Unidade</th>
                                <th>Operação</th>
                                <th>Motivo</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_estoque order by id desc');
                            
                                $grupoNome = '';
                                while($row = $resp->fetch_assoc()){
                                    $produto = $con->query('select a.nome as produto,b.simbolo as un from tbl_produtos a inner join tbl_unidades b on b.id = a.unidadeEstoque where a.id = '.$row['produto'])->fetch_assoc();
                                    
                                    switch($row['operacao']){
                                        case 'e':
                                            $operacao = "Entrada";
                                        break;
                                        case 's':
                                            $operacao = "Saída";
                                        break;
                                        case 'i':
                                            $operacao = "Interno";
                                        break;
                                    }
                                    
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.$produto['produto'].'</td>
                                            <td>'.$row['quantia'].'</td>
                                            <td>'.$produto['un'].'</td>
                                            <td>'.$operacao.'</td>
                                            <td>'.$row['motivo'].'</td>
                                            <td>'.date('d / m / Y',strtotime($row['data'])).'</td>
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
                            $resp = $con->query('select * from tbl_locaisEstoque where id = '.$_GET['edt']);
                            $un = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

                    <div class="row">
                        <div class="col">
                            <label for="nome">Nome<span class="ml-2 text-danger">*</span></label>
                            <input type="text" value="<?php echo $un['nome']; ?>" class="form-control mb-3" name="nome" id="nome" maxlength="60" required>
                        </div>
                        <div class="col-2">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" <?php echo $un['status'] == 1 || !isset($_GET['edt'])?'selected':'';?>>Ativo</option>
                                <option value="0" <?php echo $un['status'] == 0 && isset($_GET['edt'])?'selected':'';?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="local">Local<span class="ml-2 text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" name="local" id="local" value="<?php echo $un['local'];?>" maxlength="120" required>
                        </div>
                        <div class="col">
                            <label for="empresa">Empresa</label>
                            <select name="empresa" id="empresa" class="form-control">
                                <?
                                    $resp = $con->query('select id,razao_social from tbl_configuracao');
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'" '.($_COOKIE['empresa']==$row['id']?'selected':'').'>'.$row['razao_social'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <input id="needs-validation" class="d-none" type="submit" value="enviar">


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