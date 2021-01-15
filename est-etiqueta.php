<?php include('header.php'); ?>

<?php

/*if(isset($_POST['cmd'])){
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
}*/
/*elseif(isset($_GET['del'])){
    $con->query('delete from tbl_unidades where id = '.$_GET['del']);
    #redirect($con->error);
}*/

?>
<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()">imprimir</button>');
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
                    <?$confImp = $con->query('select * from tbl_confImp')->fetch_all();?>
                    <table id="tablePrint">
                        <style>
                            *{
                                margin: 0;
                                padding: 0;
                            }
                            #tablePrint{
                                border-collapse: separate;
                                border-spacing: <?=$confImp[0][14]?>mm <?=$confImp[0][10];?>mm;
                                padding-left: <?=$confImp[0][13];?>mm;
                                padding-right: <?=$confImp[0][13];?>mm;
                                border-width: 1px;
                                font-size: 10px;
                            }
                            .linha{
                                height: <?=$confImp[0][11];?>mm;
                                max-height: <?=$confImp[0][11];?>mm;
                            }
                            .etiqueta{
                                height: 100%;
                                width: <?=$confImp[0][12];?>mm;
                                max-width: <?=$confImp[0][12];?>mm;
                                background-color:yellow;
                                border-style:solid;
                                border-width:1px;
                                position: relative;
                            }
                            .etiqueta span{
                                position: absolute;
                            }
                        </style>
                        <tr class="linha">
                            <td class="etiqueta">
                                <span style="top: <?=$confImp[0][3]?>mm;left: <?=$confImp[4][3]?>mm;display:<?=($confImp[4][3]==0&&$confImp[0][3]==0)?'none':'block';?>">0012</span>
                                <span style="top: <?=$confImp[0][4]?>mm;left: <?=$confImp[4][4]?>mm;display:<?=($confImp[4][4]==0&&$confImp[0][4]==0)?'none':'block';?>">Peso</span>
                                <span style="top: <?=$confImp[0][5]?>mm;left: <?=$confImp[4][5]?>mm;display:<?=($confImp[4][5]==0&&$confImp[0][5]==0)?'none':'block';?>">Preço</span>
                                <span style="top: <?=$confImp[0][6]?>mm;left: <?=$confImp[4][6]?>mm;display:<?=($confImp[4][6]==0&&$confImp[0][6]==0)?'none':'block';?>">Tamanho</span>
                                <span style="top: <?=$confImp[0][7]?>mm;left: <?=$confImp[4][7]?>mm;display:<?=($confImp[4][7]==0&&$confImp[0][7]==0)?'none':'block';?>">Quantidade</span>
                                <span style="top: <?=$confImp[0][8]?>mm;left: <?=$confImp[4][8]?>mm;display:<?=($confImp[4][8]==0&&$confImp[0][8]==0)?'none':'block';?>">Produto teste</span>
                                <span style="top: <?=$confImp[0][9]?>mm;left: <?=$confImp[4][9]?>mm;display:<?=($confImp[4][9]==0&&$confImp[0][9]==0)?'none':'block';?>">0111010101011</span>
                            </td>
                            <td class="etiqueta">
                                <span style="top: <?=$confImp[1][3]?>mm;left: <?=$confImp[5][3]?>mm;display:<?=($confImp[1][3]==0&&$confImp[5][4]==0)?'none':'block';?>">0012</span>
                                <span style="top: <?=$confImp[1][4]?>mm;left: <?=$confImp[5][4]?>mm;display:<?=($confImp[1][4]==0&&$confImp[5][4]==0)?'none':'block';?>">Peso</span>
                                <span style="top: <?=$confImp[1][5]?>mm;left: <?=$confImp[5][5]?>mm;display:<?=($confImp[1][5]==0&&$confImp[5][5]==0)?'none':'block';?>">Preço</span>
                                <span style="top: <?=$confImp[1][6]?>mm;left: <?=$confImp[5][6]?>mm;display:<?=($confImp[1][6]==0&&$confImp[5][6]==0)?'none':'block';?>">Tamanho</span>
                                <span style="top: <?=$confImp[1][7]?>mm;left: <?=$confImp[5][7]?>mm;display:<?=($confImp[1][7]==0&&$confImp[5][7]==0)?'none':'block';?>">Quantidade</span>
                                <span style="top: <?=$confImp[1][8]?>mm;left: <?=$confImp[5][8]?>mm;display:<?=($confImp[1][8]==0&&$confImp[5][8]==0)?'none':'block';?>">produto teste</span>
                                <span style="top: <?=$confImp[1][9]?>mm;left: <?=$confImp[5][9]?>mm;display:<?=($confImp[1][9]==0&&$confImp[5][9]==0)?'none':'block';?>">0111010101011</span>

                            </td>
                            <td class="etiqueta">
                                <span style="top: <?=$confImp[2][3]?>mm;left: <?=$confImp[6][3]?>mm;display:<?=($confImp[2][3]==0&&$confImp[6][4]==0)?'none':'block';?>">0012</span>
                                <span style="top: <?=$confImp[2][4]?>mm;left: <?=$confImp[6][4]?>mm;display:<?=($confImp[2][4]==0&&$confImp[6][4]==0)?'none':'block';?>">Peso</span>
                                <span style="top: <?=$confImp[2][5]?>mm;left: <?=$confImp[6][5]?>mm;display:<?=($confImp[2][5]==0&&$confImp[6][5]==0)?'none':'block';?>">Preço</span>
                                <span style="top: <?=$confImp[2][6]?>mm;left: <?=$confImp[6][6]?>mm;display:<?=($confImp[2][6]==0&&$confImp[6][6]==0)?'none':'block';?>">Tamanho</span>
                                <span style="top: <?=$confImp[2][7]?>mm;left: <?=$confImp[6][7]?>mm;display:<?=($confImp[2][7]==0&&$confImp[6][7]==0)?'none':'block';?>">Quantidade</span>
                                <span style="top: <?=$confImp[2][8]?>mm;left: <?=$confImp[6][8]?>mm;display:<?=($confImp[2][8]==0&&$confImp[6][8]==0)?'none':'block';?>">produto teste</span>
                                <span style="top: <?=$confImp[2][9]?>mm;left: <?=$confImp[6][9]?>mm;display:<?=($confImp[2][9]==0&&$confImp[6][9]==0)?'none':'block';?>">0111010101011</span>
                            </td>
                            <td class="etiqueta">
                                <span style="top: <?=$confImp[3][3]?>mm;left: <?=$confImp[7][3]?>mm;display:<?=($confImp[3][3]==0&&$confImp[7][4]==0)?'none':'block';?>">0012</span>
                                <span style="top: <?=$confImp[3][4]?>mm;left: <?=$confImp[7][4]?>mm;display:<?=($confImp[3][4]==0&&$confImp[7][4]==0)?'none':'block';?>">Peso</span>
                                <span style="top: <?=$confImp[3][5]?>mm;left: <?=$confImp[7][5]?>mm;display:<?=($confImp[3][5]==0&&$confImp[7][5]==0)?'none':'block';?>">Preço</span>
                                <span style="top: <?=$confImp[3][6]?>mm;left: <?=$confImp[7][6]?>mm;display:<?=($confImp[3][6]==0&&$confImp[7][6]==0)?'none':'block';?>">Tamanho</span>
                                <span style="top: <?=$confImp[3][7]?>mm;left: <?=$confImp[7][7]?>mm;display:<?=($confImp[3][7]==0&&$confImp[7][7]==0)?'none':'block';?>">Quantidade</span>
                                <span style="top: <?=$confImp[3][8]?>mm;left: <?=$confImp[7][8]?>mm;display:<?=($confImp[3][8]==0&&$confImp[7][8]==0)?'none':'block';?>">produto teste</span>
                                <span style="top: <?=$confImp[3][9]?>mm;left: <?=$confImp[7][9]?>mm;display:<?=($confImp[3][9]==0&&$confImp[7][9]==0)?'none':'block';?>">0111010101011</span>
                            </td>
                        </tr>                    
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