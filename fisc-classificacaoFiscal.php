<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $cfop = $con->query('select id from tbl_cfop where cfop = "'.explode(' - ',$_POST['cfop'])[0].'"')->fetch_assoc()['id'];
        $cest = $con->query('select id from tbl_ncm_cest where cest = "'.explode(' - ',$_POST['cest'])[0].'"')->fetch_assoc()['id'];

        $query = 'INSERT INTO `tbl_classificacaoFiscal`(`nome`, `descricao`, `ncm`, `cfop`, `cest`, `cst`, `origem`, `icms_interno`, `aliq_ipi`, `cst_ipi`, `reducao_bc_icms`, `cst_pis`, `cst_cofins`, `aliq_pis`, `aliq_cof`, `aliq_II`, `icms`, `mva`, `red_bc`, `ret_st`) VALUES (
            "'.$_POST['nome'].'",
            "'.$_POST['descricao'].'",
            "'.$_POST['ncm'].'",
            "'.$cfop.'",
            "'.$cest.'",
            "'.$_POST['cst'].'",
            "'.$_POST['origem'].'",
            "'.$_POST['icms_interno'].'",
            "'.$_POST['aliq_ipi'].'",
            "'.$_POST['cst_ipi'].'",
            "'.$_POST['reducao_bc_icms'].'",
            "'.$_POST['cst_pis'].'",
            "'.$_POST['cst_cofins'].'",
            "'.$_POST['aliq_pis'].'",
            "'.$_POST['aliq_cof'].'",
            "'.$_POST['aliq_ii'].'",
            "'.$_POST['icms'].'",
            "'.$_POST['mva'].'",
            "'.$_POST['red_bc'].'",
            "'.$_POST['ret_st'].'"
        )';
        $con->query($query);
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        $cfop = $con->query('select id from tbl_cfop where cfop = "'.explode(' - ',$_POST['cfop'])[0].'"')->fetch_assoc()['id'];
        $cest = $con->query('select id from tbl_ncm_cest where cest = "'.explode(' - ',$_POST['cest'])[0].'"')->fetch_assoc()['id'];
        $query = 'UPDATE `tbl_classificacaoFiscal` SET 
            `nome`="'.$_POST['nome'].'",
            `descricao`="'.$_POST['descricao'].'",
            `ncm`="'.$_POST['ncm'].'",
            `cfop`="'.$cfop.'",
            `cest`="'.$cest.'",
            `cst`="'.$_POST['cst'].'",
            `origem`="'.$_POST['origem'].'",
            `icms_interno`="'.$_POST['icms_interno'].'",
            `aliq_ipi`="'.$_POST['aliq_ipi'].'",
            `cst_ipi`="'.$_POST['cst_ipi'].'",
            `reducao_bc_icms`="'.$_POST['reducao_bc_icms'].'",
            `cst_pis`="'.$_POST['cst_pis'].'",
            `cst_cofins`="'.$_POST['cst_cofins'].'",
            `aliq_pis`="'.$_POST['aliq_pis'].'",
            `aliq_cof`="'.$_POST['aliq_cof'].'",
            `aliq_II`="'.$_POST['aliq_ii'].'",
            `icms`="'.$_POST['icms'].'",
            `mva`="'.$_POST['mva'].'",
            `red_bc`="'.$_POST['red_bc'].'",
            `ret_st`="'.$_POST['ret_st'].'" 
            WHERE id = '.$_POST['id'].'
        ';
        $con->query($query);
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_classificacaoFiscal set status = 0 where id = '.$_GET['del']);
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
                <span>Classificação fiscal</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição da classificação fiscal
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

                    <h5 class="card-title">Classificação fiscal</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:12%">Nome</th>
                                <th>Descrição</th>
                                <th style="width:12%">NCM</th>
                                <th style="width:12%">CEST</th>
                                <th style="width:12%">CFOP</th>
                                <th style="width:12%">CST</th>
                                <th class="noPrint" style="width:6%"></th>
                                <th class="noPrint" style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_classificacaoFiscal where status = 1');

                                while($row = $resp->fetch_assoc()){
                                    $cfop = $con->query('select cfop from tbl_cfop where id = '.$row['cfop'])->fetch_assoc()['cfop'];
                                    $cest = $con->query('select cest from tbl_ncm_cest where id = '.$row['cest'])->fetch_assoc()['cest'];
                                    $cst = $con->query('select codigo from tbl_cst where id = '.$row['cst'])->fetch_assoc()['codigo'];
                                    echo '
                                        <tr>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['descricao'].'</td>
                                            <td>'.$row['ncm'].'</td>
                                            <td>'.$cest.'</td>
                                            <td>'.$cfop.'</td>
                                            <td>'.$cst.'</td>
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
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar nova classificação</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_classificacaoFiscal where id = '.$_GET['edt']);
                            $un = $resp->fetch_assoc();
                            
                            $cfop = implode(' - ',$con->query('select cfop,descricao from tbl_cfop where id = '.$un['cfop'])->fetch_assoc());
                            $cest = implode(' - ',$con->query('select cest,descricao from tbl_ncm_cest where id = '.$un['cest'])->fetch_assoc());
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">

                    <div class="row">
                        <div class="col">
                            <label for="codigo">Nome<span class="ml-2 text-danger">*</span></label>
                            <input type="text" value="<?php echo $un['nome']; ?>" class="form-control mb-3" name="nome" id="nome" maxlength="60" required>
                        </div>
                        <div class="col">
                            <label for="codigo">descricao</label>
                            <input type="text" value="<?php echo $un['descricao']; ?>" class="form-control mb-3" name="descricao" id="descricao" maxlength="200">
                        </div>
                    </div>

                    <label class="mt-3 mb-0"><strong>Fiscal</strong></label>
                    <div class="divider mt-0"></div>

                    <div class="row">
                        <div class="col">
                            <label for="cfop">CFOP<span class="ml-2 text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" name="cfop" id="cfop" value="<?php echo $cfop;?>" list="listaCfop" onselect="if($(this).val().search(' - ') > -1)$(this)[0].setSelectionRange(0,0);" required>
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
                            <label for="ncm">NCM<span class="ml-2 text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" name="ncm" id="ncm" list="listancm" value="<?php echo $un['ncm'];?>" onchange="mostrarCest(this)" required>
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
                            <label for="cest">CEST<span class="ml-2 text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" value="<?php echo $cest;?>" name="cest" id="cest" list="listacest" autocomplete="off" onselect="if($(this).val().search(' - ') > -1)$(this)[0].setSelectionRange(0,0);" required>
                            <datalist id="listacest"></datalist>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="origem">Origem</label>
                            <select value="<?php echo $un['origem']; ?>" class="form-control mb-3" name="origem" id="origem" required>
                                <option value="0" <?php echo $un['origem'] == 0?'selected':'';?>>0 - Nacional: exceto as indicadas nos códigos 3 a 5</option>
                                <option value="1" <?php echo $un['origem'] == 1?'selected':'';?>>1 - Estrangeira: Importação direta, exceto a indicada no código 6</option>
                                <option value="2" <?php echo $un['origem'] == 2?'selected':'';?>>2 - Estrangeira: Adquirida no mercado interno, exceto a indicada no código 7</option>
                                <option value="3" <?php echo $un['origem'] == 3?'selected':'';?>>3 - Nacional: mercadoria ou bem com Conteúdo de Importação superior a 40%</option>
                                <option value="4" <?php echo $un['origem'] == 4?'selected':'';?>>4 - Nacional: cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei nº 288/1967 , e as Leis nºs 8.248/1991, 8.387/1991, 10.176/2001 e 11.484/2007</option>
                                <option value="5" <?php echo $un['origem'] == 5?'selected':'';?>>5 - Nacional: mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%</option>
                                <option value="6" <?php echo $un['origem'] == 6?'selected':'';?>>6 - Estrangeira: Importação direta, sem similar nacional, constante em lista de Resolução Camex e gás natura</option>
                                <option value="7" <?php echo $un['origem'] == 7?'selected':'';?>>7 - Estrangeira: adquirida no mercado interno, sem similar nacional, constante em lista de resoluções Camex e gás natural</option>
                                <option value="8" <?php echo $un['origem'] == 8?'selected':'';?>>8 - Nacional: mercadoria ou bem com conteúdo de importação superior a 70%</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="cst">Código ST</label>
                            <select class="form-control mb-3" name="cst" id="cst" required>
                                <?php
                                    $conf = $con->query('select crt from tbl_configuracao')->fetch_assoc();
                                    $crt = $conf['crt'] == 0? 1: 0;
                                    $resp = $con->query('select * from tbl_cst where simples = '.$crt);
                                    while($row = $resp->fetch_assoc()){
                                        echo '<option value="'.$row['id'].'" '.($row['id'] == $un['cst']? 'selected':'').'>'.$row['codigo'].' - '.$row['descricao'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="icms_interno">ICMS %</label>
                            <input type="number" value="<?php echo $un['icms_interno']; ?>" class="form-control mb-3" name="icms_interno" id="icms_interno">
                        </div>
                        <div class="col">
                            <label for="aliq_ipi">IPI %</label>
                            <input type="number" value="<?php echo $un['aliq_ipi']; ?>" class="form-control mb-3" name="aliq_ipi" id="aliq_ipi">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="cst_ipi">CST IPI</label>
                            <select class="form-control mb-3" name="cst_ipi" id="cst_ipi">
                                <option value="00" <?php echo $un['cst_ipi'] == '00'?'selected':'';?>>00</option>
                                <option value="01" <?php echo $un['cst_ipi'] == '01'?'selected':'';?>>01</option>
                                <option value="02" <?php echo $un['cst_ipi'] == '02'?'selected':'';?>>02</option>
                                <option value="03" <?php echo $un['cst_ipi'] == '03'?'selected':'';?>>03</option>
                                <option value="04" <?php echo $un['cst_ipi'] == '04'?'selected':'';?>>04</option>
                                <option value="05" <?php echo $un['cst_ipi'] == '05'?'selected':'';?>>05</option>
                                <option value="49" <?php echo $un['cst_ipi'] == '49'?'selected':'';?>>49</option>
                                <option value="50" <?php echo $un['cst_ipi'] == '50'?'selected':'';?>>50</option>
                                <option value="51" <?php echo $un['cst_ipi'] == '51'?'selected':'';?>>51</option>
                                <option value="52" <?php echo $un['cst_ipi'] == '52'?'selected':'';?>>52</option>
                                <option value="53" <?php echo $un['cst_ipi'] == '53'?'selected':'';?>>53</option>
                                <option value="54" <?php echo $un['cst_ipi'] == '54'?'selected':'';?>>54</option>
                                <option value="55" <?php echo $un['cst_ipi'] == '55'?'selected':'';?>>55</option>
                                <option value="99" <?php echo $un['cst_ipi'] == '99'?'selected':'';?>>99</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="reducao_bc_icms">Redução BC ICMS %</label>
                            <input type="number" value="<?php echo $un['reducao_bc_icms']; ?>" class="form-control mb-3" name="reducao_bc_icms" id="reducao_bc_icms">
                        </div>
                        <div class="col">
                            <label for="cst_pis">CST PIS</label>
                            <select class="form-control mb-3" name="cst_pis" id="cst_pis">
                                <?
                                    $lista = array('01','02','03','04','05','06','07','08','09','49','50',
                                        '51','52','53','54','55','56','60','61','62','63','64','65','66','67',
                                        '70','71','72','73','74','75','98','99'
                                    );
                                    for($i=0; $i < sizeof($lista); $i++){
                                        echo '<option value="'.$lista[$i].'" '.($un['cst_pis'] == $lista[$i]? 'selected':'').'>'.$lista[$i].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="cst_cofins">CST COFINS</label>
                            <select class="form-control mb-3" name="cst_cofins" id="cst_cofins">
                                <?
                                    $lista = array('01','02','03','04','05','06','07','08','09','49','50',
                                        '51','52','53','54','55','56','60','61','62','63','64','65','66','67',
                                        '70','71','72','73','74','75','98','99'
                                    );
                                    for($i=0; $i < sizeof($lista); $i++){
                                        echo '<option value="'.$lista[$i].'" '.($un['cst_cofins'] == $lista[$i]? 'selected':'').'>'.$lista[$i].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="aliq_pis">PIS %</label>
                            <input type="number" value="<?php echo $un['aliq_pis']; ?>" class="form-control mb-3" name="aliq_pis" id="aliq_pis">
                        </div>
                        <div class="col">
                            <label for="aliq_cof">COFINS %</label>
                            <input type="number" value="<?php echo $un['aliq_cof']; ?>" class="form-control mb-3" name="aliq_cof" id="aliq_cof">
                        </div>
                        <div class="col">
                            <label for="aliq_ii">II %</label>
                            <input type="number" value="<?php echo $un['aliq_II']; ?>" class="form-control mb-3" name="aliq_ii" id="aliq_ii">
                        </div>
                    </div>

                    <label class="mt-3 mb-0"><strong>Substituição tributária</strong></label>
                    <div class="divider mt-0"></div>

                    <div class="row">
                        <div class="col">
                            <label for="icms">ICMS %</label>
                            <input type="number" value="<?php echo $un['icms']; ?>" class="form-control mb-3" name="icms" id="icms">
                        </div>
                        <div class="col">
                            <label for="mva">MVA %</label>
                            <input type="number" value="<?php echo $un['mva']; ?>" class="form-control mb-3" name="mva" id="mva">
                        </div>
                        <div class="col">
                            <label for="red_bc">Red. BC %</label>
                            <input type="number" value="<?php echo $un['red_bc']; ?>" class="form-control mb-3" name="red_bc" id="red_bc">
                        </div>
                        <div class="col">
                            <label for="ret_st">Ret. ST %</label>
                            <input type="number" value="<?php echo $un['ret_st']; ?>" class="form-control mb-3" name="ret_st" id="ret_st">
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