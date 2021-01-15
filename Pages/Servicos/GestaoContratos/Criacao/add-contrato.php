<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $corpo = base64_encode($_POST['contrato']);
       
        $grava_contrato = "insert into tbl_doc_contrato(id_cliente,criacao,id_usuario,conteudo) values(
            '".$_POST['id']."',
            '".date("Y-m-d h:i:s")."',
            '".$_SESSION['id']."',
            '".$corpo."'            
        )";
           

        #grava as informa��es no banco de dados
        if($con->query($grava_contrato)){
            echo "<script>window.location.href='serv-criacontratos.php';</script>";
        }else{
            echo $con->error;

        }
            
        
        exit;
    }
    elseif($cmd == "edt"){
     
    }
}
elseif(isset($_GET['del'])){

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
                <span>Cria&ccedil;&atilde;o de Contratos</span>
                <div class="page-title-subheading">
                    Formul&aacute;rio de adição de contratos
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
                    <?php 
                    #busca o nome do cliente para inser��o do contrato
                    $query = "select * from tbl_clientes where id='".$_GET['id']."'";
                    
                    $result = $con->query($query);
                    if($result->num_rows > 0) {  
                      $row = $result->fetch_object(); 
                    }else{
                        $clienteNome = "Cliente n&atilde;o encontrado.";
                    }
                    ?>
                    <h5 class="card-title">Criar contrato para: <?=$row->razaoSocial_nome?></h5>
                    <script src="https://cdn.tiny.cloud/1/6vhij89h5nambfeb3v118fvefkds6o755v8awtjujxvxl3te/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
                    <script>
                        
                        tinymce.init({
                            selector: 'textarea#mytextarea',
                            plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
                            toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
                            toolbar_mode: 'floating',
                            toolbar:"undo redo | styleselect | fontselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                            tinycomments_mode: 'embedded',
                            tinycomments_author: 'Author name',
                        });

                        function externo(arquivo) {
                            var dado = arquivo.split(':');
                            //alert(dado[0] + ' - ' + dado[1]);
                            var url = 'Pages/Servicos/GestaoContratos/Criacao/templateprocessor.php?id='+dado[0] + "&arquivo="+dado[1];
                            $.get(url, function(response) {
                                tinymce.activeEditor.setContent(response);
                                console.log(response);
                            }).fail(function(err) {
                                console.log(err);
                            });
                        }

                    </script>

                    
                    <form method="post" action="serv-addcontratos.php">
                    <b class="mt-4">Carregar Template:</b>
                    <div class="d-flex mt-1 mb-3">
                        <?php
                    
                        $path = "assets/contratoTemplates";
                        $diretorio = dir($path);
                                
                            while($arquivo = $diretorio -> read()){
                                if($arquivo != '.' && $arquivo != '..'){
                                    $formatoDir = explode(".",$arquivo);
                                    if(@$formatoDir[1]!=""){
                                        echo "<button onclick=\"externo('".$_GET['id'].":".$arquivo."')\" type=\"button\" class=\"btn btn-light ml-1\">".$arquivo."</button><br>";
                                    }
                                        
                                    
                                }
                            }
                            $diretorio -> close();
                        ?>
                    </div>
                    <textarea id="mytextarea" name="contrato"></textarea>
                    <!--Testemunhas:
                        <fieldset class="todos_labels">

                        <div class="repeatable"></div>

                        <input type="button" value="Add" class="add">

                        </fieldset>-->
                        <p>&nbsp;</p>
                        <button type="submit" class="btn btn-primary">Adicionar Contrato</button>
                        <a class="btn btn-dark" href="serv-criacontratos.php">voltar</a>
                        <input type="hidden" name="cmd" value="add">
                        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                    </form>
                        
                    <!--<script type="text/template" id="todos_labels">
                        <div class="field-group">

                            <label for="testemunha_{?}">Nome:</label>
                            <input type="text" name="testemunha[{?}][nome]" value="{testemunha}" id="testemunha_{?}" size=>

                            <label for="docTest_{?}">Documento:</label>
                            <input type="text" name="testemunha[{?}][documento]" value="{documento}" id="documento_{?}">

                            <input type="button" class="delete" value="Remover">

                        </div>
                    </script>-->
                </div>
            </div>

        </div>
    </div>

</div> 
<script src="//code.jquery.com/jquery.min.js"></script>
 <!--
 <script src="assets/scripts/jquery.repeatable.js"></script>
                            <script>
$(".todos_labels .repeatable").repeatable({
  addTrigger: ".todos_labels .add",
  deleteTrigger: ".todos_labels .delete",
  template: "#todos_labels"
});
                            </script>-->
<!-- fim conteúdo -->
