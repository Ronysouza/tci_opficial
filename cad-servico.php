<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $con->query("insert into tbl_servicos(nome,descricao,valor) values(
            '".$_POST['nome']."',
            '".$_POST['descricao']."',
            '".$_POST['valor']."'
        )");
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        $con->query("update tbl_servicos set
            nome = '".$_POST['nome']."',
            descricao = '".$_POST['descricao']."',
            valor = '".$_POST['valor']."'
            where id  = ".$_POST['id']
        );
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_servicos set status = 0 where id ='.$_GET['del']);
    redirect($con->error);
}

?>
<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Serviços Cadastrados</h5>');
        newWin.document.write(divPrint.outerHTML);
        //await new Promise(r => setTimeout(r, 150));
        //newWin.print();
        //newWin.close();
    }
    function getCidade(self){
        const xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "https://viacep.com.br/ws/"+$(self).val().replace('-','')+"/json/", false ); // false for synchronous request
        xmlHttp.send(null);
        const resp = JSON.parse(xmlHttp.responseText);
        $("#estado").val(resp['uf']);
        $("#cidade").val(resp['localidade']);
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
                <i class="fas fa-cogs icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de Serviços</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de serviços
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=servicos">
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

                    <h5 class="card-title">Serviços cadastrados</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:30%">Nome</th>
                                <th>Descrição</th>
                                <th style="width:10%">Valor</th>
                                <th style="width:8%" class="noPrint"></th>
                                <th style="width:8%" class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_servicos where status = 1  order by id desc');
                            
                                while($row = $resp->fetch_assoc()){
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['descricao'].'</td>
                                            <td>R$ '.$row['valor'].'</td>
                                            <td class="noPrint"><a href="?edt='.$row['id'].'" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint"><a href="?del='.$row['id'].'" class="btn"><i class="fas fa-trash icon-gradient bg-happy-itmeo"></i></a></td>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo serviço</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_servicos where id = '.$_GET['edt']);
                            $row = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id"> 

                    <div class="row">
                        <div class="col">
                            <label for="nome">Nome<span class="ml-2 text-danger">*</span></label>
                            <input type="text" value="<?php echo $row['nome'];?>" class="form-control mb-3" name="nome" id="nome" required>
                        </div>

                        <div class="col">
                            <label for="nome">Valor<span class="ml-2 text-danger">*</span></label>
                            <input class="form-control mb-3" name="valor" id="valor" value="<?php echo $row['valor'];?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="nome">Descrição</label>
                            <textarea class="form-control mb-3" name="descricao" id="descricao" maxlength="400" style="resize:none;"><?php echo $row['descricao'];?></textarea>
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