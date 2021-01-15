<?php include('header.php'); ?>

<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if(!isset($_FILES['xml']['tmp_name'])) echo '<script>location.href="est-impXml.php?e"</script>';

$arq = simplexml_load_file($_FILES['xml']['tmp_name'])->NFe->infNFe;

$nota = $arq->ide;
$emit = $arq->emit;
$dest = $arq->dest;
$itens = $arq->det;

$empresa = $con->query('select * from tbl_configuracao where id = '.$_COOKIE['empresa'])->fetch_assoc();

#if(!(str_replace(array('.','/','-'),'',$empresa['cnpj']) == $dest->CNPJ))echo '<script>location.href="est-impXml.php?e"</script>';

$cliente = $con->query('select * from tbl_clientes where cnpj_cpf = "'.$emit->CNPJ.'"');
$cadastrado = true;
$idCliente = -1;
if($cliente->num_rows == 0){
    $query = 'insert into tbl_clientes(tipoPessoa,razaoSocial_nome,cnpj_cpf,logradouro,numero,complemento,bairro,cidade,cep,telefoneEmpresa,estado,tipoCliente,tipoFornecedor,tipoFuncionario,tipoTecnico) values(
        "PJ",
        "'.sqlEscape($emit->xNome).'",
        "'.sqlEscape($emit->CNPJ).'",
        "'.sqlEscape($emit->enderEmit->xLgr).'",
        "'.sqlEscape($emit->enderEmit->nro).'",
        "'.sqlEscape($emit->enderEmit->xCpl).'",
        "'.sqlEscape($emit->enderEmit->xBairro).'",
        "'.sqlEscape($emit->enderEmit->xMun).'",
        "'.sqlEscape($emit->enderEmit->CEP).'",
        "'.sqlEscape($emit->enderEmit->fone).'",
        "'.sqlEscape($emit->enderEmit->UF).'",
        "",
        "on",
        "",
        ""
    )';
    $con->query($query);
    $cadastrado = false;
    $idCliente = $con->insert_id;
}
else{
    $idCliente = $cliente->fetch_assoc()['id'];
}

$attr = (array) $arq;

$resp = $con->query('select id from tbl_impXmlLog where nNota = "'.$nota->nNF.'" and sNota = "'.$nota->serie.'"');
$impChecaLog = ($resp->num_rows > 0)?true:false;

?>
<script>
    function mostrarLinha(rId,self){
        $('#'+rId).toggle();
        $(self).children().toggle();
    }

    function salvarProduto(rId){
        let campos = $('.'+rId+' .cadastroProd');
        $.cookie.json = true;
        let prod = $.cookie('produtos') || [];
        
        let req = $('.'+rId+' .cadastroProd[required]');
        let flag = false;
        for(item of req){
            if($(item).val() == ""){
                $(item).css('border-color','red');
                flag = true;
            }
            else{
                $(item).css('border-color','#ced4da');
            }
        }
        if(flag)return;
        
        data = Object();
        for(let i = 0; i < campos.length; i++){
            data[$(campos[i]).attr('id')] = $(campos[i]).is(':checked')? '1':$(campos[i]).val();
        }

        data.add = rId.split('-').length == 1?false:true;

        prod.push(data);
        $.cookie('produtos',prod);

        rId = rId.split('-')[0];

        $('#'+rId).toggle();
        $($('td[linha="'+rId+'"]')[0]).empty();

        if($('td[linha] button').length == 0){
            $('#sucessoRetornar').toggle();
        }
    }

    function setValComp(self){
        $('#valVenda').attr('placeholder',$('option[prodId="'+$(self).val().split(' - ')[0]+'"]').attr('valor'));
        
        if($(self).val().search(' - ') > -1)
            $(self)[0].setSelectionRange(0,0);
    }

    function removerPontos(self){
        $(self).val($(self).val().replace(/[\.-\s]/g,''));
    }
</script>

