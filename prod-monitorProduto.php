<?php include('header.php'); ?>

<?

if($_POST['cmd'] == 'finalizar'){
    $con->query('update tbl_producaoItens set status = 2 where id = '.$_POST['producaoItem']);
    $keys = array_keys($_POST['item']);
    for($i = 0; $i < sizeof($keys); $i++){
        $con->query('update tbl_produtos set estoque = (estoque - '.$_POST['item'][$keys[$i]].') where id = '.$keys[$i]);
        $con->query('INSERT INTO `tbl_estoque`(`quantia`, `produto`, `local`, `xml`, `operacao`, `motivo`, `data`) VALUES (
            '.$_POST['item'][$keys[$i]].',
            '.$keys[$i].',
            '.$_POST['localEstoque'].',
            0,
            "i",
            "Produção",
            now()
        )');
    }

    $resp = $con->query('select id from tbl_producaoItens where status = 1 and producao = '.$_GET['producao']);
    if($resp->num_rows == 0){
        $con->query('update tbl_producao set baixa = 1 where id = '.$_GET['producao']);
    }

    echo "<script>location.href='prod-monitor.php?s'</script>";
}

$producao = $con->query('
    select a.*,b.razaoSocial_nome from tbl_producao a
    left join tbl_clientes b on b.id = a.cliente
    where a.id = '.$_GET['producao']
)->fetch_assoc();

$grade = $con->query('
    select a.grade,a.quantia,b.descricao from tbl_producaoItens a 
    inner join tbl_gradeProdutos b on b.id = a.grade
    where a.id = '.$_GET['item']
)->fetch_assoc();

?>

<script>

    $(document).ready(function(){
        $("#campoPesquisa").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tablePrint tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('.select2').select2({
            theme: "bootstrap"
        });
    });
</script>

<!-- conteúdo -->
<div class="content">

    <form id="formularioOrdem" method="POST">
        <input type="hidden" name="cmd" value="finalizar">
        <input type="hidden" name="producaoItem" value="<?=$_GET['item']?>">
        <input type="hidden" name="localEstoque" value="<?=$producao['localEstoque']?>">

        <div class="row mb-3">
            <div class="col">
                <a href="prod-monitor.php" class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
            </div>
            <div class="col d-flex">
                <button class="btn btn-success ml-auto"><i class="fas fa-check mr-2"></i><strong>Finalizar</strong></button>
            </div>
        </div>

        <div class="row">
            <div class="col">

                <h3>O.P.</h3>
                <div class="card main-card mb-3">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col">
                                <label for="codigo">Código da O.P.</label>
                                <input type="text" id="codigo" value="<?=$producao['id']?>" class="form-control" readonly>
                            </div>
                            <div class="col">
                                <label for="emissao">Data da emissão</label>
                                <input type="date" id="emissao" value="<?=$producao['emissao']?>" class="form-control" readonly>
                            </div>
                            <div class="col">
                                <label for="prazo">Prazo</label>
                                <input type="date" id="prazo" value="<?=$producao['prazo']?>" class="form-control" readonly>
                            </div>
                            <div class="col">
                                <label for="cliente">Cliente</label>
                                <input type="cliente" id="cliente" value="<?=$producao['razaoSocial_nome']?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label for="grade">Grade</label>
                                <input type="text" id="grade" class="form-control" value="<?=$grade['descricao']?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="observacao">Observações</label>
                                <textarea id="observacao" class="form-control" style="resize:none" rows="4" readonly><?=$producao['observacao']?></textarea>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        
        <div class="row">
            <div class="col">

                <h3>Grade</h3>
                <div class="card main-card mb-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Descrição</th>
                                            <th>Unidade</th>
                                            <th>Estoque</th>
                                            <th style="width:15%">Utilizada</th>
                                        </tr>
                                    </thead>
                                    <tbody id="campoGrade">
                                    
                                    <?
                                        $resp = $con->query('
                                            select a.*, b.nome, b.unidadeEstoque, b.estoque, c.simbolo from tbl_gradeProdutosItens a 
                                            inner join tbl_produtos b on b.id = a.item
                                            inner join tbl_unidades c on c.id = b.unidadeEstoque 
                                            where a.grade = '.$grade['grade']
                                        );

                                        while($row = $resp->fetch_assoc()){
                                            $estFinal = $row['estoque'] - $row['fator'];
                
                                            if($estFinal < 0){
                                                $bgEst = "danger";
                                            }
                                            elseif($estFinal == 0){
                                                $bgEst = "warning";
                                            }
                                            else{
                                                $bgEst = "info";
                                            }

                                            echo '
                                                <tr>
                                                    <td>'.$row['id'].'</td>
                                                    <td>'.$row['nome'].'</td>
                                                    <td>'.$row['simbolo'].'</td>
                                                    <td>'.$row['estoque'].'</td>
                                                    <td><input type="number" class="form-control" name="item['.$row['item'].']" value="'.floatval($row['fator']*$grade['quantia']).'" fator="'.floatval($row['fator']).'" step="0.00001"></td>
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
        </div>

    </form>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>