<?php include('header.php'); ?>

<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Unidades Cadastradas</h5>');
        newWin.document.write(divPrint.outerHTML);
    }

    function abrirProduto(item,producao){
        location.href = "prod-monitorProduto.php?item="+item+"&producao="+producao;
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
                <i class="fas fa-desktop icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Monitor de produção</span>
                <div class="page-title-subheading">
                    Campo para monitoração de produção
                </div>
            </div>

        </div>
        <div class="page-title-actions">

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

                    <h5 class="card-title">Monitor</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:4%">ID</th>
                                <th>Grade</th>
                                <th>Quantia</th>
                                <th>Produto</th>
                                <th>Observação</th>
                                <th>Prazo</th>
                                <th>status</th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('
                                    select a.*,b.descricao,c.prazo,c.observacao,e.nome from tbl_producaoItens a 
                                    inner join tbl_gradeProdutos b on b.id = a.grade
                                    inner join tbl_producao c on c.id = a.producao
                                    inner join tbl_gradeProdutosItens d on d.grade = a.grade
                                    inner join tbl_produtos e on e.id = d.item       
                                    where a.status >= 1 group by a.producao order by a.id desc
                                ');
                            
                                $grupoNome = '';
                                while($row = $resp->fetch_assoc()){
                                    $status = '';
                                    $bgStatus = '';
                                    
                                    switch($row['status']){
                                        case 1:
                                            $status = 'Aberto';
                                            $bgStatus = 'primary';
                                        break;
                                        case 2:
                                            $status = 'Finalizado';
                                            $bgStatus = 'success';
                                        break;
                                    }

                                    echo '
                                        <tr style="cursor:pointer" '.($row['status']==1?'ondblclick="abrirProduto('.$row['id'].','.$row['producao'].')"':'').'>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$row['descricao'].'</td>
                                            <td>'.$row['quantia'].'</td>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['observacao'].'</td>
                                            <td>'.date('d / m / Y',strtotime($row['prazo'])).'</td>
                                            <td><span class="badge badge-'.$bgStatus.'">'.$status.'</span></td>
                                            <td>'.($row['status']==1?'<i class="fas fa-angle-double-right"></i>':'').'</td>
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