<!-- conteúdo -->
<div class="content rounded bg-white p-2 shadow">

    <div class="row mt-2">
        <div class="col" id="importarRetornar">
            <a class="btn btn-<?=$impChecaLog?'success':'dark'?>" href="est-impXml.php"><i class="fas fa-angle-double-left text-light"></i></a>
            <a class="btn btn-success text-white" id="sucessoRetornar" style="display:none !important" href="est-impXml.php?imp">
                <span class="mt-auto mb-auto"><strong>Importar</strong></span>
            </a>
            <?=$impChecaLog?'<strong><span class="bg-success p-1 rounded text-white ml-4">Nota importada!</span></strong>':''?>
        </div>
        <div class="col d-flex">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <h4>Nota: <strong><?=$nota->nNF?></strong> série <strong><?=$nota->serie?></strong></h4>
        </div>
        <div class="col text-right">
            <h4>Data: <strong><?=date('d/m/Y',strtotime($nota->dhEmi))?></strong></h4>
        </div>
    </div>
    <div class="divider"></div>

    <div class="row mb-3">
        <div class="col">
            <strong>Fornecedor</strong><?if($cadastrado):?><span class="badge ml-3 badge-info">Cadastrado</span><?else:?><span class="badge ml-3 badge-success">novo</span><?endif;?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            Nome: <span><strong><?=$emit->xNome?></strong></span>
        </div>
        <div class="col">
            CNPJ: <span><strong><?=$emit->CNPJ?></strong></span>
        </div>
        <div class="col">
            IE: <span><strong><?=$emit->IE?></strong></span>
        </div>
        <ddiv class="col">
            Contato: <span><strong>(<?=substr($emit->enderEmit->fone,0,2)?>) <?=substr($emit->enderEmit->fone,2)?></strong></span>
        </ddiv>
    </div>
    <div class="row">
        <div class="col-4">
            Logradouro: <span><strong><?=$emit->enderEmit->xLgr?></strong></span>
        </div>
        <div class="col">
            Número: <span><strong><?=$emit->enderEmit->nro?></strong></span>
        </div>
        <div class="col">
            Complemento: <span><strong><?=$emit->enderEmit->xCpl?></strong></span>
        </div>
        <div class="col">
            Bairro: <span><strong><?=$emit->enderEmit->xBairro?></strong></span>
        </div>
        <div class="col">
            Cidade: <span><strong><?=$emit->enderEmit->xMun?>/<?=$emit->enderEmit->UF?></strong></span>
        </div>
    </div>
    <div class="divider"></div>

    <div class="row mb-3">
        <div class="col">
            <strong>Itens</strong>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-4">
            <label for="localestoque">Local</label>
            <select class="form-control cadastroProd" onchange="document.cookie='lEstoque='+$(this).val()">
                <option selected disabled>Selecione</option>
                <?
                    $resp = $con->query('select id,nome from tbl_locaisEstoque where status = 1 and empresa = '.$_COOKIE['empresa']);
                    if($resp){
                        while($row = $resp->fetch_assoc()){
                            echo '<option value="'.$row['id'].'" '.($_COOKIE['lEstoque'] == $row['id']?'select':'').'>'.$row['nome'].'</option>';
                        }
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table striped">
            <thead>
                <tr>
                    <th></th>
                    <th style="width:10%">Referência</th>
                    <th>Nome</th>
                    <th style="width:12%">Valor unidade</th>
                    <th style="width:10%">Quantidade</th>
                    <th style="width:8%">Unidade</th>
                    <th style="width:12%">Valor Total</th>
                </tr>
            </thead>
                <tbody>
                    <?
                        $impCompleta = true;
                        foreach($itens as $item){
                            $prod = $item->prod;
                            $imposto = (array)$item->imposto->ICMS;
                            $imposto = $imposto[array_keys($imposto)[0]];
                            $rId = uniqid();
                            
                            $cad = $con->query('select * from tbl_produtos where nome like "%'.$prod->cProd.'%"')->fetch_assoc();
                            $cadastrar = !isset($cad);
                            
                            
                            echo '
                                <tr>
                                    <td linha="'.$rId.'"><button class="btn btn-dark" onclick="mostrarLinha(\''.$rId.'\',this)" '.($impChecaLog?'style="display:none"':'').'><i class="fas fa-caret-down"></i><i class="fas fa-caret-right" style="display:none"></i></button></td>
                                    <td>'.$prod->cProd.'</td>
                                    <td>'.$prod->xProd.'</td>
                                    <td>R$ '.number_format(floatval($prod->vUnCom),2,',','.').'</td>
                                    <td>'.number_format(floatval($prod->qCom),4,',','.').'</td>
                                    <td>'.$prod->uCom.'</td>
                                    <td>R$ '.number_format(floatval($prod->vProd),2,',','.').'</td>
                                </tr>
                                
                                <tr style="display:none" id="'.$rId.'" class="bg-light p-2">
                                    <td colspan="7">';
                    ?>
                                        <ul class="nav nav-tabs">
                                            <?if($cadastrar):?>
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#new-<?=$rId?>">Novo</a>
                                            </li>
                                            <?endif;?>
                                            <li class="nav-item">
                                                <a class="nav-link <?=$cadastrar?'':'active';?>" data-toggle="tab" href="#add-<?=$rId?>">Adicionar</a>
                                            </li>
                                        </ul>

                                        <?if($cadastrar):?>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="new-<?=$rId?>">

                                                <form autocomplete="off" class="<?=$rId?>">
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <strong><?=($cad?'Importar':'Adicionar')?> produto</strong>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3" >
                                                        <input class="cadastroProd" type="hidden" name="referencia" id="referencia" value="">
                                                        <!--<div class="col-2">
                                                            <label for="referencia">Referência</label>
                                                            
                                                        </div>-->
                                                        <div class="col">
                                                            <label for="nome">Nome</label>
                                                            <input class="form-control cadastroProd" type="text" name="nome" id="nome" value="<?=$prod->xProd?> - <?=$prod->cProd?>" required>
                                                        </div>
                                                        <div class="col">
                                                            <label for="unEstoque">Únidade de estoque</label>
                                                            <select class="form-control cadastroProd" name="unEstoque" id="unEstoque">
                                                                <?
                                                                    $sResp = $con->query('select id from tbl_unidades where simbolo = "'.$prod->uCom.'";');
                                                                    if($sResp->num_rows == 0){
                                                                        $con->query('insert into tbl_unidades(nome,status,simbolo) values("'.$prod->uCom.'",1,"'.$prod->uCom.'");');
                                                                    }

                                                                    $resp = $con->query('select nome,simbolo,id from tbl_unidades where status = 1');
                                                                    while($row = $resp->fetch_assoc()){
                                                                        echo '<option value="'.$row['id'].'" '.(strtolower($row['simbolo']) == strtolower($prod->uCom)?'selected':'').'>'.$row['simbolo'].' - '.$row['nome'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <label for="valor">Preço de venda</label>
                                                            <input class="form-control cadastroProd" type="number" step="0.01" name="valor" id="valor" value="">
                                                        </div>
                                                        <div class="col">
                                                            <label for="grupo">Grupo<span class="ml-2 text-danger">*</span></label>
                                                            <select class="form-control mb-3 cadastroProd" name="grupo" id="grupo">
                                                                <option selected disabled>Selecione</option>
                                                                <?
                                                                    $resp = $con->query('select id,nome from tbl_grupo where grupo = 0;');
                                                                    while($row = $resp->fetch_assoc()){
                                                                        echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col">
                                                            <label for="tipodeProduto">Tipo de produto<span class="ml-2 text-danger">*</span></label>
                                                            <select name="tipoDeProduto" id="tipoDeProduto" class="form-control cadastroProd">
                                                                <option value="0" selected>Acabado</option>
                                                                <option value="1">Semi acabado</option>
                                                                <option value="2">Matéria prima</option>
                                                            </select>
                                                        </div>
                                                        <div class="col">
                                                            <label for="barras">Barras</label>
                                                            <input class="form-control cadastroProd" type="text" name="barras" id="barras" value="<?=$prod->cEAN != "SEM GTIN"?$prod->cEAN:'';?>" maxlength="13">
                                                        </div>
                                                        <div class="col-2 d-flex">
                                                            <div class="form-check mt-auto mb-auto">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input cadastroProd" value="" id="usoConsumo" name="usoConsumo" <?=$prod['usoConsumo']?'checked':'';?>>Uso e consumo
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 d-flex">
                                                            <div class="form-check mt-auto mb-auto">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input cadastroProd" value="" id="comercializavel" name="comercializavel" <?=$prod['comercializavel']?'checked':'';?>>Comercializável
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <input type="hidden" name="fornecedor" id="fornecedor" value="<?=$idCliente;?>" class="cadastroProd">
                                                        <input type="hidden" name="estoque" id="estoque" value="<?=$prod->qCom?>" class="cadastroProd">
                                                        <input type="hidden" name="notaId" id="notaId" value="<?=$nota->nNF?>" class="cadastroProd">
                                                        <input type="hidden" name="notaSerie" id="notaSerie" value="<?=$nota->serie?>" class="cadastroProd">
                                                        <input type="hidden" name="cfopEntrada" id="cfopEntrada" value="<?=$prod->CFOP?>" class="cadastroProd">
                                                        <input type="hidden" name="dataNota" id="dataNota" value="<?=$nota->dhEmi?>" class="cadastroProd">
                                                        <input type="hidden" name="chaveNFe" id="chaveNFe" value="<?=$attr['@attributes']['Id']?>" class="cadastroProd">

                                                    </div>
                                                        

                                                    <div>
                                                        <input class="cadastroProd" type="hidden" name="ncm" id="ncm" value="<?=$prod->NCM?>">
                                                        <input class="cadastroProd" type="hidden" name="cfop" id="cfop" value="">
                                                        <input class="cadastroProd" type="hidden" name="cst" id="cst" value="">
                                                        <input class="cadastroProd" type="hidden" name="cest" id="cest" value="<?=$prod->CEST?>">
                                                        <input class="cadastroProd" type="hidden" name="origem" id="origem" value="<?=$imposto->orig?>">
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="btn btn-success float-right" onclick="salvarProduto('<?=$rId?>')">Cadastrar</div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>
                                            <?endif;?>
                                            <div class="tab-pane <?=$cadastrar?'fade':'active';?>" id="add-<?=$rId?>">
                                                <form autocomplete="off" class="<?=$rId?>-2">
                                                    
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <label for="produto">Produto</label>
                                                            
                                                            <?
                                                                $response = $con->query('select id,nome,valor from tbl_produtos where referencia = "'.$prod->cProd.'" or nome = "'.$prod->xProd.'"')->fetch_assoc();
                                                            ?>
                                                            <input class="form-control cadastroProd" list="listaProdutos" value="<?=(isset($response['id'])?$response['id'].' - '.$response['nome']:'')?>" type="text" name="produto" id="produto" required onselect="setValComp(this)">
                                                            <datalist id="listaProdutos">
                                                                <?php
                                                                    $resp = $con->query('select id,nome,valor from tbl_produtos');
                                                                    while($row = $resp->fetch_assoc()){
                                                                        echo '<option value="'.$row['id'].' - '.$row['nome'].'" prodId="'.$row['id'].'" valor="'.$row['valor'].'">';
                                                                    }
                                                                ?>
                                                            </datalist>
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="estoque">Quantia</label>
                                                            <input class="form-control cadastroProd" name="estoque" id="estoque" type="text" value="<?=number_format(floatval($prod->qCom),4,',','.')?>" readonly>
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="compra">Valor compra</label>
                                                            <input class="form-control cadastroProd" name="compra" id="compra" type="text" value="<?=number_format(floatval($prod->vUnCom),2,',','.')?>" readonly>
                                                        </div>
                                                        <div class="col-2">
                                                            <label for="cest">Valor venda</label>
                                                            <input class="form-control cadastroProd" step="0.01" type="number" value="<?=number_format(floatval($prod->vUnCom),2,'.','')?>" name="valVenda" id="valVenda" required>
                                                        </div>
                                                        <div class="col d-flex">
                                                            
                                                            <input type="hidden" name="produtoDeEntrada" id="produtoDeEntrada" value="<?=$prod->xProd;?>" class="cadastroProd">
                                                            <input type="hidden" name="fornecedor" id="fornecedor" value="<?=$idCliente;?>" class="cadastroProd">
                                                            <input type="hidden" name="notaId" id="notaId" value="<?=$nota->nNF?>" class="cadastroProd">
                                                            <input type="hidden" name="notaSerie" id="notaSerie" value="<?=$nota->serie?>" class="cadastroProd">
                                                            <input type="hidden" name="dataNota" id="dataNota" value="<?=$nota->dhEmi?>" class="cadastroProd">
                                                            <input type="hidden" name="chaveNFe" id="chaveNFe" value="<?=$attr['@attributes']['Id']?>" class="cadastroProd">
                                                            <input type="hidden" name="referencia" id="referencia" value="<?=$prod->cProd?>" class="cadastroProd">

                                                            <div class="btn btn-success ml-auto mt-auto" onclick="salvarProduto('<?=$rId?>'+'-2')">Adicionar</div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>

                    <?
                            echo '  </td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>