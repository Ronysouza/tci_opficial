<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){

    }
    elseif($cmd == "edt"){
     
    }
}
elseif(isset($_GET['del'])){

}

?>
<script src="assets/scripts/serv-contratos.js"></script>
<script>
  function gerarContrato(self){
    $('#gerarContrato').removeClass('disabled');
    $('#gerarContrato').attr('href','serv-addcontratos.php?id='+$(self).val());
  }
</script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-file-contract icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Gestão de Contratos</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de contratos
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
                            <a href="serv-contratostemplates.php" class="nav-link text-dark">Templates</a>

                            
                        
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

                    <h5 class="card-title">Lista de clientes</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <div class="input-group w-25">
                      <select class="mb-2 form-control" onchange="gerarContrato(this)">
                        <option selected disabled>Selecione o cliente</option>
                        <?
                          $resp = $con->query('select id,razaoSocial_nome from tbl_clientes where tipoCliente = "on"');
                          while($row = $resp->fetch_array()){
                            echo '<option value="'.$row['id'].'">'.$row['razaoSocial_nome'].'</option>';
                          }
                        ?>
                      </select>
                      <div class="input-group-append">
                        <a class="btn btn-light disabled mb-3" href="#" id="gerarContrato">Gerar</a>
                      </div>
                    </div>
                    
                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:2%">Tipo</th>
                                <th>Nome/Raz&atilde;o Social</th>
                                <th style="width:12%">CNPJ/CPF</th>
                                <th style="width:14%">Respons&aacute;vel</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                       
                    
                    //limite para pagina��o
                    $limit = 40;
                    //quantas p�ginas adjacentes aparecer�o junto a pagina corrente
                    $adjacents = 2;

                    //busca o total de linhas
                    $sql_totais = "SELECT COUNT(*) 'total_rows' FROM tbl_clientes";

                    $res = $con->query($sql_totais)->fetch_object();
                    $total_rows = $res->total_rows;
                    $total_pages = ceil($total_rows / $limit);

                    $start = 1;
                    $end = $total_pages;
                    
                    if(isset($_GET['page']) && $_GET['page'] != "") {
                      $page = $_GET['page'];
                      $offset = $limit * ($page-1);
                    } else {
                      $page = 1;
                      $offset = 0;
                    }
                    //efetua a busca dos clientes para a cria��o dos contratos
                    $query = "select * from tbl_clientes where id in (select id_cliente from tbl_doc_contrato group by id_cliente) limit $offset, $limit";
                    
                    $result = $con->query($query);
                    if($result->num_rows > 0) {
                        
                      while($row = $result->fetch_object()) {?>
                        
                         <?php #$result->close(); ?>
                      </div>
                   </div>
                  </div>
                  
                  <?php
                        #busca se ja existe algum contrato cadastrado no banco
                        $busca_contrato = "select id,criacao from tbl_doc_contrato where id_cliente = '".$row->id."'";
                        $exec_contratos = $con->query($busca_contrato);
                        
                        
                        if($exec_contratos->num_rows > 0){
                          
                          
                          $exibe_contrato = '
                          <form method="post" action="Pages/Servicos/GestaoContratos/Criacao/gerapdf.php">
                          <select name="id" class="form-control">
                            <option>Selecione o Contrato</option>';
                          while($contrato = $exec_contratos->fetch_object()) {
                              $exibe_contrato.="<option value='". $contrato->id."'>".DataHora($contrato->criacao,'exibe')."</option>";
                          } 
                          $exibe_contrato .='
                          </select>
                          <input class="btn btn-light mt-2" type="submit" value="Gerar PDF">
                          </form>
                          <!--
                          <a href="Pages/Servicos/GestaoContratos/Criacao/gerapdf.php?id='.@$contrato->id.'" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">
                          <i class="fa fa-file-pdf" aria-hidden="true" title="Gerar arquivo PDF"></i>
                          -->
                          ';
                        }else{
                          $exibe_contrato = '';
                        }
                        echo '<tr>
                        <td>'.str_pad($row->id,3,"0",STR_PAD_LEFT).'</td>
                        <td>'.$row->tipoPessoa.'</td>
                        <td>'.$row->razaoSocial_nome.'</td>
                        <td>'.$row->cnpj_cpf.'</td>
                        <td>'.$row->nomeResponsavel.'</td>
                        <td>'.$exibe_contrato.'</td>
                        <td></td>
                    </tr>';
                      }
                    }
                    //Here we generates the range of the page numbers which will display.
                    if($total_pages <= (1+($adjacents * 2))) {
                      $start = 1;
                      $end   = $total_pages;
                    } else {
                      if(($page - $adjacents) > 1) { 
                        if(($page + $adjacents) < $total_pages) { 
                          $start = ($page - $adjacents);            
                          $end   = ($page + $adjacents);         
                        } else {             
                          $start = ($total_pages - (1+($adjacents*2)));  
                          $end   = $total_pages;               
                        }
                      } else {               
                        $start = 1;                                
                        $end   = (1+($adjacents * 2));             
                      }
                    }
                   
                                
                        
                            ?>
                            <?php if($total_pages > 1) { ?>
                            <tr><td colspan="8">
                            <ul class="pagination pagination-sm justify-content-center">
                              <!-- Link of the first page -->
                              <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
                                <a class='page-link' href='?page=1'><<</a>
                              </li>
                              <!-- Link of the previous page -->
                              <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
                                <a class='page-link' href='?page=<?php ($page>1 ? print($page-1) : print 1)?>'><</a>
                              </li>
                              <!-- Links of the pages with page number -->
                              <?php for($i=$start; $i<=$end; $i++) { ?>
                              <li class='page-item <?php ($i == $page ? print 'active' : '')?>'>
                                <a class='page-link' href='?page=<?php echo $i;?>'><?php echo $i;?></a>
                              </li>
                              <?php } ?>
                              <!-- Link of the next page -->
                              <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                                <a class='page-link' href='?page=<?php ($page < $total_pages ? print($page+1) : print $total_pages)?>'>></a>
                              </li>
                              <!-- Link of the last page -->
                              <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                                <a class='page-link' href='?page=<?php echo $total_pages;?>'>>>                      
                                </a>
                              </li>
                            </ul>
                            </td></tr>
                         <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- fim conteúdo -->
