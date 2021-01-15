<?php include('header.php'); ?>

<?
    switch($_POST['cmd']){
        case 'add':
            $query = 'INSERT INTO `tbl_producao`(`emissao`, `movimento`, `prazo`, `observacao`, `localEstoque`, `cliente`, `baixa`, `status`) VALUES (
                "'.$_POST['emissao'].'",
                "'.$_POST['movimento'].'",
                "'.$_POST['prazo'].'",
                "'.$_POST['observacoes'].'",
                "'.$_POST['lEstoque'].'",
                "'.(isset($_POST['cliente'])?$_POST['cliente']:'0').'",
                0,
                1
            )';

            $con->query($query);
            $last_id = $con->insert_id;

            foreach($_POST['grades'] as $grade){
                $grade = json_decode(urldecode($grade));
                
                $query = 'INSERT INTO `tbl_producaoItens`(`grade`, `quantia`, `producao`, `status`) VALUES (
                    "'.$grade->codigo.'",
                    "'.$grade->quantia.'",
                    "'.$last_id.'",
                    1
                )';

                $con->query($query);
                $produto_id = $con->insert_id;
            }
            
            echo '<script>location.href="prod-ordem.php?s"</script>';
        break;
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

    function getGrade(self){
        $.get('core/ajax/prod-ordem/grade.php?grade='+$(self).val(),function(resp){
            $('#campoGrade').empty();
            $('#campoGrade').append(resp);
            $('#allCheck').prop('checked',false);
            $('#allCheck').click();
            $('#quantia').val(1);
            $('#quantia').focus();
        });
    }

    function alteraEstoque(self){
        const quantia = $(self).val();
        const linhas = $('#campoGrade tr');

        if(quantia < 1){
            $(self).val(1);
            return;
        }

        for(let i = 0; i < linhas.length; i++){
            const linha = $(linhas[i]).children();
            const input = linha[4];
            const badge = $(linhas[i]).find('.badge');

            const utilizada = $(linha[4]).attr('fator') * quantia;
            const final = parseFloat($(linha[3]).text()) - utilizada;

            $(badge).text(final);
            $(input).text(utilizada);

            $(badge).removeClass();
            $(badge).addClass('badge');

            if(final == 0){
                $(badge).addClass('badge-warning');
            }
            else if(final < 0){
                $(badge).addClass('badge-danger');
            }
            else{
                $(badge).addClass('badge-info');
            }
        }
    }


    function adicionar(){
        const linhas = $('#campoGrade tr');
        let grade = {codigo:$('#grade').val(),quantia:$('#quantia').val(),itens:[]};

        let j = 0;
        for(let i = 0; i < linhas.length; i++){
            const linha = $(linhas[i]).children();
            const input = $(linhas[i]).find('input[type="number"]');
            grade.itens[j++] = {id:$(linha[0]).text(),quantia:$(input).val()};
        }

        $('#campoProducoes').append(`
            <tr>
                <td>`+$('#grade').val()+`</td>
                <td>`+$('#grade option:selected').text()+`</td>
                <td>`+$('#quantia').val()+`</td>
                <td><i class="fas fa-trash-alt btn btn-danger" onclick="limpar(this)"></i></td>
                <input type="hidden" name="grades[]" value="`+encodeURI(JSON.stringify(grade))+`">
            </tr>
        `);

        $('#campoGrade').empty();
        $('#grade').val(0);
        $('#quantia').val('');
    }

    function limpar(self){
        $($(self).parent().parent()).empty();
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

<!-- conteúdo -->
<div class="content">

    <form id="formularioOrdem" method="POST">
        <input type="hidden" name="cmd" value="add">

        <div class="row mb-3">
            <div class="col">
                <a href="prod-ordem.php" class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
            </div>
            <div class="col d-flex">
                <button class="btn btn-success ml-auto"><i class="fas fa-save mr-2"></i><strong>Salvar</strong></button>
            </div>
        </div>

        <div class="row">
            <div class="col">

                <h4>Ordem de produção</h4>
                <div class="card main-card mb-3">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-2">
                                <label for="op">O.P.</label>
                                <input type="text" name="op" id="op" value="0" class="form-control" readonly>
                            </div>
                            <div class="col">
                                <label for="lEstoque">Local de estoque</label>
                                <select name="lEstoque" id="lEstoque" class="form-control">
                                    <option selected disabled>Selecione</option>
                                    <?
                                        $resp = $con->query('select nome,id from tbl_locaisEstoque where status = 1');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="prazo">Prazo de entrega</label>
                                <input type="date" class="form-control" name="prazo" id="prazo" min="<?=date('Y-m-d')?>" required>
                            </div>
                            <div class="col-2">
                                <label for="movimento">Tipo de movimento</label>
                                <select name="movimento" id="movimento" class="form-control">
                                    <option value="0" selected>Estoque</option>
                                    <option value="1">Venda</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="emissao">Emissão</label>
                                <input type="date" name="emissao" id="emissao" class="form-control" value="<?=date('Y-m-d')?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="cliente">Cliente</label>
                                <select name="cliente" id="cliente" class="form-control select2">
                                    <option value="-1" selected disabled>Selecione o cliente</option>
                                    <?
                                        $resp = $con->query('select razaoSocial_nome,id from tbl_clientes where tipoCliente = "on"');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['razaoSocial_nome'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="observacoes">Observações</label>
                                <textarea name="observacoes" id="observacoes" class="form-control" row="40" style="resize:none" maxlength="400"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <h4>Produtos e insumos</h4>
                <div class="card main-card mb-3">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col">
                                <label for="grade">Grade</label>
                                <select id="grade" class="form-control select2" onchange="getGrade(this)">
                                    <option value="0">Selecione a grade</option>
                                    <?
                                        $resp = $con->query('select descricao,id from tbl_gradeProdutos where status = 1');
                                        while($row = $resp->fetch_assoc()){
                                            echo '<option value="'.$row['id'].'">'.$row['descricao'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="quantia">Quantia</label>
                                <input type="number" id="quantia" class="form-control" onchange="alteraEstoque(this)">
                            </div>
                            <div class="col d-flex">
                                <div class="btn btn-primary mt-auto mb-auto ml-auto" onclick="adicionar()">Adicionar</div>
                            </div>
                        </div>

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
                                            <th>Final</th>
                                        </tr>
                                    </thead>
                                    <tbody id="campoGrade">
                                    
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>

                <h4>Lista de produtos</h4>
                <div class="card main-card mb-3">
                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descrição</th>
                                    <th style="width:20%">Quantidade</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="campoProducoes">
                            
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>