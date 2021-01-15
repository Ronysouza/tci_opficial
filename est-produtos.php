<?php include('header.php'); ?>

<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting('E_ALL');

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $target_dir = 'upload/';
        $target_file = $target_dir . date('dmY') .basename($_FILES['imagem']['name']);
        $uploadOk = 1;

        $check = getimagesize($_FILES["imagem"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if($_POST['fiscal']['cfop'] != "")
            $cfop = $con->query('select id from tbl_cfop where cfop = "'.explode(' - ',$_POST['fiscal']['cfop'])[0].'"')->fetch_assoc()['id'];
        else
            $cfop = 0;

        if($_POST['fiscal']['cest'])
            $cest = $con->query('select id from tbl_ncm_cest where cest = "'.explode(' - ',$_POST['fiscal']['cest'])[0].'"')->fetch_assoc()['id'];
        else
            $cest = 0;

        $con->query('INSERT INTO `tbl_classificacaoFiscal`(`ncm`, `cfop`, `cest`, `cst`, `origem`, `icms_interno`, `aliq_ipi`, `cst_ipi`, `reducao_bc_icms`, `cst_pis`, `cst_cofins`, `aliq_pis`, `aliq_cof`, `aliq_II`, `icms`, `mva`, `red_bc`, `ret_st`, `status`) VALUES (
            "'.$_POST['fiscal']['ncm'].'",
            "'.$cfop.'",
            "'.$cest.'",
            "'.($_POST['fiscal']['cst'] | 0).'",
            "'.($_POST['fiscal']['origem'] | 0).'",
            "'.($_POST['fiscal']['icms_interno'] | 0).'",
            "'.($_POST['fiscal']['aliq_ipi'] | 0).'",
            "'.$_POST['fiscal']['cst_ipi'].'",
            "'.($_POST['fiscal']['reducao_bc_icms'] | 0).'",
            "'.$_POST['fiscal']['cst_pis'].'",
            "'.$_POST['fiscal']['cst_cofins'].'",
            "'.($_POST['fiscal']['aliq_pis'] | 0).'",
            "'.($_POST['fiscal']['aliq_cof'] | 0).'",
            "'.($_POST['fiscal']['aliq_ii'] | 0).'",
            "'.($_POST['fiscal']['icms'] | 0).'",
            "'.($_POST['fiscal']['mva'] | 0).'",
            "'.($_POST['fiscal']['red_bc'] | 0).'",
            "'.($_POST['fiscal']['ret_st'] | 0).'",
            1
        )');
        $_POST['classificacao_fiscal'] = $con->insert_id;

        if($uploadOk === 1){
            if(move_uploaded_file($_FILES["imagem"]["tmp_name"],$target_file)){
                $query = 'INSERT INTO `tbl_produtos`(`valor`,`referencia`,`nome`,`unidadeEstoque`,`grupo`,`subgrupo`,`tipoProduto`,`fornecedor`,`usoConsumo`, `comercializavel`, `descontoMinimo`, `descontoMaximo`, `classificacaoFiscal`, `codBarras`, `descricao`, `imagem`, `promocao`, `custo`, `comissao`, `valPromocao`, `dataPromocao`, `patrimonio`, `serie`) VALUES (
                    "'.($_POST['valor'] == 0? 0 : $_POST['valor']).'",
                    "'.$_POST['referencia'].'",
                    "'.$_POST['nome'].'",
                    "'.$_POST['un_estoque'].'",
                    "'.$_POST['grupo'].'",
                    "'.$_POST['subgrupo'].'",
                    "'.$_POST['tipoDeProduto'].'",
                    "'.$_POST['fornecedor'].'",
                    "'.isset($_POST['usoConsumo']).'",
                    "'.isset($_POST['comercializavel']).'",
                    "'.($_POST['descontoMinimo'] | 0).'",
                    "'.($_POST['descontoMaximo'] | 0).'",
                    "'.$_POST['classificacao_fiscal'].'",
                    "'.$_POST['codigoBarras'].'",
                    "'.addslashes($_POST['descricao']).'",
                    "'.$target_file.'",
                    "'.(isset($_POST['promocao'])?1:0).'",
                    "'.($_POST['custo'] | 0).'",
                    "'.($_POST['comissao'] | 0).'",
                    "'.($_POST['valPromocao'] | 0 ).'",
                    '.($_POST['dataPromocao']?'"'.$_POST['dataPromocao'].'"':'NULL').',
                    "'.$_POST['patrimonio'].'",
                    "'.$_POST['serie'].'"           
                )';
            }
        }
        else{
            $query = 'INSERT INTO `tbl_produtos`(`valor`,`referencia`,`nome`,`unidadeEstoque`,`grupo`,`subgrupo`,`tipoProduto`,`fornecedor`,`usoConsumo`, `comercializavel`, `descontoMinimo`, `descontoMaximo`, `classificacaoFiscal`, `codBarras`, `descricao`, `promocao`, `custo`, `comissao`, `valPromocao`, `dataPromocao`,`patrimonio`,`serie`) VALUES (
                "'.($_POST['valor'] == 0? 0 : $_POST['valor']).'",
                "'.$_POST['referencia'].'",
                "'.$_POST['nome'].'",
                "'.$_POST['un_estoque'].'",
                "'.$_POST['grupo'].'",
                "'.$_POST['subgrupo'].'",
                "'.$_POST['tipoDeProduto'].'",
                "'.$_POST['fornecedor'].'",
                "'.isset($_POST['usoConsumo']).'",
                "'.isset($_POST['comercializavel']).'",
                "'.($_POST['descontoMinimo'] | 0).'",
                "'.($_POST['descontoMaximo'] | 0).'",
                "'.$_POST['classificacao_fiscal'].'",
                "'.$_POST['codigoBarras'].'",
                "'.addslashes($_POST['descricao']).'",
                "'.(isset($_POST['promocao'])?1:0).'",
                "'.($_POST['custo'] | 0).'",
                "'.($_POST['comissao'] | 0).'",
                "'.($_POST['valPromocao'] | 0 ).'",
                '.($_POST['dataPromocao']?'"'.$_POST['dataPromocao'].'"':'NULL').',
                "'.$_POST['patrimonio'].'",
                "'.$_POST['serie'].'"    
            )';
        }



        $con->query($query);
        $prodId = $con->insert_id;

        foreach($_POST['codigos'] as $codigo){
            $con->query('insert into tbl_codigoBarras(produto,codigo) values("'.$prodId.'","'.$codigo.'")');
        }
        
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        
        if($_POST['fiscal']['cfop'] != "")
            $cfop = $con->query('select id from tbl_cfop where cfop = "'.explode(' - ',$_POST['fiscal']['cfop'])[0].'"')->fetch_assoc()['id'];
        else
            $cfop = 0;

        if($_POST['fiscal']['cest'])
            $cest = $con->query('select id from tbl_ncm_cest where cest = "'.explode(' - ',$_POST['fiscal']['cest'])[0].'"')->fetch_assoc()['id'];
        else
            $cest = 0;

        $query = 'UPDATE `tbl_classificacaoFiscal` SET
            `ncm`="'.$_POST['fiscal']['ncm'].'",
            `cfop`="'.$cfop.'",
            `cest`="'.$cest.'",
            `cst`="'.$_POST['fiscal']['cst'].'",
            `origem`="'.$_POST['fiscal']['origem'].'",
            `icms_interno`="'.$_POST['fiscal']['icms_interno'].'",
            `aliq_ipi`="'.$_POST['fiscal']['aliq_ipi'].'",
            `cst_ipi`="'.$_POST['fiscal']['cst_ipi'].'",
            `reducao_bc_icms`="'.$_POST['fiscal']['reducao_bc_icms'].'",
            `cst_pis`="'.$_POST['fiscal']['cst_pis'].'",
            `cst_cofins`="'.$_POST['fiscal']['cst_cofins'].'",
            `aliq_pis`="'.$_POST['fiscal']['aliq_pis'].'",
            `aliq_cof`="'.$_POST['fiscal']['aliq_cof'].'",
            `aliq_II`="'.$_POST['fiscal']['aliq_ii'].'",
            `icms`="'.$_POST['fiscal']['icms'].'",
            `mva`="'.$_POST['fiscal']['mva'].'",
            `red_bc`="'.$_POST['fiscal']['red_bc'].'",
            `ret_st`="'.$_POST['fiscal']['ret_st'].'" 
            WHERE id = '.$_POST['fiscal']['id'].'
        ';
        $con->query($query);

        if($_FILES['imagem']['name'] != ''){
            $target_dir = 'upload/';
            $target_file = $target_dir . date('dmY') .basename($_FILES['imagem']['name']);
            $uploadOk = 1;

            $check = getimagesize($_FILES["imagem"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }

            if($uploadOk === 1){
                if(move_uploaded_file($_FILES["imagem"]["tmp_name"],$target_file)){
                    unlink($_POST['imagemAntiga']);
                    $query = 'UPDATE `tbl_produtos` SET 
                        `referencia`= "'.$_POST['referencia'].'",
                        `nome`= "'.$_POST['nome'].'",
                        `unidadeEstoque`= "'.$_POST['un_estoque'].'",
                        `grupo`= "'.$_POST['grupo'].'",
                        `subgrupo`= "'.$_POST['subgrupo'].'",
                        `tipoProduto`= "'.$_POST['tipoDeProduto'].'",
                        `fornecedor`= "'.$_POST['fornecedor'].'",
                        `usoConsumo`= "'.isset($_POST['usoConsumo']).'",
                        `comercializavel`= "'.isset($_POST['comercializavel']).'",
                        `descontoMaximo`= "'.$_POST['descontoMaximo'].'",
                        `codBarras`= "'.$_POST['codigoBarras'].'",
                        `descricao`= "'.addslashes($_POST['descricao']).'",
                        `imagem`= "'.$target_file.'",
                        `valor` = "'.$_POST['valor'].'",
                        `promocao`= "'.(isset($_POST['promocao'])?1:0).'",
                        `custo`= "'.$_POST['custo'].'",
                        `comissao`= "'.$_POST['comissao'].'",
                        `valPromocao`= "'.$_POST['valPromocao'].'",
                        `dataPromocao`= '.($_POST['dataPromocao']?'"'.$_POST['dataPromocao'].'"':'NULL').',
                        `patrimonio` = "'.$_POST['patrimonio'].'",
                        `serie` = "'.$_POST['serie'].'"
                        WHERE `id` = "'.$_POST['id'].'"
                    ';
                }
            }
        }
        else{
            $query = 'UPDATE `tbl_produtos` SET 
                `referencia`= "'.$_POST['referencia'].'",
                `nome`= "'.$_POST['nome'].'",
                `unidadeEstoque`= "'.$_POST['un_estoque'].'",
                `grupo`= "'.$_POST['grupo'].'",
                `subgrupo`= "'.$_POST['subgrupo'].'",
                `tipoProduto`= "'.$_POST['tipoDeProduto'].'",
                `fornecedor`= "'.$_POST['fornecedor'].'",
                `usoConsumo`= "'.isset($_POST['usoConsumo']).'",
                `comercializavel`= "'.isset($_POST['comercializavel']).'",
                `descontoMaximo`= "'.$_POST['descontoMaximo'].'",
                `codBarras`= "'.$_POST['codigoBarras'].'",
                `descricao`= "'.addslashes($_POST['descricao']).'",
                `valor` = "'.$_POST['valor'].'",
                `promocao`= "'.(isset($_POST['promocao'])?1:0).'",
                `custo`= "'.$_POST['custo'].'",
                `comissao`= "'.$_POST['comissao'].'",
                `valPromocao`= "'.$_POST['valPromocao'].'",
                `dataPromocao`= '.($_POST['dataPromocao']?'"'.$_POST['dataPromocao'].'"':'NULL').',
                `patrimonio` = "'.($_POST['patrimonio']).'",
                `serie` = "'.$_POST['serie'].'"
                WHERE `id` = "'.$_POST['id'].'"
            ';
        }
        $con->query($query);

        $con->query('delete from tbl_codigoBarras where produto = '.$_POST['id']);

        $prodId = $_POST['id'];
        foreach($_POST['codigos'] as $codigo){
            $con->query('insert into tbl_codigoBarras(produto,codigo) values("'.$prodId.'","'.$codigo.'")');
        }

        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_produtos set status = 0 where id = '.$_GET['del']);
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

    function selecionaGrupo(self){
        $('#grupoUnidadeNome').val($(self).val());
        $('#unBase').val(0);
    }

    function gerarCodBarras(){
        const codBarras = ('0000000000000' + $('#referencia').val()).slice(-13);
        $('#codigoBarras').val(codBarras);
    }

    function addCodBarras(){
        const codigo = $('#codigoBarras').val();
        $('#codigosBarras').prepend('<tr><td class="border-bottom">'+codigo+'<input type="hidden" name="codigos[]" value="'+codigo+'"></td><td class="text-right"><span class="btn btn-danger" onclick="deletarLinha(this)"><i class="fas fa-trash"></i></span></td></tr>');
    }

    function deletarLinha(self){
        $($(self).parent().parent()).remove();
    }

    function atvPromocao(){
        $('.promocao').toggle();
    }

    function mostraSubgrupo(self){
        $.get('core/ajax/getSubgrupo.php?grupo='+$(self).val(),function(resp){
            $('#subgrupo').empty();
            $('#subgrupo').append(resp);
        });

    }

    function mostrarCest(self){
        $('#cest').val('');
        $.get('core/ajax/cest.php?ncm='+$(self).val(),resp => {
            $('#listacest').empty();
            $('#listacest').append(resp);
        });
    }

    $(document).ready(function(){
        $("#campoPesquisa").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tablePrint tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $('.nav-link[data-target]').click(function(){
            $('.nav-link[data-target]').removeClass('active');
            $('.tab-pane').removeClass('active');
            $('.tab-pane').addClass('fade');
            $(this).addClass('active');
            $($(this).attr('data-target')).removeClass('fade');
            $($(this).attr('data-target')).addClass('active');

        });
    });
</script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-boxes icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de produtos</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de produtos
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

                    <h5 class="card-title">Produtos</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <?
                        $resp = $con->query('select count(*) as qtd from tbl_produtos')->fetch_assoc();
                        
                        $itensPorPagina = 50;
                        
                        $qtdPaginas = intval($resp['qtd'] / $itensPorPagina);
                        
                        $pagina = isset($_GET['p'])?$_GET['p']:0;
                        
                        $inicioSelect = $pagina * $itensPorPagina;
                        
                        $existeAnterior = !($pagina-1 < 0);
                        $existeProxima = !($pagina+1 > $qtdPaginas);
                    ?>

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:4%">Código</th>
                                <th>Nome</th>
                                <th style="width:8%">Referencia</th>
                                <th style="width:14%">Grupo</th>
                                <th style="width:14%">Preço</th>
                                <th style="width:14%">Estoque</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_produtos where status = 1 order by id desc limit '.$inicioSelect.','.$itensPorPagina.'');

                                while($row = $resp->fetch_assoc()){
                                    
                                    if($row['grupo']){
                                        $gResp = $con->query('select nome from tbl_grupo where id = '.$row['grupo']);
                                        if($gResp->num_rows > 0){
                                            $grupo = $gResp->fetch_assoc();
                                        }
                                        else{
                                            $grupo = "";
                                        }
                                    }
                                    else
                                        $grupo = "";

                                    echo '
                                        <tr>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['referencia'].'</td>
                                            <td>'.$grupo['nome'].'</td>
                                            <td>'.number_format($row['valor'],2,',','.').'</td>
                                            <td>'.number_format($row['estoque'],4,',','.').'</td>
                                            <td class="noPrint text-center"><a href="?edt='.$row['id'].'" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?del='.$row['id'].'" class="btn"><i class="fas fa-trash icon-gradient bg-happy-itmeo"></i></a></td>
                                        </tr>
                                    ';
                                }
                        
                            ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?=$existeAnterior?'':'disabled'?>"><a class="page-link" href="?p=0">Primeira</a></li>
                                <li class="page-item <?=$existeAnterior?'':'disabled'?>"><a class="page-link" href="?p=<?=$pagina-1?>">Anterior</a></li>
                                <li class="page-item <?=$existeProxima?'':'disabled'?>"><a class="page-link" href="?p=<?=$pagina+1?>">Próxima</a></li>
                                <li class="page-item <?=$existeProxima?'':'disabled'?>"><a class="page-link" href="?p=<?=$qtdPaginas?>">Última</a></li>
                            </ul>
                        </div>
                    </div>

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
        <div class="modal-content bg-light">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo produto</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form method="post" autocomplete="off" enctype="multipart/form-data">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_produtos where id = '.$_GET['edt']);
                            $prod = $resp->fetch_assoc();
                        }
                        else{
                            $resp = $con->query('select id from tbl_produtos order by id desc limit 1');
                            $lastid = $resp->fetch_assoc()['id']+1;
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

                    <div class="shadow bg-white p-3 rounded">
                        <label class="mt-3 mb-0"><strong>Informações do produto</strong></label>
                        <div class="divider mt-0"></div>

                        <div class="row">
                            <div class="col-1">
                                <label for="codigo">codigo</label>
                                <input type="text" value="<?= isset($_GET['edt'])?$_GET['edt']:$lastid;?>" class="form-control mb-3" name="codigo" id="codigo" maxlength="" readonly>
                            </div>
                            <div class="col-2">
                                <label for="referencia">Referência</label>
                                <input type="text" value="<?= isset($_GET['edt'])?$prod['referencia']:$lastid;?>" class="form-control mb-3" name="referencia" id="referencia" maxlength="20">
                            </div>
                            <div class="col">
                                <label for="nome">Nome<span class="ml-2 text-danger">*</span></label>
                                <input type="text" value="<?=$prod['nome'];?>" class="form-control mb-3" name="nome" id="nome" maxlength="200" required>
                            </div>
                            <div class="col-2">
                                <label for="un_estoque">Únidade de estoque<span class="ml-2 text-danger">*</span></label>
                                <select class="form-control mb-3" name="un_estoque" id="un_estoque" required>
                                    <option selected disabled>Selecione</option>
                                    <?php
                                        $resp = $con->query('select simbolo,nome,id from tbl_unidades where status = 1 order by grupoNome');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'" '.($prod['unidadeEstoque'] == $row['id']?'selected':'').'>'.$row['simbolo'].' - '.$row['nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="">Estoque</label>
                                <input type="text" value="<?=str_replace('.',',',$prod['estoque']);?>" class="form-control" readonly>
                            </div>
                            
                        </div>

                        <div class="row">

                            <div class="col">
                                <label for="patrimonio">Patrimônio</label>
                                <input type="text" class="form-control" name="patrimonio" id="patrimonio" value="<?=$prod['patrimonio']?>">
                            </div>
                            <div class="col">
                                <label for="serie">Série</label>
                                <input type="text" class="form-control" name="serie" id="serie" value="<?=$prod['serie'];?>">
                            </div>                                                
                            <div class="col">
                                <label for="grupo">Grupo<span class="ml-2 text-danger">*</span></label>
                                <select class="form-control mb-3" name="grupo" id="grupo" onchange="mostraSubgrupo(this)" required>
                                    <option selected disabled>Selecione</option>
                                    <?
                                        $resp = $con->query('select id,nome from tbl_grupo where grupo = 0');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'" '.($row['id'] == $prod['grupo']?'selected':'').'>'.$row['nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="subgrupo">Subgrupo</label>
                                <select name="subgrupo" id="subgrupo" class="form-control">
                                    <?
                                        if($_GET['edt'] && $prod['grupo'] != ""){
                                            $resp = $con->query('select id,nome from tbl_grupo where grupo = '.$prod['grupo']);
                                            echo '<option selected disabled>Selecione</option>';
                                            while($row = $resp->fetch_assoc()){
                                                echo '<option value="'.$row['id'].'" '.($prod['subgrupo'] == $row['id']?'selected':'').'>'.$row['nome'].'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="fornecedor">Fornecedor<span class="ml-2 text-danger">*</span></label>
                                <select class="form-control mb-3" name="fornecedor" id="fornecedor" required>
                                    <option selected disabled>Selecione</option>
                                    <?php
                                        $resp = $con->query('select id,razaoSocial_nome from tbl_clientes where tipoFornecedor = "on" and status = 1');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'" '.($prod['fornecedor'] == $row['id']?'selected':'').'>'.$row['razaoSocial_nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-3 d-flex">
                                <div class="form-check mt-auto mb-auto">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" value="" name="usoConsumo" <?=$prod['usoConsumo']?'checked':'';?>>Uso e consumo
                                    </label>
                                </div>
                            </div>
                            <div class="col d-flex">
                                <div class="form-check mt-auto mb-auto">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" value="" name="comercializavel" <?=$prod['comercializavel']?'checked':'';?>>Comercializável
                                    </label>
                                </div>
                            </div>
                            <div class="col d-flex">
                                <div class="form-check mt-auto mb-auto">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" value="" name="promocao" onchange="atvPromocao()" <?=$prod['promocao']?'checked':'';?>>Promoção
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="tipodeProduto">Tipo de produto<span class="ml-2 text-danger">*</span></label>
                                <select name="tipoDeProduto" id="tipoDeProduto" class="form-control">
                                    <option value="0" <?=$prod['tipoProduto']==0?'selected':'';?>>Acabado</option>
                                    <option value="1" <?=$prod['tipoProduto']==1?'selected':'';?>>Semi acabado</option>
                                    <option value="2" <?=$prod['tipoProduto']==2?'selected':'';?>>Matéria prima</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="descontoMaximo">Desconto máximo</label>
                                <input type="number" value="<?=$prod['descontoMaximo'];?>" step="0.01" min="0" class="form-control" name="descontoMaximo" placeholder="R$ 0,00">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <label for="valor">Preço venda</label>
                                <input type="number" value="<?=$prod['valor'];?>" step="0.01" min="0" class="form-control" name="valor" placeholder="R$ 0,00">
                            </div>
                            <div class="col">
                                <label for="custo">Preço de custo</label>
                                <input type="number" value="<?=$prod['custo'];?>" step="0.01" min="0" class="form-control" name="custo" placeholder="R$ 0,00">
                            </div>
                            <div class="col">
                                <label for="comissao">Comissão (%)</label>
                                <input type="number" value="<?=$prod['comissao'];?>" step="0.01" min="0" class="form-control" name="comissao" placeholder="0,00 %">
                            </div>
                            <div class="col-2">
                                <div class="promocao" <?=$prod['promocao']?'':'style="display:none"'?>>
                                    <label for="valPromocao">Promoção (R$)</label>
                                    <input type="number" value="<?=$prod['valPromocao'];?>" step="0.01" min="0" class="form-control" name="valPromocao">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="promocao" <?=$prod['promocao']?'':'style="display:none"'?>>
                                    <label for="dataPromocao">Data limite promoção</label>
                                    <input type="date" value="<?=$prod['dataPromocao'];?>" class="form-control" name="dataPromocao">
                                </div>
                            </div>                    
                        </div>
                    </div>

                    <div class="divider"></div>

                    <ul class="nav nav-tabs mb-0">
                        <li class="nav-item">
                            <a data-target="#fiscal" class="nav-link active">Fiscal</a>
                        </li>
                        <li class="nav-item">
                            <a data-target="#codBarras" class="nav-link">Código de barras</a>
                        </li>
                        <li class="nav-item">
                            <a data-target="#outros" class="nav-link">Outros</a>
                        </li>
                    </ul>

                    <div class="tab-contents">

                        <div class="tab-pane active" id="fiscal">

                            <? 
                                if($_GET['edt']){
                                    $fisc = $con->query('select * from tbl_classificacaoFiscal where id = '.$prod['classificacaoFiscal'])->fetch_assoc();
                                    
                                    $resp = $con->query('select cfop,descricao from tbl_cfop where id = '.$fisc['cfop']);
                                    if($resp->num_rows > 0)
                                        $cfop = implode(' - ',$resp->fetch_assoc());
                                    
                                    $resp = $con->query('select cest,descricao from tbl_ncm_cest where id = '.$fisc['cest']);
                                    if($resp->num_rows > 0)
                                        $cest = implode(' - ',$resp->fetch_assoc());
                                }
                            ?>

                            <input type="hidden" name="fiscal[id]" value="<?=$fisc['id']?>">

                            <div class="shadow p-3 rounded bg-white mt-0">
                                <label class="mt-3 mb-0"><strong>Informações fiscais</strong></label>
                                <div class="divider mt-0"></div>

                                <div class="row">
                                    <div class="col">
                                        <label for="cfop">CFOP</label>
                                        <input type="text" class="form-control mb-3" name="fiscal[cfop]" id="cfop" value="<?=$cfop?>" list="listaCfop" onselect="if($(this).val().search(' - ') > -1)$(this)[0].setSelectionRange(0,0);">
                                        <datalist id="listaCfop">
                                            <?php
                                                $resp = $con->query('select cfop,descricao from tbl_cfop');
                                                while($row = $resp->fetch_assoc()){
                                                    echo '<option value="'.implode(' - ',$row).'">';
                                                }
                                            ?>
                                        </datalist>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        <label for="ncm">NCM</label>
                                        <input type="text" class="form-control mb-3" name="fiscal[ncm]" id="ncm" list="listancm" value="<?=$fisc['ncm']?>" maxlength="10" onchange="mostrarCest(this)">
                                    </div>
                                    <datalist id="listancm">
                                        <?php
                                            $resp = $con->query('select ncm from tbl_ncm_cest group by ncm order by ncm');
                                            while($row = $resp->fetch_assoc()){
                                                echo '<option value="'.$row['ncm'].'">';
                                            }
                                        ?>
                                    </datalist>
                                    <div class="col">
                                        <label for="cest">CEST</label>
                                        <input type="text" class="form-control mb-3" value="<?=$cest?>" name="fiscal[cest]" id="cest" list="listacest" autocomplete="off" onselect="if($(this).val().search(' - ') > -1)$(this)[0].setSelectionRange(0,0);">
                                        <datalist id="listacest"></datalist>
                                    </div>
                                </div>
                            </div>

                            <div class="shadow p-3 rounded bg-white mt-4">
                                <label class="mt-3 mb-0"><strong>Complemento</strong></label>
                                <div class="divider mt-0"></div>

                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="origem">Origem</label>
                                        <select value="" class="form-control mb-3" name="fiscal[origem]" id="origem">
                                            <option value="0" <?php echo $fisc['origem'] == 0?'selected':'';?>>0 - Nacional: exceto as indicadas nos códigos 3 a 5</option>
                                            <option value="1" <?php echo $fisc['origem'] == 1?'selected':'';?>>1 - Estrangeira: Importação direta, exceto a indicada no código 6</option>
                                            <option value="2" <?php echo $fisc['origem'] == 2?'selected':'';?>>2 - Estrangeira: Adquirida no mercado interno, exceto a indicada no código 7</option>
                                            <option value="3" <?php echo $fisc['origem'] == 3?'selected':'';?>>3 - Nacional: mercadoria ou bem com Conteúdo de Importação superior a 40%</option>
                                            <option value="4" <?php echo $fisc['origem'] == 4?'selected':'';?>>4 - Nacional: cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei nº 288/1967 , e as Leis nºs 8.248/1991, 8.387/1991, 10.176/2001 e 11.484/2007</option>
                                            <option value="5" <?php echo $fisc['origem'] == 5?'selected':'';?>>5 - Nacional: mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%</option>
                                            <option value="6" <?php echo $fisc['origem'] == 6?'selected':'';?>>6 - Estrangeira: Importação direta, sem similar nacional, constante em lista de Resolução Camex e gás natura</option>
                                            <option value="7" <?php echo $fisc['origem'] == 7?'selected':'';?>>7 - Estrangeira: adquirida no mercado interno, sem similar nacional, constante em lista de resoluções Camex e gás natural</option>
                                            <option value="8" <?php echo $fisc['origem'] == 8?'selected':'';?>>8 - Nacional: mercadoria ou bem com conteúdo de importação superior a 70%</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="cst">Código ST</label>
                                        <select class="form-control mb-3" name="fiscal[cst]" id="cst">
                                            <?php
                                                $conf = $con->query('select crt from tbl_configuracao')->fetch_assoc();
                                                $crt = $conf['crt'] == 0? 1: 0;
                                                $resp = $con->query('select * from tbl_cst where simples = '.$crt);
                                                while($row = $resp->fetch_assoc()){
                                                    echo '<option value="'.$row['id'].'" '.($row['id'] == $fisc['cst']? 'selected':'').'>'.$row['codigo'].' - '.$row['descricao'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="icms_interno">ICMS %</label>
                                        <input type="number" value="<?=$fisc['icms_interno']?>" class="form-control mb-3" name="fiscal[icms_interno]" id="icms_interno" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="aliq_ipi">IPI %</label>
                                        <input type="number" value="<?=$fisc['aliq_ipi']?>" class="form-control mb-3" name="fiscal[aliq_ipi]" id="aliq_ipi" placeholder="0,00 %">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label for="cst_ipi">CST IPI</label>
                                        <select class="form-control mb-3" name="fiscal[cst_ipi]" id="cst_ipi">
                                            <option value="00" <?php echo $fisc['cst_ipi'] == '00'?'selected':'';?>>00</option>
                                            <option value="01" <?php echo $fisc['cst_ipi'] == '01'?'selected':'';?>>01</option>
                                            <option value="02" <?php echo $fisc['cst_ipi'] == '02'?'selected':'';?>>02</option>
                                            <option value="03" <?php echo $fisc['cst_ipi'] == '03'?'selected':'';?>>03</option>
                                            <option value="04" <?php echo $fisc['cst_ipi'] == '04'?'selected':'';?>>04</option>
                                            <option value="05" <?php echo $fisc['cst_ipi'] == '05'?'selected':'';?>>05</option>
                                            <option value="49" <?php echo $fisc['cst_ipi'] == '49'?'selected':'';?>>49</option>
                                            <option value="50" <?php echo $fisc['cst_ipi'] == '50'?'selected':'';?>>50</option>
                                            <option value="51" <?php echo $fisc['cst_ipi'] == '51'?'selected':'';?>>51</option>
                                            <option value="52" <?php echo $fisc['cst_ipi'] == '52'?'selected':'';?>>52</option>
                                            <option value="53" <?php echo $fisc['cst_ipi'] == '53'?'selected':'';?>>53</option>
                                            <option value="54" <?php echo $fisc['cst_ipi'] == '54'?'selected':'';?>>54</option>
                                            <option value="55" <?php echo $fisc['cst_ipi'] == '55'?'selected':'';?>>55</option>
                                            <option value="99" <?php echo $fisc['cst_ipi'] == '99'?'selected':'';?>>99</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="reducao_bc_icms">Redução BC ICMS %</label>
                                        <input type="number" value="<?=$fisc['reducao_bc_icms']?>" class="form-control mb-3" name="fiscal[reducao_bc_icms]" id="reducao_bc_icms" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="cst_pis">CST PIS</label>
                                        <select class="form-control mb-3" name="fiscal[cst_pis]" id="cst_pis">
                                            <?
                                                $lista = array('01','02','03','04','05','06','07','08','09','49','50',
                                                    '51','52','53','54','55','56','60','61','62','63','64','65','66','67',
                                                    '70','71','72','73','74','75','98','99'
                                                );
                                                for($i=0; $i < sizeof($lista); $i++){
                                                    echo '<option value="'.$lista[$i].'" '.($fisc['cst_pis'] == $lista[$i]? 'selected':'').'>'.$lista[$i].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="cst_cofins">CST COFINS</label>
                                        <select class="form-control mb-3" name="fiscal[cst_cofins]" id="cst_cofins">
                                            <?
                                                $lista = array('01','02','03','04','05','06','07','08','09','49','50',
                                                    '51','52','53','54','55','56','60','61','62','63','64','65','66','67',
                                                    '70','71','72','73','74','75','98','99'
                                                );
                                                for($i=0; $i < sizeof($lista); $i++){
                                                    echo '<option value="'.$lista[$i].'" '.($fisc['cst_cofins'] == $lista[$i]? 'selected':'').'>'.$lista[$i].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="aliq_pis">PIS %</label>
                                        <input type="number" value="<?=$fisc['aliq_pis']?>" class="form-control mb-3" name="fiscal[aliq_pis]" id="aliq_pis" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="aliq_cof">COFINS %</label>
                                        <input type="number" value="<?=$fisc['aliq_cof']?>" class="form-control mb-3" name="fiscal[aliq_cof]" id="aliq_cof" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="aliq_ii">II %</label>
                                        <input type="number" value="<?=$fisc['aliq_II']?>" class="form-control mb-3" name="fiscal[aliq_ii]" id="aliq_ii" placeholder="0,00 %">
                                    </div>
                                </div>
                            </div>

                            <div class="shadow p-3 rounded bg-white mt-4">
                                <label class="mt-3 mb-0"><strong>Substituição tributária</strong></label>
                                <div class="divider mt-0"></div>

                                <div class="row">
                                    <div class="col">
                                        <label for="icms">ICMS %</label>
                                        <input type="number" value="<?=$fisc['icms']?>" class="form-control mb-3" name="fiscal[icms]" id="icms" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="mva">MVA %</label>
                                        <input type="number" value="<?=$fisc['mva']?>" class="form-control mb-3" name="fiscal[mva]" id="mva" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="red_bc">Red. BC %</label>
                                        <input type="number" value="<?=$fisc['red_bc']?>" class="form-control mb-3" name="fiscal[red_bc]" id="red_bc" placeholder="0,00 %">
                                    </div>
                                    <div class="col">
                                        <label for="ret_st">Ret. ST %</label>
                                        <input type="number" value="<?=$fisc['ret_st']?>" class="form-control mb-3" name="fiscal[ret_st]" id="ret_st" placeholder="0,00 %">
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="tab-pane fade shadow p-3 rounded bg-white mt-0" id="codBarras">
                            <div class="row">
                                <div class="col-4">
                                    <label for="codigoBarras">Código de Barras</label>
                                    <div class="input-group d-flex">
                                        <input type="text" value="<?=str_pad($prod['codBarras'],13,'0',STR_PAD_LEFT);?>" class="form-control" maxlength="14" name="codigoBarras" id="codigoBarras">
                                        <div class="input-group-append">
                                            <span class="btn btn-primary" onclick="addCodBarras()">Adicionar</span>
                                        </div>
                                        <span class="btn btn-link mt-auto mb-auto" onclick="gerarCodBarras()">Gerar</span>
                                    </div>
                                    
                                    <div class="mt-2 bg-light" id="campCodigoBarras">
                                        <table class="table" id="codigosBarras">
                                            <?
                                                $resp = $con->query('select * from tbl_codigoBarras where produto = '.$_GET['edt']);
                                                if($resp->num_row > 0)
                                                    while($row = $resp->fetch_assoc()){
                                                        echo '
                                                            <tr>
                                                                <td class="border-bottom">
                                                                    '.$row['codigo'].'
                                                                    <input type="hidden" name="codigos[]" value="'.$row['codigo'].'">
                                                                </td>
                                                                <td class="text-right">
                                                                    <span class="btn btn-danger" onclick="deletarLinha(this)">
                                                                        <i class="fas fa-trash"></i>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        ';
                                                    }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade shadow p-3 rounded bg-white mt-0" id="outros">
                            <div class="row">
                                <div class="col">
                                    <label for="descricao">Descricao<span class="ml-2 text-danger">*</span></label>
                                    <!--<input type="text" value="" class="form-control mb-3" name="descricao" id="descricao" maxlength="" required>-->
                                    <textarea class="form-control mb-3" style="resize:none;" rows="5" name="descricao"><?=$prod['descricao'];?></textarea>
                                </div>
                                <div class="col">
                                    <label for="imagem">Imagem</label>
                                    <input type="file" class="form-control p-0" name="imagem" accept="image/*">
                                    <input type="hidden" name="imagemAntiga" value="<?=$prod['imagem'];?>">
                                </div>
                            </div>
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