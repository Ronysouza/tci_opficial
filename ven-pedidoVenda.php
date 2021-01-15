<?php
if(isset($_GET['iLinhas'])){
    setcookie('iLinhas',$_GET['iLinhas']);
    $_COOKIE['iLinhas'] = $_GET['iLinhas'];
}
if(isset($_GET['display'])){
    setcookie('display',$_GET['display']);
    $_COOKIE['display'] = $_GET['display'];
}

if(isset($_COOKIE['carrinho'])){
    $carrinho = (array) json_decode($_COOKIE['carrinho']);
}
else{
    $carrinho = array();
}
if($_POST['id']){
    $carrinho['prod-'.$_POST['id']] = isset($carrinho['prod-'.$_POST['id']])? $carrinho['prod-'.$_POST['id']] + $_POST['qtd'] : $_POST['qtd'];
    setcookie('carrinho',json_encode($carrinho));
}

if(isset($_GET['carDel'])){
    unset($carrinho['prod-'.$_GET['carDel']]);
    setcookie('carrinho',json_encode($carrinho));
    echo "<script>location.href='?s'</script>";
}

$qtdCarrinho = sizeof($carrinho);

include('header.php');
?>

<?php

if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];
    if($cmd == "add"){
        $query = 'INSERT INTO `tbl_pedido`(`data`, `hora`, `cliente`, `remessa`, `subtotal`, `desconto`, `acrescimo`, `total`, '.($_POST['vencimento'] != ''?'`vencimento`,':'').'`pago`,`formaPagamento`,`condicao`) VALUES (
            "'.$_POST['data'].'",
            "'.$_POST['hora'].'",
            "'.$_POST['cliente'].'",
            "'.($_POST['remessa']=='null'?0:$_POST['remessa']).'",
            "'.$_POST['subtotal'].'",
            "'.($_POST['desconto'] | 0).'",
            "'.($_POST['acrescimo'] | 0).'",
            "'.$_POST['total'].'",
            '.($_POST['vencimento'] != ''?'"'.$_POST['vencimento'].'",':'').'
            0,
            "'.$_POST['formaPagamento'].'",
            "'.$_POST['condicao'].'"
        )';
        $con->query($query);
        $lastid = $con->insert_id;
        $carrinho = (array)json_decode($_COOKIE['carrinho']);
        $carrinhoKeys = array_keys($carrinho);
        foreach($carrinhoKeys as $key){
            $resp = $con->query('select id,valor from tbl_produtos where id = '.explode('-',$key)[1])->fetch_assoc();
            $query = 'INSERT INTO `tbl_pedidoItem`(`produto`, `quantidade`, `preco`, `total`, `pedido`) VALUES (
                "'.$resp['id'].'",
                "'.$carrinho[$key].'",
                "'.$resp['valor'].'",
                "'.($resp['valor']*$carrinho[$key]).'",
                "'.$lastid.'"
            )';
            $con->query($query);
            $con->query('update tbl_produtos set estoque = estoque - '.$carrinho[$key].' where id = '.$resp['id']);
            $con->query('INSERT INTO `tbl_estoque`(`quantia`, `produto`, `local`, `operacao`, `motivo`, `data`) VALUES (
                "'.$carrinho[$key].'",
                "'.$resp['id'].'",
                "0",
                "s",
                "Venda no sistema",
                "'.date('Y-m-d').'"
            )');
        }
        echo "<script>location.href='ven-pedido.php?d';</script>";
    }
}

$pedidosCarrinho = 0;

?>
<script>
    $(document).ready(function(){
        $("#campoPesquisa").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".produtos").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        $("#desconto").change(function(resp){
            let valor = parseFloat($(resp.currentTarget).val());
            let subtotal = parseFloat($('#subtotal').val());
            let acrescimo = parseFloat($('#acrescimo').val()) | 0;
            $('#total').val((subtotal-valor)+acrescimo);
        });
        $("#acrescimo").change(function(resp){
            let valor = parseFloat($(resp.currentTarget).val());
            let subtotal = parseFloat($('#subtotal').val());
            let desconto = parseFloat($('#desconto').val()) | 0;
            $('#total').val((subtotal-desconto)+valor);
        })
    });
    function finalizar(){
        location.href="?finalizar="+$('#selecionarCliente').val()+'&remessa='+$('#selecionaRemessa').val();
    }
    function selectRemessa(self){
        location.href="?remessa="+$(self).val();
        document.cookie='remessa='+$(self).val()
    }
