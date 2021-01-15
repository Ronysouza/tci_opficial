<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
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
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_unidades set status = 0 where id = '.$_GET['del']);
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
                <span>Controle de medições</span>
                <div class="page-title-subheading">
                    Campo para controle de medições
                </div>
            </div>

        </div>
        <div class="page-title-actions">

            <a href="con-controleNovo.php" class="btn-shadow mr-3 btn btn-dark" id="btn-modal">
                <i class="fas fa-plus"></i>
            </a>

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

                    <h5 class="card-title">Controle de medições</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <?php
                        $resp = $con->query('select count(*) as qtd from tbl_contratoLocacaoMedidores')->fetch_assoc();

                        
                        $itensPorPagina = 50;
                        
                        $qtdPaginas = intval($resp['qtd'] / $itensPorPagina);
                        
                        $pagina = isset($_GET['p'])?$_GET['p']:0;
                        
                        $inicioSelect = $pagina * $itensPorPagina;
                        
                        $existeAnterior = !($pagina-1 < 0);
                        $existeProxima = !($pagina+1 > $qtdPaginas);
                    ?>

                    <div class="row">
                        <div class="col">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php=$existeAnterior?'':'disabled'?>"><a class="page-link" href="?p=0">Primeira</a></li>
                                <li class="page-item <?=$existeAnterior?'':'disabled'?>"><a class="page-link" href="?p=<?=$pagina-1?>">Anterior</a></li>
                                <li class="page-item <?=$existeProxima?'':'disabled'?>"><a class="page-link" href="?p=<?=$pagina+1?>">Próxima</a></li>
                                <li class="page-item <?=$existeProxima?'':'disabled'?>"><a class="page-link" href="?p=<?=$qtdPaginas?>">Última</a></li>
                            </ul>
                        </div>
                    </div>
                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Contrato</th>
                                <th>Produto</th>
                                <th>Data</th>
                                <th>Medição</th>
                                <th>Medição anterior</th>
                                <th>medição Efetiva</th>
                                <th>Tipo</th>
                                <th>Franquia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select a.*,b.tipoContrato,b.qtdFranquia from tbl_contratoLocacaoMedidores a
                                    left join tbl_contratoLocacaoEquipamentos b on b.contrato = a.contrato and b.produto = a.produto
                                    order by medicao desc limit '.$inicioSelect.','.$itensPorPagina.'
                                ');
                            
                                while($row = $resp->fetch_assoc()){
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.$row['contrato'].'</td>
                                            <td>'.$row['produto'].'</td>
                                            <td>'.date('d/m/Y',strtotime($row['medicao'])).'</td>
                                            <td>'.$row['qtdMedicao'].'</td>
                                            <td>'.$row['qtdMedicaoAnterior'].'</td>
                                            <td>'.$row['qtdEfetiva'].'</td>
                                            <td>'.$row['tipoContrato'].'</td>
                                            <td>'.$row['qtdFranquia'].'</td>
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