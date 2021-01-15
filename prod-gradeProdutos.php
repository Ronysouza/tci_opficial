<?php include('header.php'); ?>

<?
    switch($_POST['cmd']){
        case 'add':
            $query = 'INSERT INTO `tbl_gradeProdutos`(`descricao`, `grupo`) VALUES ("'.$_POST['descricao'].'","'.$_POST['grupo'].'")';
            $con->query($query);
            $last_id = $con->insert_id; 
            
            for($i = 0; $i < sizeof($_POST['id']); $i++){
                $query = 'INSERT INTO `tbl_gradeProdutosItens`(`item`, `fator`, `grade`) VALUES ("'.$_POST['id'][$i].'","'.$_POST['fator'][$i].'","'.$last_id.'")';
                $con->query($query);
            }

            redirect($con->error);
        break;
        case 'edt':
            $query = 'UPDATE `tbl_gradeProdutos` SET 
                `descricao`= "'.$_POST['descricao'].'",
                `grupo`= "'.$_POST['grupo'].'" 
                WHERE id = '.$_POST['codigo'].'
            ';
            $con->query($query);

            $con->query('delete from tbl_gradeProdutosItens where grade = '.$_POST['codigo']);

            for($i = 0; $i < sizeof($_POST['id']); $i++){
                $query = 'INSERT INTO `tbl_gradeProdutosItens`(`item`, `fator`, `grade`) VALUES ("'.$_POST['id'][$i].'","'.$_POST['fator'][$i].'","'.$_POST['codigo'].'")';
                $con->query($query);
            }

            redirect($con->error);
        break;
    }
    if($_GET['del']){
        $con->query('update tbl_gradeProdutos set status = 0 where id = '.$_GET['del']);
        $con->query('update tbl_gradeProdutosItens set status = 0 where grade = '.$_GET['del']);
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

    function salvarProduto(){
        const buffer = $('#produto').val().split(' - ');
        if(buffer.length < 3){
            alert('Selecione o produto');
            return;
        }

        $('#linhaProdutos').append(`
            <tr>
                <td>
                    `+buffer[0]+`    
                </td>
                <td>
                    `+buffer[2]+`
                </td>
                <td>
                    `+parseFloat($('#fator').val()).toFixed(8).toString()+`
                </td>
                <td>
                    <span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
                    <input type="hidden" name="id[]" value="`+buffer[0]+`">
                    <input type="hidden" class="form-control" name="produto[]" value="`+buffer[2]+`" readonly>
                    <input type="hidden" class="form-control" name="fator[]" value="`+parseFloat($('#fator').val()).toFixed(8).toString()+`">
                    <input type="hidden" class="form-control" name="calculo[]" value="`+$('#formaCalculo option:selected').text()+`">
                </td>
            </tr>
        `);

        $('#produto').val('');
        $('#fator').val(0);
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
                <i class="fas fa-table icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Grade de produtos</span>
                <div class="page-title-subheading">
                    Campo para controle de grade de produção
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

                    <h5 class="card-title">Grade de produtos</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:4%">ID</th>
                                <th>Descrição</th>
                                <th style="width:14%">Grupo</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_gradeProdutos where status = 1 order by id desc');
                            
                                $grupoNome = '';
                                while($row = $resp->fetch_assoc()){
                                    $grupo = $con->query('select nome from tbl_grupo where id = '.$row['grupo'])->fetch_assoc()['nome'];                                        
                                    
                                    echo '
                                        <tr>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$row['descricao'].'</td>
                                            <td>'.$grupo.'</td>
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
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar nova grade de produto</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" autocomplete="off">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_gradeProdutos where id = '.$_GET['edt']);
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
                        <div class="col-3">
                            <label for="">Grupo<span class="ml-2 text-danger">*</span></label>
                            <select name="grupo" id="grupo" class="form-control">
                                <option selected disabled>Selecione</option>
                                <?
                                    $resp = $con->query('select id,nome from tbl_grupo where status = 1 and grupo = 0');
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'" '.($row['id'] == $un['grupo']?'selected':'').'>'.$row['nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="divider mb-3"></div>

                    <div class="row">
                        <div class="col-4">
                            <input type="text" placeholder="Nome do produto" class="form-control" id="produto" maxlength="60" list="listProd">
                            <datalist id="listProd">
                                <?
                                    $resp = $con->query('select id,referencia,nome from tbl_produtos where status = 1');
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].' - '.str_replace('-','',$row['referencia']).' - '.$row['nome'].'">';
                                    }
                                ?>
                            </datalist>
                        </div>
                        <div class="col-2">
                            <input type="number" placeholder="Fator" id="fator" class="form-control" min="0" step="0.00000001">
                        </div>
                        <div class="col-2">
                            <select id="formaCalculo" class="form-control">
                                <option value="0" selected>Custo Fixo X Quantidade</option>
                            </select>
                        </div>
                        <div class="col">
                            <span class="btn btn-success" onclick="salvarProduto()">Salvar</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <table class="table table-striped mt-3" id="linhaProdutos">

                                <?
                                    $resp = $con->query('select * from tbl_gradeProdutosItens where grade = '.$_GET['edt']);
                                    if($resp){
                                        while($row = $resp->fetch_assoc()){
                                            $produto = $con->query('select id,nome from tbl_produtos where id = '.$row['item'])->fetch_assoc();
                                            echo '
                                                <tr>
                                                    <td>'.$produto['id'].'</td>
                                                    <td>'.$produto['nome'].'</td>
                                                    <td>'.number_format($row['fator'],8,'.','').'</td>
                                                    <td>
                                                        <span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
                                                        <input type="hidden" name="id[]" value="'.$produto['id'].'">
                                                        <input type="hidden" class="form-control" name="produto[]" value="'.$produto['nome'].'" readonly>
                                                        <input type="hidden" class="form-control" name="fator[]" value="'.number_format($row['fator'],8,'.','').'">
                                                        <input type="hidden" class="form-control" name="calculo[]" value="'.$row['formaCalculo'].'">
                                                    </td>
                                                </tr>
                                            ';
                                        }
                                    }
                                ?>

                            </table>
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