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
    elseif($cmd == "edt"){
        $query = 'UPDATE `tbl_locaisEstoque` SET 
            `nome`= "'.$_POST['nome'].'",
            `local`= "'.$_POST['local'].'",
            `empresa`= "'.$_POST['empresa'].'",
            `status`= "'.$_POST['status'].'" 
        WHERE id =  "'.$_POST['id'].'"';
        $con->query($query);
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('delete from tbl_locaisEstoque where id = '.$_GET['del']);
    redirect($con->error);
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

    function mostrar(classe,self){
        $('.produtosEstoque:not("'+classe+'")').hide();
        $('.produtosEstoque:not("'+classe+'")').hide();

        const flag = $($(self).parent().find('i')).hasClass('active');
        $('.tbl_linhaComBorda td .active').toggle();
        $('.tbl_linhaComBorda td .active').removeClass('active');
        
        if(!flag){
            $($(self).parent().find('i')).toggle();
            $($(self).parent().find('i')).addClass('active');
        }
        
        $(classe).toggle('slow');
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
                <span>Posição de estoque</span>
                <div class="page-title-subheading">
                    Campo para acompanhamento de estoque
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

                    <h5 class="card-title">Estoque</h5>
                    <div class="input-group w-50">
                        <input type="text" class="mb-2 form-control" placeholder="Pesquisar" id="campoPesquisa">
                        <select class="form-control" onchange="location.href='?f='+$(this).val()">
                            <option selected disabled>Selecione o filtro</option>
                            <option value="produto">Produto</option>
                            <option value="local">Local</option>
                        </select>
                    </div>

                    <table class="table mb-0 table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Produto</th>
                                <th style="width:10%">Quantia</th>
                                <th style="width:10%">UN</th>
                                <th style="width:10%">Local</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                                if(!isset($_GET['f']) || $_GET['f'] == "local"){
                                    $resp = $con->query('select *,sum(quantia) as estoque_total from tbl_estoque group by produto order by local;');
                                    $grupoNome = '';
                                    while($row = $resp->fetch_assoc()){
                                        $produto = $con->query('select nome,unidadeEstoque from tbl_produtos where id = '.$row['produto'])->fetch_assoc();
                                        $local = $con->query('select local from tbl_locaisEstoque where id = '.$row['local'])->fetch_assoc();
                                        $un = $con->query('select simbolo from tbl_unidades where id = '.$produto['unidadeEstoque'])->fetch_assoc();
                                        
                                        if($grupoNome != $row['local']){
                                            echo '
                                                <tr class="tbl_linhaComBorda bg-light">
                                                    <td><i class="fas fa-caret-down"></i><i class="fas fa-caret-up" style="display:none"></i></td>
                                                    <td colspan="4" onclick="mostrar(\'.c-'.$row['local'].'\',this)"><strong>'.ucfirst($local['local']).'</strong></td>
                                                </tr>
                                            ';
                                            $grupoNome = $row['local'];
                                        }
                                        echo '
                                            <tr class="c-'.$row['local'].' produtosEstoque" style="display:none">
                                                <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                                <td>'.$produto['nome'].'</td>
                                                <td>'.number_format(floatval($row['estoque_total']),4,',','.').'</td>
                                                <td>'.$un['simbolo'].'</td>
                                                <td></td>
                                            </tr>
                                        ';
                                    }
                                }
                                elseif($_GET['f'] == "produto"){
                                    $resp = $con->query('select * from tbl_estoque order by produto;');
                                    $grupoNome = '';
                                    while($row = $resp->fetch_assoc()){
                                        $produto = $con->query('select nome,unidadeEstoque from tbl_produtos where id = '.$row['produto'])->fetch_assoc();
                                        $local = $con->query('select local from tbl_locaisEstoque where id = '.$row['local'])->fetch_assoc();
                                        $un = $con->query('select simbolo from tbl_unidades where id = '.$produto['unidadeEstoque'])->fetch_assoc();
                                        
                                        if($grupoNome != $row['produto']){
                                            $totalEstoque = $con->query('select sum(quantia) as total_estoque from tbl_estoque where produto = '.$row['produto'].' group by produto')->fetch_assoc();
                                            echo '
                                                <tr class="tbl_linhaComBorda bg-light">
                                                    <td><i class="fas fa-caret-down"></i><i class="fas fa-caret-up" style="display:none"></i></td>
                                                    <td colspan="3" onclick="mostrar(\'.c-'.$row['produto'].'\',this)"><strong>'.ucfirst($produto['nome']).'</strong></td>
                                                    <td class="d-flex">Total <strong class="ml-auto">'.number_format(floatval($totalEstoque['total_estoque']),4,',','.').' '.$un['simbolo'].'</strong></td>
                                                </tr>
                                            ';
                                            $grupoNome = $row['produto'];
                                        }
                                        echo '
                                            <tr class="c-'.$row['produto'].' produtosEstoque" style="display:none">
                                                <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                                <td>'.$produto['nome'].'</td>
                                                <td>'.number_format(floatval($row['quantia']),4,',','.').'</td>
                                                <td>'.$un['simbolo'].'</td>
                                                <td>'.$local['local'].'</td>
                                            </tr>
                                        ';
                                        
                                    }
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