<?php include('header.php'); ?>

<script>
    $(document).ready(function(){
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

<?if($_CONF['sistema']['certificado'] == "" && $_CONF['sistema']['certSenha'] == ""):?>
    <div class="alert alert-danger">
        <strong>Erro!</strong> Não foi possível acessar o certificado. <a href="configuracao.php?p=geral" class="alert-link">Clique aqui</a> para corrigir.
    </div>
<?else:?>
    <div class="alert alert-info">
        <strong><?=$_CONF['sistema']['razao_social']?></strong> (<?=$_CONF['sistema']['cnpj']?>).
    </div>
    <div class="content">
        <div class="nav nav-tabs mb-0">
            <li class="nav-item">
                <a class="nav-link active" data-target="#geral">Geral</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#destinatario">Destinatário</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#produtos">Produtos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#transporte">Transporte</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#referencias">Referencias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#pagamentos">Pagamentos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  data-target="#finalizar">Finalizar</a>
            </li>
        </div>
        
        <div class="tab-content">
            <div class="tab-pane active" id="geral">
                <?include('pag/nfeNota/geral.php');?>
            </div>
            <div class="tab-pane fade" id="destinatario">
                <?include('pag/nfeNota/destinatario.php')?>
            </div>
            <div class="tab-pane fade" id="produtos">
                <?include('pag/nfeNota/produtos.php');?>
            </div>
            <div class="tab-pane fade" id="transporte">
                <?include('pag/nfeNota/transporte.php');?>
            </div>
            <div class="tab-pane fade" id="referencias">
                <?include('pag/nfeNota/referencias.php');?>
            </div>
            <div class="tab-pane fade" id="pagamentos">
                <?include('pag/nfeNota/pagamentos.php');?>
            </div>
            <div class="tab-pane fade" id="finalizar">
                <?include('pag/nfeNota/finalizar.php');?>
            </div>
        </div>
    </div>
<?endif;?>

<?php include('footer.php');?>