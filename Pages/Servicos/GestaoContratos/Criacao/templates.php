<?php
//para remover o arquivo
if(@$_GET['del']=='1'){
    $arquivo = base64_decode($_GET['arquivo']);
    if(unlink($arquivo)){
        echo "<script>window.location.href='serv-contratostemplates.php';</script>";
        exit;
    }else{
        echo "erro ao excluir o arquivo";
    }
}
#para gravar o arquivo
if(isset($_POST['upload'])&&$_POST['upload']=='1'){
    #executa o upload do arquivo
    $diretorioUpload = "assets/contratoTemplates/";
    $ext_perm = array('doc','docx','DOC','DOCX');
    if (is_uploaded_file($_FILES['template']['tmp_name'])) { 
      
        $ext = pathinfo($_FILES['template']['name'], PATHINFO_EXTENSION);
        if(in_array($ext, $ext_perm)) {
            $nome = pathinfo($_FILES['template']['name'], PATHINFO_BASENAME);
            $nome = str_replace(' ', '-', $nome);
            
            move_uploaded_file($_FILES['template']['tmp_name'], $diretorioUpload . $nome);
            
        } else {
            
            echo 'Não é permitido enviar arquivo com extensão ' . strtoupper($ext);
            exit;
        }
    }
}
?>
<script src="assets/scripts/serv-contratos.js"></script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-file-contract icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cria&ccedil;&atilde;o de Contratos - Templates</span>
                <div class="page-title-subheading">
                    Inserção e remoção de modelos de contratos
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=clientes">
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

                    <h5 class="card-title">Lista de Arquivos</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">
                    <?php
                   
                    $path = "assets/contratoTemplates";
                    $diretorio = dir($path);
                                       
                    ?>
                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                    <thead >
                            <tr>
                                <th style="width:5%">Nome do arquivo</th>
                                <th style="width:14%">Tamanho</th>
                                <th style="width:6%">A&ccedil;&atilde;o</th>
                                <th style="width:6%" class="noPrint"></th>
                                <th style="width:6%" class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($arquivo = $diretorio -> read()){
                                $formatoDir = explode(".",$arquivo);
                                if(@$formatoDir[1]!=""){
                                if($arquivo != '.' && $arquivo != '..'){
                                ?>
                            <tr>
                                <td><?=$arquivo?></td>
                                <td><?php echo round(filesize($path.'/'.$arquivo)/1024,0)." KB";?></td>
                                <td><a href="?del=1&arquivo=<?php echo base64_encode($path.'/'.$arquivo);?>" onclick="javascript:return confirm('Deseja mesmo excluir o arquivo?');" class="btn btn-danger btn-lg active" role="button" aria-pressed="true">
                                    <i class="fa fa-trash" aria-hidden="true" title="Excluir template"></i></a></td>
                                <td class="noPrint"></td>
                                <td class="noPrint"></td>
                            </tr>
                            <?php }
                                }
                            }
                            $diretorio -> close();?>
                            <tfoot>
                            <tr>
                                <td>Adicionar novo Template</td>
                                <td colspan="3">
                                    <form method="post" enctype="multipart/form-data" action="#">
                                        <input type="file" name="template" class="form-control p-1"> 
                                        <input type="submit" value="Adicionar" class="mt-2 btn btn-primary" style="float: left;">
                                       
                                        
                                        <input type="hidden" name="upload" value="1">
                                    </form>
                                    <a href="serv-criacontratos.php">
                                        <button class="mt-2 btn btn-dark" style="margin-left: 20px;">Voltar</button>
                                    </a>
                                </td>

                            </tr>
                            </tfoot>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- fim conteúdo -->
