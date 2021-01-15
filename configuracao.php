<?php include('header.php'); ?>
<?php

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if(isset($_POST['cmd'])){
    switch($_POST['cmd']){
        case 'add':
            $certificado = '';
            if(isset($_FILES['certificado']) && $_FILES['certificado']['name'] != ""){
                if($_FILES['certificado']['type'] == 'application/x-pkcs12' && $_POST['certSenha'] != ''){
                    $arq = $_FILES['certificado'];
                    $ext = 'pfx';
                    $nome = uniqid().'-'.$_COOKIE['empresa'].'.'.$ext;

                    if(move_uploaded_file($arq["tmp_name"],"upload/certificado/".$nome)){
                        $certificado = $nome;
                    }
                    else{
                        echo "<script>Erro ao importar!</script>";
                        return;
                    }
                }
                else{
                    echo '<script>alert("arquivo enviado não possui a extensão .pfx ou senha inválida")</script>';
                    return;
                }
            }
            
            $con->query('INSERT INTO `tbl_configuracao`(`razao_social`,`cnpj`,`endereco`,`complemento`,`cidade`,`cep`,`telefone`,`im`,`cnae`,`crt`,`email`,`site`,`logo`,`msg_fiscal`,`ie`,`numero`,`bairro`,`estado`,`ibge`,`iss`,`aliq_sn`,`aliq_pis`,`aliq_cofins`,`nome_fantasia`, `certificado`,`certSenha`) VALUES (
                "'.$_POST['razao_social'].'",
                "'.$_POST['cnpj'].'",
                "'.$_POST['endereco'].'",
                "'.$_POST['complemento'].'",
                "'.$_POST['cidade'].'",
                "'.$_POST['cep'].'",
                "'.$_POST['telefone'].'",
                "'.$_POST['im'].'",
                "'.$_POST['cnae'].'",
                "'.$_POST['crt'].'",
                "'.$_POST['email'].'",
                "'.$_POST['site'].'",
                "'.$_POST['logo'].'",
                "'.$_POST['msg_fiscal'].'",
                "'.$_POST['ie'].'",
                "'.$_POST['numero'].'",
                "'.$_POST['bairro'].'",
                "'.$_POST['estado'].'",
                "'.$_POST['ibge'].'",
                "'.($_POST['iss']?$_POST['iss']:0).'",
                "'.($_POST['aliq_sn']?$_POST['aliq_sn']:0).'",
                "'.($_POST['aliq_pis']?$_POST['aliq_pis']:0).'",
                "'.($_POST['aliq_cofins']?$_POST['aliq_cofins']:0).'",
                "'.($_POST['nome_fantasia']?$_POST['nome_fantasia']:0).'",
                "'.$certificado.'",
                "'.$_POST['certSenha'].'"
            )');
            $con->query('INSERT into tbl_usuarioMeta(meta,valor,descricao,status,usuario) values("habilitar_empresa",'.$con->insert_id.',"",1,0)');
            redirect($con->error);
            
        break;

        case 'edt':
            var_dump($_FILES);
            $certificado = '';
            if(isset($_FILES['certificado']) && $_FILES['certificado']['name'] != ""){
                if($_FILES['certificado']['type'] == 'application/x-pkcs12' && $_POST['certSenha'] != ""){
                    $arq = $_FILES['certificado'];
                    $ext = 'pfx';
                    $nome = uniqid().'-'.$_COOKIE['empresa'].'.'.$ext;

                    if(move_uploaded_file($arq["tmp_name"],"upload/certificado/".$nome)){
                        $certificado = $nome;
                    }
                    else{
                        echo "<script>Erro ao importar!</script>";
                        return;
                    }
                }
                else{
                    echo '<script>alert("arquivo enviado não possui a extensão .pfx ou senha inválida")</script>';
                    return;
                }
            }
            
            $con->query('update tbl_configuracao set
                razao_social = "'.$_POST['razao_social'].'",
                cnpj = "'.$_POST['cnpj'].'",
                endereco = "'.$_POST['endereco'].'",
                complemento = "'.$_POST['complemento'].'",
                cidade = "'.$_POST['cidade'].'",
                cep = "'.$_POST['cep'].'",
                telefone = "'.$_POST['telefone'].'",
                im = "'.$_POST['im'].'",
                cnae = "'.$_POST['cnae'].'",
                crt = "'.$_POST['crt'].'",
                email = "'.$_POST['email'].'",
                site = "'.$_POST['site'].'",
                logo = "'.$_POST[''].'",
                msg_fiscal = "'.$_POST['msg_fiscal'].'",
                ie = "'.$_POST['ie'].'",
                numero = "'.$_POST['numero'].'",
                bairro = "'.$_POST['bairro'].'",
                estado = "'.$_POST['estado'].'",
                ibge = "'.$_POST['ibge'].'",
                iss = "'.$_POST['iss'].'",
                aliq_sn = "'.$_POST['aliq_sn'].'",
                aliq_pis = "'.$_POST['aliq_pis'].'",
                aliq_cofins = "'.$_POST['aliq_cofins'].'",
                nome_fantasia = "'.$_POST['nome_fantasia'].'",
                '.($certificado == ''?'':'certificado = "'.$certificado.'",').'
                certSenha = "'.$_POST['certSenha'].'"
                where id = '.$_POST['id'].'
            ');
            redirect($con->error);
        break;

        case 'impressora':
            for($i = 0; $i < 4; $i++){
                $v = $_POST['larg'];
                $con->query('update tbl_confImp set codigo = '.$v['codigo'][$i].', peso = '.$v['peso'][$i].', preco = '.$v['preco'][$i].', tamanho = '.$v['tamanho'][$i].', quantidade = '.$v['quantidade'][$i].', descricao = '.$v['descricao'][$i].', barras = '.$v['barras'][$i].' where direcao = "l" and coluna = '.($i+1));
                $v = $_POST['alt'];
                $con->query('update tbl_confImp set codigo = '.$v['codigo'][$i].', peso = '.$v['peso'][$i].', preco = '.$v['preco'][$i].', tamanho = '.$v['tamanho'][$i].', quantidade = '.$v['quantidade'][$i].', descricao = '.$v['descricao'][$i].', barras = '.$v['barras'][$i].' where direcao = "a" and coluna = '.($i+1));
            }
            $con->query('update tbl_confImp set 
                espacamento = '.$_POST['geral']['espacamento'].', 
                linha = '.$_POST['geral']['linha'].', 
                tColuna = '.$_POST['geral']['coluna'].',
                margem = '.$_POST['geral']['margem'].',
                tEspacamento = '.$_POST['geral']['tEspacamento'].'    
            ');
        break;

        case 'sistema':
            $con->query('update tbl_configuracao set debug = '.(isset($_POST['homologacao'])?1:0));
            redirect($con->error);
        break;
    }
}
elseif(isset($_GET['del'])){
    $con->query('DELETE FROM `tbl_configuracao` WHERE id = '.$_GET['del']);
    $con->query('DELETE from tbl_usuarioMeta where meta = "habilitar_empresa" and valor = '.$_GET['del']);
    redirect($con->error);
}

$resp = $con->query('select * from tbl_configuracao where id = 1');
$usuario = $resp->fetch_assoc();

?>

<div id="my_camera"></div>

<script src="assets/scripts/lib/webcam/webcam.min.js"></script>
<script>
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    function selecionaBanco(self){
        $('#caixaComplemento').empty();
        if($(self).val() == 104){
            $('#caixaComplemento').append(`
                <div class="col-2 text-right"><strong>Complemento Caixa</strong></div>
                <div class="col">
                    <select name="compCaixa" id="compCaixa" class="form-control w-25" maxlength="120">
                        <option value="001" selected>001</option>
                        <option value="002">002</option>
                        <option value="003">003</option>
                        <option value="006">006</option>
                        <option value="007">007</option>
                        <option value="013">013</option>
                        <option value="022">022</option>
                        <option value="023">023</option>
                        <option value="028">028</option>
                        <option value="043">043</option>
                        <option value="031">031</option>
                    </select> 
                </div>
            `);
        }
    }

    function modalFoto(doc){
        $('#mdl-confPagamento').modal();
        $('#imgPhoto').attr('target',doc);
        
        if($('#'+doc).val() != ""){
            $('#imgArea').toggle();
            $('#imgPhoto').attr('src',$('#'+doc).val());
            $('#imgPhoto').addClass('show');
            $('#imgPhoto').toggle();
        }
        window.Webcam.reset();
        window.Webcam.attach('#imgArea');
    }

    function baterFoto(){
        window.Webcam.snap(function(data_uri){
            $('#imgArea').toggle();
            $('#imgPhoto').attr('src',data_uri);
            $('#imgPhoto').addClass('show');
            $('#imgPhoto').toggle();
        });
    }
    
    function limparFoto(){
        $('#imgPhoto').attr('src','');
        if($('#imgPhoto').is('.show')){
            $('#imgPhoto').toggle();
            $('#imgArea').toggle();
            $('#imgPhoto').removeClass('show');    
        }
        else{
            $('#mdl-confPagamento').modal('hide');
            window.Webcam.reset();
        }
    }

    function salvarFoto(){
        if($('#imgPhoto').is('.show')){
            $('#imgPhoto').toggle();
            $('#imgArea').toggle();
            $('#imgPhoto').removeClass('show');
            $('#'+$('#imgPhoto').attr('target')).val($('#imgPhoto').attr('src'));
            window.Webcam.reset();
            $('#mdl-confPagamento').modal('hide');
            $('.custom-file-label').text('');
            $('#check-'+$('#imgPhoto').attr('target')).show();
        }
        else{
            if($('#impImagem')[0].files.length == 0) return;
            var reader = new FileReader();
            reader.onload = function(e){
                var dataUri = e.target.result;
                $('#imgPhoto').toggle();
                $('#imgArea').toggle();
                $('#imgPhoto').removeClass('show');
                $('#'+$('#imgPhoto').attr('target')).val(dataUri);
                window.Webcam.reset();
                $('#mdl-confPagamento').modal('hide');
                $('#check-'+$('#imgPhoto').attr('target')).toggle();
            }
            reader.readAsDataURL($('#impImagem')[0].files[0]);
        }
    }

    function modal(target){
        $('.collapse:not("'+target+'")').hide('slow');
        $(target).toggle('slow');
    }
    
    function getEmpresa(self){
        $('#compEmpresaID').val($(self).val());
        $('#bancoEmpresaID').val($(self).val());
        let liberar = false;

        $('#caixaComplemento').empty();

        $('#compEmpresa').css('display','block');
        $('#bancoEmpresa').css('display','none');
        $('#docsEmpresa').css('display','none');
        $('#habilitarEmpresa').css('display','none');
        
        $.get('core/ajax/configuracao/compEmpresa.php?id='+$(self).val(),function(resp){
            if(resp != 'null'){
                resp = JSON.parse(resp);
                $('#area').val(resp['area']);
                $('#tipo').val(resp['tipo']);
                $('#descricao').val(resp['descricao']);
                $('#respLegal').val(resp['respLegal']);
                $('#docResp').val(resp['docResp']);
                $('#aniversarioResp').val(resp['dataResp']);
                $('#bancoEmpresa').css('display','block');
                liberar = true;
            }
            else{
                $('#area').val('');
                $('#tipo').val('');
                $('#descricao').val('');
                $('#respLegal').val('');
                $('#docResp').val('');
                $('#aniversarioResp').val('');
                liberar = false;
            }
        });

        $.get('core/ajax/configuracao/bancoEmpresa.php?id='+$(self).val(),function(resp){
            if(resp != 'null'){
                resp = JSON.parse(resp);
                $('#banco').val(resp['banco']);
                $('#agencia').val(resp['agencia']);
                $('#conta').val(resp['conta']);
                $('#responsavel').val(resp['responsavel']);
                $('#documento').val(resp['documento']);
                $('#docsEmpresa').css('display','block');
                $('#habilitarEmpresa').css('display','block');
                if(resp['complementoCaixa'] != '0'){
                    $('#caixaComplemento').append(`
                        <div class="col-2 text-right"><strong>Complemento Caixa</strong></div>
                        <div class="col">
                            <select name="compCaixa" id="compCaixa" class="form-control w-25" maxlength="120">
                                <option value="001" `+(resp['complementoCaixa']=='001'?'selected':'')+`>001</option>
                                <option value="002" `+(resp['complementoCaixa']=='002'?'selected':'')+`>002</option>
                                <option value="003" `+(resp['complementoCaixa']=='003'?'selected':'')+`>003</option>
                                <option value="006" `+(resp['complementoCaixa']=='006'?'selected':'')+`>006</option>
                                <option value="007" `+(resp['complementoCaixa']=='007'?'selected':'')+`>007</option>
                                <option value="013" `+(resp['complementoCaixa']=='013'?'selected':'')+`>013</option>
                                <option value="022" `+(resp['complementoCaixa']=='022'?'selected':'')+`>022</option>
                                <option value="023" `+(resp['complementoCaixa']=='023'?'selected':'')+`>023</option>
                                <option value="028" `+(resp['complementoCaixa']=='028'?'selected':'')+`>028</option>
                                <option value="043" `+(resp['complementoCaixa']=='043'?'selected':'')+`>043</option>
                                <option value="031" `+(resp['complementoCaixa']=='031'?'selected':'')+`>031</option>
                            </select> 
                        </div>
                    `);
                }
            }
            else{
                $('#banco').val('');
                $('#agencia').val('');
                $('#conta').val('');
                $('#responsavel').val('');
                $('#documento').val('');
            }
        });

        $.get('core/ajax/configuracao/enviarDoc.php?id='+$(self).val(),function(resp){
            if(resp == null) return;

            $('#campDocs').empty();
            $('#campDocs').append(resp);
        });

        $.get('core/ajax/configuracao/ativarJuno.php?id='+$(self).val(),function(resp){
            if(resp == 'null') return;
            
            resp = JSON.parse(resp);
            
            if(resp['pagamentoStatus'] == "1"){
                $('#habilitarPagamento').attr('checked',true);
                $('#habilitarPagamento').addClass(':checked');
                $('#habilitarPagamento').attr('checked');
            }
            else{
                $('#habilitarPagamento').removeAttr('checked');
                $('#habilitarPagamento').removeClass(':checked');
            } 
        });
        
    }
    
    $(document).ready(function(){
        $('.nav-link[data-target]').click(function(){
            $('.nav-link[data-target]').removeClass('active');
            $('.tab-pane').removeClass('active');
            $('.tab-pane').addClass('fade');
            $(this).addClass('active');
            $($(this).attr('data-target')).removeClass('fade');
            $($(this).attr('data-target')).addClass('active');
        });

        $("#compEmpresa").submit(function(e){
            const data = $(this).serializeArray();
            $.post('core/ajax/configuracao/compEmpresa.php',data,function(resp){
                loadToastNR(resp);
                $('#bancoEmpresa').css('display','block');
            });
            e.preventDefault();
        });

        $("#bancoEmpresa").submit(function(e){
            const data = $(this).serializeArray();
            console.log(data);
            $.post('core/ajax/configuracao/bancoEmpresa.php',data,function(resp){
                loadToastNR(resp);
                $('#habilitarEmpresa').css('display','block');
            });
            e.preventDefault();
        });

        $('#docsEmpresa').submit(function(e){
            const data = $(this).serializeArray();
            $.post('core/ajax/configuracao/enviarDoc.php',data,function(resp){
                console.log(resp);
                loadToastNR(resp);
                //$('#habilitarEmpresa').css('display','block');
            });
            e.preventDefault();
        });

        $('#habilitarPagamento').click(function(){
            const idEmpresa = $('#habilitarPagamentoEmpresa').val();
            const status = $(this).is(':checked') || $(this).attr('checked') == 'checked'? true:false;
            $.post('core/ajax/configuracao/ativarJuno.php',{empresa:idEmpresa,habilitar:status},function(resp){
                resp = JSON.parse(resp);
                console.log(resp);
                if(resp['status'] != 'true' && $('#habilitarPagamento').is(':checked')){
                    $('#habilitarPagamento').click();
                    alert(resp['valor']);
                }
                loadToastNR(resp);
            });
        });

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-user-cog icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Configuracao</span>
                <div class="page-title-subheading">
                    Campo para configurações do sistema
                </div>
            </div>

        </div>
        
    </div>
</div>
<!-- fim cabeçalho-->

<!-- conteúdo -->
<div class="content">

    <div class="nav nav-tabs mb-0">
        <li class="nav-item">
            <a class="nav-link <?=!isset($_GET['p'])||$_GET['p']=='geral'?'active':''?>" data-target="#geral">Geral</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php=$_GET['p']=='pagamento'?'active':''?>"  data-target="#pagamento">Pagamento</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php=$_GET['p']=='impressao'?'active':''?>"  data-target="#impressao">Impressão</a>
        </li>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane <?=!isset($_GET['p'])||$_GET['p']=='geral'?'active':'fade'?>" id="geral">
            <?include('pag/configuracao/geral.php');?>
        </div>
        <div class="tab-pane <?=$_GET['p']=='pagamento'?'active':'fade'?>" id="pagamento">
            <?include('pag/configuracao/pagamento.php')?>
        </div>
        <div class="tab-pane <?=$_GET['p']=='impressao'?'active':'fade'?>" id="impressao">
            <?include('pag/configuracao/impressao.php');?>
        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>

<div class="modal" tabindex="-1" role="dialog" id="mdl-confPagamento">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">

                <div class="row">
                    <div class="col d-flex">
                        <span id="imgArea" class="m-auto"></span>
                        <img id="imgPhoto" src="" class="m-auto" style="display:none">
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="impImagem">
                            <label class="custom-file-label" for="impImagem">Selecione o arquivo</label>
                        </div>
                        <!--<input type="file" id="impImagem" class="form-control p-1">-->
                    </div> 
                </div>
                <div class="row">
                    <div class="col d-flex">
                        <div class="ml-auto mr-auto mt-3">
                            <i class="btn text-danger fas fa-times-circle" onclick="limparFoto();" style="font-size: 2rem"></i>
                            <i class="btn btn-outline-primary fas fa-camera" onclick="baterFoto()" style="font-size: 2rem"></i>
                            <i class="btn text-success fas fa-check-circle" onclick="salvarFoto()" style="font-size: 2rem"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div id="toast-container" class="toast-top-center mt-3">
    <div id="toast-success" class="toast toast-success" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Sucesso!</div>
    </div>
    <div id="toast-error" class="toast toast-error hidden" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Erro!</div>
    </div>
</div>