</script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading btn" onclick="$('#tblCarrinho').toggle('slow');">

            <div class="page-title-icon">
                <i class="fas fa-shopping-basket icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Carrinho</span>
                <div class="page-title-subheading">
                    Total de<span class="badge badge-primary"><?=$qtdCarrinho?></span> itens no carrinho
                </div>
            </div>

        </div>
        
        <div class="page-title-actions">

            <a onclick="finalizar()">
                <button class="btn-shadow mr-3 btn btn-info" id="btn-modal" type="button">
                    Finalizar
                </button>
            </a>
            <a href="ven-pedido.php">
                <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button">
                    Voltar
                </button>
            </a>

        </div>
    </div>

    <div class="row mt-4 p-2 bg-light rounded shadow" id="tblCarrinho" style="display:none">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:18%">Nome</th>
                    <th>Valor UN</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?
                    $keys = array_keys($carrinho);
                    $totalPedido = 0;
                    foreach($keys as $key){
                        $resp = $con->query('select * from tbl_produtos where id = '.explode('-',$key)[1]);
                        $row = $resp->fetch_assoc();
                        $totalPedido += $row['valor']*$carrinho[$key];
                        echo '
                            <tr>
                                <td>'.$row['nome'].'</td>
                                <td>R$ '.number_format($row['valor'],2,',','.').'</td>
                                <td>'.$carrinho[$key].'</td>
                                <td>R$ '.number_format($row['valor']*$carrinho[$key],2,',','.').'</td>
                                <td><a class="btn" href="?carDel='.$row['id'].'"><i class="fa fa-trash"></i></a></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
            <tfooter>
                <tr>
                    <td colspan="5"><strong>Total R$ <?=number_format($totalPedido,2,',','.')?></strong></td>
                </tr>
            </tfooter>
        </table>
    </div>
</div>
<!-- fim cabeçalho-->

<!-- conteúdo -->
<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col">
                            <select class="form-control" id="selecionarCliente" onchange="document.cookie='cliente='+$(this).val()">
                                <option selected disabled>Selecione o cliente</option>
                                <?php
                                    $resp = $con->query('select id, razaoSocial_nome from tbl_clientes where tipoCliente="on" and status = 1');
                                    while($row = $resp->fetch_assoc()){
                                        $selected = $_COOKIE['cliente'] == $row['id']? 'selected':'';
                                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['razaoSocial_nome'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" id="selecionaRemessa" onchange="selectRemessa(this)">
                                <option selected value="0">Selecione a remessa</option>
                                <?
                                    $resp = $con->query('select id,descricao from tbl_remessa where status = 0 and cliente in (-1,'.$_CONF['usuario']['id'].')');
                                    while($row = $resp->fetch_assoc()){
                                        $selected = $_COOKIE['remessa'] == $row['id']? 'selected':'';
                                        echo '<option value="'.$row['id'].'"  '.$selected.'>'.$row['descricao'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!--<div class="col">
                            <?if($_COOKIE['display']=='grid'):?>
                                <a class="btn text-center" href="?display=list"><h3><i class="fas fa-th"></i></h3></a>
                            <?else:?>
                                <a class="btn text-center" href="?display=grid"><h3><i class="fas fa-bars"></i></h3></a>
                            <?endif;?>
                        </div>-->
                        <div class="col-6">
                            <input type="text" class="mb-2 form-control" placeholder="Pesquisar" id="campoPesquisa">
                        </div>
                        <div class="col">    
                            <select class="form-control" onchange="location.href='?iLinhas='+$(this).val();">
                                <option selected disabled>Itens por linha</option>
                                <option value="7">7</option>
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>

                    <div class="content venda">
                        <?
                            if($_GET['remessa'] != 0){
                                $resp = $con->query('select * from tbl_produtos where status = 1 and id in (select produto from tbl_remessaItem where remessa = '.$_GET['remessa'].')');
                            }
                            else{
                                $resp = $con->query('select * from tbl_produtos where status = 1');
                            }
                            $itens = $resp->num_rows;
                            $itensLinha = isset($_COOKIE['iLinhas'])?$_COOKIE['iLinhas']:7;
                            $linhas = intval(($itens-1)/$itensLinha)+1;
                            
                            while($row = $resp->fetch_assoc()){
                                echo '
                                    <div class="produtos">
                                        <div class="row mb-3 pb-2 border-bottom onlyPhone">
                                            <div class="col '.($row['imagem']==""?'d-none':'d-flex').'">
                                                <img class="m-auto" src="'.$row['imagem'].'" height="40">
                                            </div>
                                            <div class="col ml-4">
                                                <strong>'.$row['referencia'].'</strong>
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title">'.$row['nome'].'</h4>
                                            </div>
                                            <div class="col onlyPhone d-flex">
                                                <strong class="ml-auto mt-auto mb-auto mr-2">R$ '.number_format($row['valor'],2,',','.').'</strong>
                                            </div>
                                            <div class="col d-flex">
                                                <form method="post">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <input type="submit" class="btn btn-dark" value="Adicionar">
                                                        </div>
                                                        <input type="number" class="form-control" name="qtd" value="1">
                                                        <input type="hidden" value="'.$row['id'].'" name="id">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row onlyDesktop">
                                            <div class="col '.($row['imagem']==""?'d-none':'d-flex').'">
                                                <img class="m-auto" src="'.$row['imagem'].'" height="40">
                                            </div>
                                            <div class="col">
                                                <h4 class="card-title text-center">'.$row['nome'].'</h4>
                                            </div>
                                        </div>
                                        <div class="row onlyDesktop">
                                            <div class="col text-center mb-3">
                                                <strong>'.$row['referencia'].'</strong>
                                            </div>
                                        </div>
                                        <div class="row mb-3 pb-2 border-bottom onlyDesktop">
                                            <div class="col d-flex">
                                                <strong class="m-auto">R$ '.number_format($row['valor'],2,',','.').'</strong>
                                            </div>
                                            <div class="col d-flex">
                                                <form method="post">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <input type="submit" class="btn btn-dark" value="Adicionar">
                                                        </div>
                                                        <input type="number" class="form-control" name="qtd" value="1">
                                                        <input type="hidden" value="'.$row['id'].'" name="id">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                        ?>
                    
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
                    <input type="hidden" value="add" name="cmd">
                    <?
                        $carrinho = (array) json_decode($_COOKIE['carrinho']);
                        $carrinhoKeys = array_keys($carrinho);
                        
                        $totalProd = 0;
                        foreach($carrinhoKeys as $key){
                            $prodUnVal = $con->query('select valor from tbl_produtos where id = '.explode('-',$key)[1])->fetch_assoc()['valor'];
                            $totalProd += $prodUnVal * $carrinho[$key];
                        }
                    ?>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="data">Data</label>
                            <input type="date" name="data" id="data" class="form-control" value="<?=date('Y-m-d')?>" required>
                        </div>
                        <div class="col-4">
                            <label for="hora">Hora</label>
                            <input type="time" name="hora" id="hora" class="form-control" value="<?=date('H:i:s')?>" required>
                        </div>
                        <div class="col-4">
                            <label for="vencimento">Vencimento</label>
                            <input type="date" name="vencimento" id="vencimento" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3" style="display:none !important">
                        <div class="col">
                            <label for="chaveNota">Chave da nota</label>
                            <input type="text" name="chaveNota" id="chaveNota" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3" style="display:none !important">
                        <div class="col">
                            <label for="documento">Documento</label>
                            <input type="text" name="documento" id="documento" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="formaPagamento">Forma de pagamento</label>
                            <select name="formaPagamento" id="formaPagamento" class="form-control">
                                <option value="0">Dinheiro</option>
                                <option value="1">Cartão de crédito</option>
                                <option value="2">Cartão de débito</option>
                                <option value="3">Cheque</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="condicao">Condição de pagamento</label>
                            <input type="text" name="condicao" id="condicao" maxlength="400" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-right">Subtotal</div>
                        <div class="col">
                            <input type="number" class="form-control" name="subtotal" id="subtotal" value="<?=$totalProd;?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-right">Desconto</div>
                        <div class="col">
                            <input type="number" class="form-control" name="desconto" id="desconto" placeholder="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-right">Acrescimo</div>
                        <div class="col">
                            <input type="number" class="form-control" name="acrescimo" id="acrescimo" placeholder="0">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4 text-right">Total</div>
                        <div class="col">
                            <input type="number" class="form-control" name="total" id="total" value="<?=$totalProd;?>" readonly>
                        </div>
                    </div>

                    <input id="needs-validation" class="d-none" type="submit" value="enviar">
                    <input type="hidden" name="cliente" id="cliente" value="<?=$_GET['finalizar']?>">
                    <input type="hidden" name="remessa" id="remessa" value="<?=$_GET['remessa']?>">

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
<?php if(isset($_GET['finalizar'])) echo "<script>$('#mdl-cliente').modal()</script>"; ?>
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