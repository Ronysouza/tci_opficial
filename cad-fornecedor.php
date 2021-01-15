<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];
    
    $temp = explode('-',$_POST['cidade']);
    $cidade = $_POST['cidade'];
    $ibge = 0;//$temp[0];

    if($cmd == "add"){
        $existe = $con->query('select id from tbl_clientes where cnpj_cpf = "'.$_POST['cnpj_cpf'].'"');
        if($_POST['cnpj_cpf'] == "" || $existe->num_rows == 0){
          
		  if ($_POST['estrangeiro'] == 0) {
                $Id_Pais_Origem         = 0;
                $contato_cad_form       = '';
                $documento_cad          = '';
                $cep_estrangeiro        = '';
                $estado_estrangeiro     = '';
                $cidade_estrangeiro     = '';
                $logradouro_estrangeiro = '';
                $bairro_estrangeiro     = '';
                $numero_estrangeiro     = '';
                
                
            } else {
                $Id_Pais_Origem         = $_POST['PaisOrigem'];
                $contato_cad_form       = $_POST['contato_cad'];
                $documento_cad          = $_POST['documento_cad'];
                $cep_estrangeiro        = $_POST['cep_estrangeiro'];
                $estado_estrangeiro     = $_POST['estado_estrangeiro'];
                $cidade_estrangeiro     = $_POST['cidade_estrangeiro'];
                $logradouro_estrangeiro = $_POST['logradouro_estrangeiro'];
                $bairro_estrangeiro     = $_POST['bairro_estrangeiro'];
                $numero_estrangeiro     = $_POST['numero_estrangeiro'];
            }
			$con->query("insert into tbl_clientes(tipoPessoa,razaoSocial_nome, cnpj_cpf, nomeResponsavel, logradouro, numero,
			complemento, bairro, cidade, cep, email, telefoneEmpresa, telefoneWhatsapp, 
			cpfResponsavel, observacao, estado, tipoCliente, tipoFornecedor,
			tipoFuncionario, tipoTecnico, ibge, ie,id_pais_origem,contato_estrangeiro,cpf_cnpj_financeiro,des_conta_banco,
			des_agencia_banco,des_banco, documento_estrangeiro
			,cep_estrangeiro,estado_estrangeiro,cidade_estrangeiro,logradouro_estrangeiro,bairro_estrangeiro,numero_estrangeiro,estrangeiro) values(
                '".$_POST['tipoPessoa']."',
                '".$_POST['razaoSocial_nome']."',
                '".$_POST['cnpj_cpf']."',
                '".$_POST['nomeResponsavel']."',
                '".$_POST['logradouro']."',
                '".$_POST['numero']."',
                '".$_POST['complemento']."',
                '".$_POST['bairro']."',
                '".$cidade."',
                '".$_POST['cep']."',
                '".$_POST['email']."',
                '".$_POST['telefoneEmpresa']."',
                '".$_POST['telefoneWhatsapp']."',
                '".$_POST['cpfResponsavel']."',
                '".$_POST['observacao']."',
                '".$_POST['estado']."',
                '".$_POST['tipo_cliente']."',
                '".$_POST['tipo_fornecedor']."',
                '".$_POST['tipo_funcionario']."',
                '".$_POST['tipo_tecnico']."',
                '".$ibge."',
                '".$_POST['ie']."',
				'".$Id_Pais_Origem."',
				'".$contato_cad_form."',
				'".$_POST['cnpj_cpf_cad']."',
				'".$_POST['conta_cad']."',
				'".$_POST['agencia_cad']."', 
				'".$_POST['banco_cad']."',
				'".$documento_cad."',
				'" . $cep_estrangeiro . "',
		        '" . $estado_estrangeiro . "',
		 	    '" . $cidade_estrangeiro . "',
			    '" . $logradouro_estrangeiro . "',
		 	    '" . $bairro_estrangeiro . "',
			    '" . $numero_estrangeiro . "',
                '".$_POST['estrangeiro']."'
            )");
            //var_dump($con->error);
            //redirect($con->error);
        }
        else{
            var_dump($_POST['cnpj_cpf']);
            echo "<script>alert('Erro! CNPJ/CPF já cadastrado, usuário não cadastrado');</script>";
        }
    }
    elseif($cmd == "edt"){
		
		if ($_POST['estrangeiro'] == 0) {
                $Id_Pais_Origem         = 0;
                $contato_cad_form       = '';
                $documento_cad          = '';
                $cep_estrangeiro        = '';
                $estado_estrangeiro     = '';
                $cidade_estrangeiro     = '';
                $logradouro_estrangeiro = '';
                $bairro_estrangeiro     = '';
                $numero_estrangeiro     = '';
                
                
            } else {
                $Id_Pais_Origem         = $_POST['PaisOrigem'];
                $contato_cad_form       = $_POST['contato_cad'];
                $documento_cad          = $_POST['documento_cad'];
                $cep_estrangeiro        = $_POST['cep_estrangeiro'];
                $estado_estrangeiro     = $_POST['estado_estrangeiro'];
                $cidade_estrangeiro     = $_POST['cidade_estrangeiro'];
                $logradouro_estrangeiro = $_POST['logradouro_estrangeiro'];
                $bairro_estrangeiro     = $_POST['bairro_estrangeiro'];
                $numero_estrangeiro     = $_POST['numero_estrangeiro'];
            }	
		
        $con->query("update tbl_clientes set
            tipoPessoa = '".$_POST['tipoPessoa']."',
            razaoSocial_nome = '".$_POST['razaoSocial_nome']."',
            cnpj_cpf = '".$_POST['cnpj_cpf']."',
            nomeResponsavel = '".$_POST['nomeResponsavel']."',
            logradouro = '".$_POST['logradouro']."',
            numero = '".$_POST['numero']."',
            complemento = '".$_POST['complemento']."',
            bairro = '".$_POST['bairro']."',
            cidade = '".$cidade."',
            cep = '".$_POST['cep']."',
            email = '".$_POST['email']."',
            telefoneEmpresa = '".$_POST['telefoneEmpresa']."',
            telefoneWhatsapp = '".$_POST['telefoneWhatsapp']."',
            cpfResponsavel = '".$_POST['cpfResponsavel']."',
            observacao = '".$_POST['observacao']."',
            estado = '".$_POST['estado']."',
            tipoCliente = '".$_POST['tipo_cliente']."',
            tipoFornecedor = '".$_POST['tipo_fornecedor']."',
            tipoFuncionario = '".$_POST['tipo_funcionario']."',
            tipoTecnico = '".$_POST['tipo_tecnico']."',
            ibge = '".$ibge."',
			id_pais_origem = '".$Id_Pais_Origem."',
			contato_estrangeiro = '".$contato_cad_form."',
			cpf_cnpj_financeiro = '".$_POST['cnpj_cpf_cad']."',
			des_conta_banco = '".$_POST['conta_cad']."',
			des_agencia_banco = '".$_POST['agencia_cad']."',
			des_banco = '".$_POST['banco_cad']."',
			documento_estrangeiro =   '".$documento_cad."',
			cep_estrangeiro =   '" . $cep_estrangeiro . "',
		    estado_estrangeiro =   '" . $estado_estrangeiro . "',
		    cidade_estrangeiro =   '" . $cidade_estrangeiro . "',
	  	    logradouro_estrangeiro =   '" . $logradouro_estrangeiro . "',
		    bairro_estrangeiro =   '" . $bairro_estrangeiro . "',
	  	    numero_estrangeiro =   '" . $numero_estrangeiro . "',
            estrangeiro = '".$_POST['estrangeiro']."'
            where id  = ".$_POST['id']
        );
        redirect($con->error);
    }
}
elseif(isset($_GET['del'])){
    $con->query('update tbl_clientes set status = 0 where id ='.$_GET['del']);
    redirect($con->error);
}

?>
<script>
    async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Clientes Cadastrados</h5>');
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
        console.log(resp);
        $("#estado").val(resp['uf']);
        listarCidades();
        $("#cidade").val(resp['localidade']);
    }
    function listarCidades(){
        $.post('core/ajax/cad-cliente/getCidade.php',{estado:$('#estado').val()},function(resp){
            $('#cidade').empty();
            $('#cidade').append(resp);
        });
    }
    function est(self){
        if($(self).val()=='1'){
            $('.estrangeiroInput').removeAttr('required');
            $('.estrangeiroLabel').addClass('d-none');
			$("#TabEstrageiroCad").removeClass('nav-item invisible');
            $("#TabEstrageiroCad").addClass('nav-item visible');

			
			
        }
        else{
            $('.estrangeiroInput').attr('required',true);
            $('.estrangeiroLabel').removeClass('d-none');
			$("#TabEstrageiroCad").removeClass('nav-item visible');
            $("#TabEstrageiroCad").addClass('nav-item invisible');

        }
    }
    $(document).ready(function(){
        $("#campoPesquisa").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tablePrint tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $(':input:not(textarea)').on("keydown",function(){
            if (event.key == "Enter") {
                event.preventDefault();
            }
        });
    });
</script>
<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-truck-loading icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de Fornecedores</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de fornecedores
                </div>
            </div>

        </div>
        <div class="page-title-actions">

            <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
                <i class="fas fa-user-plus"></i>
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=fornecedores">
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

                    <h5 class="card-title">Fornecedores cadastrados</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:4%">Pessoa</th>
                                <th style="width:20%">Nome</th>
                                <th style="width:10%">CNPJ</th>
                                <th style="width:14%">Contato</th>
                                <th style="width:8%">Cidade</th>
                                <th>Observações</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_clientes where tipoFornecedor = "on" and status = 1 order by id desc');
                                
                                while($row = $resp->fetch_assoc()){
								if ($row['estrangeiro']==0) {
									$EstadoGridFornecedor = $row['cidade'].'/'.$row['estado'];
								} else {
									$EstadoGridFornecedor = 'EX';
								}
									
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.($row['tipoPessoa'] == "PF" ? "Física" : "Júridica").'</td>
                                            <td>'.$row['razaoSocial_nome'].'</td>
                                            <td>'.$row['cnpj_cpf'].'</td>
                                            <td>'.$row['telefoneEmpresa'].'</td>
                                            <td>'.$EstadoGridFornecedor.'</td>
                                            <td>'.$row['observacao'].'</td>
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
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo fornecedor</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
			<?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_clientes where id = '.$_GET['edt']);
                            $row = $resp->fetch_assoc();
							
							
                        }
                    ?>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
					<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
					  aria-selected="true" Style ="font-weight: bold !important; color: #000000 !important;">Geral</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link" id="financeiro-tab" data-toggle="tab" href="#financeiro" role="tab" aria-controls="financeiro"
					  aria-selected="false" Style ="font-weight: bold !important; color: #000000 !important;">Financeiro</a>
				  </li>
				  
				  <li id="TabEstrageiroCad" name="TabEstrageiroCad"
					<?php if ($row['estrangeiro']==0) {
									echo 'class="nav-item invisible"';
								} else {
									echo 'class="nav-item visible"';
								}

								?>>
					<a class="nav-link" id="estrangeiro-tab" data-toggle="tab" href="#estrangeiro_tab" role="tab" aria-controls="estrangeiro_tab"
					  aria-selected="false" Style ="font-weight: bold !important; color: #000000 !important;">Estrangeiro</a>
				  </li>
				  
				</ul>
				
                <form method="post">
                 <div class="tab-content" id="myTabContent">
				 <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    

                    <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">
                    <div class="row">

                        <div class="col">

                            <div class="row">
                                <div class="col">
                                    <label for="razaoSocial_nome">Razão social / Nome<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['razaoSocial_nome'];?>" class="form-control mb-3" name="razaoSocial_nome" id="razaoSocial_nome" required>
                                </div>
                                <div class="col-2">
                                    <label for="tipoPessoa">Tipo Pessoa<span class="ml-2 text-danger">*</span></label>
                                    <select class="form-control mb-3" name="tipoPessoa" id="tipoPessoa" required>
                                        <option value="PF" <?php echo !isset($row['tipoPessoa']) || $row['tipoPessoa'] == "PF" ? "selected":"" ;?>>Fisíca</option>
                                        <option value="PJ" <?php echo $row['tipoPessoa'] == "PJ" ? "selected" : "";?>>Jurídica</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                            <label for="cnpj_cpf">CNPJ / CPF
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['cnpj_cpf'];
                                        ?>" class="form-control mb-3 estrangeiroInput" name="cnpj_cpf" id="cnpj_cpf" <?php
              if ($row['estrangeiro'] == 0) {
              echo ' required ';
              } else {
              echo ' ';
              }
              ?>>
                                        </div>
                            </div>

                            <div class="row">
							    <div class="col-2">
                                    <label for="estrangeiro">Estrangeiro</label>
                                    <select class="form-control mb-3" name="estrangeiro" id="estrangeiro" onchange="est(this)">
                                        <option value="0" <?=$row['estrangeiro']==0?'selected':''?>>Não</option>
                                        <option value="1" <?=$row['estrangeiro']==1?'selected':''?>>Sim</option>
                                    <select>

                                </div>
								
                                <div class="col-3">
                                    <label for="ie">IE</label>
                                    <input type="text" class="form-control mb-3" name="ie" id="ie">
                                </div>
                                <div class="col">
                                    <label for="nomeResponsavel">Nome responsável</label>
                                    <input type="text" value="<?php echo $row['nomeResponsavel'];?>" class="form-control mb-3" name="nomeResponsavel" id="nomeResponsavel">
                                </div>
                                <div class="col-3">
                                    <label for="cpfResponsavel">CPF responsável</label>
                                    <input type="text" value="<?php echo $row['cpfResponsavel'];?>" class="form-control mb-3" name="cpfResponsavel" id="cpfResponsavel">
                                </div>
                            </div>
							
							
							

                            <div class="row">
                                <div class="col">
                                    <label for="email">Email<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['email'];?>" class="form-control mb-3" name="email" id="email" required>
                                </div>
                                <div class="col">
                                    <label for="telefoneEmpresa">Telefone/Celular<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text" value="<?php echo $row['telefoneEmpresa'];?>" class="form-control mb-3" name="telefoneEmpresa" id="telefoneEmpresa" >
                                </div>
                                <div class="col">
                                    <label for="telefoneWhatsapp">Whatsapp</label>
                                    <input type="text" value="<?php echo $row['telefoneWhatsapp'];?>" class="form-control mb-3" name="telefoneWhatsapp" id="telefoneWhatsapp">
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="row">
                                <div class="col-3">
      <label for="cep">CEP<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text"
<?php if ($row['estrangeiro']==0) {
									echo ' required ';
								} else {
									echo ' ';
								}

								?>

									value="<?php echo $row['cep'];?>" class="form-control mb-3 estrangeiroInput" name="cep" id="cep"  onchange="getCidade(this)">
                                </div>
                                <div class="col-4">
                                    <label for="estado">Estado<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <select name="estado" value="<?php echo $row['estado'];?>" id="estado" class="form-control mb-3 estarngeiroInput"  <?php if ($row['estrangeiro']==0) {
									echo ' required ';
								} else {
									echo ' ';
								}

								?>
									
									
									><!-- onchange="listarCidades()" -->
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS" selected>Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="cidade">Cidade<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text" value="<?php echo $row['cidade'];?>" class="form-control mb-3 estrangeiroInput" name="cidade" id="cidade" >
                                    <!--<select name="cidade" id="cidade" class="form-control"></select>-->
                                </div>
                            </div>

                            <div class="row">
                                
                                <div class="col">
                                    <label for="logradouro">Logradouro<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text" value="<?php echo $row['logradouro'];?>" class="form-control mb-3 estrangeiroInput" name="logradouro" id="logradouro" <?php if ($row['estrangeiro']==0) {
									echo ' required ';
								} else {
									echo ' ';
								}

								?> >
                                </div>
								<div class="col">
                                    <label for="bairro">Bairro<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text" value="<?php echo $row['bairro'];?>" class="form-control mb-3 estrangeiroInput" name="bairro" id="bairro" 
<?php if ($row['estrangeiro']==0) {
									echo ' required ';
								} else {
									echo ' ';
								}

								?>
									>
                                </div>
                                <div class="col-2">
                                    <label for="numero">Número<span class="ml-2 text-danger estrangeiroLabel <?=$row['estrangeiro']!=0?'d-none':''?>">*</span></label>
                                    <input type="text" value="<?php echo $row['numero'];?>" class="form-control mb-3 estrangeiroInput" name="numero" id="numero" <?php if ($row['estrangeiro']==0) {
									echo ' required ';
								} else {
									echo ' ';
								}

								?>  >
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <label for="complemento">Complemento</label>
                                    <input type="text" value="<?php echo $row['complemento'];?>" class="form-control mb-3" name="complemento" id="complemento">
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="row">
                                <div class="col">
                                    <label for="observacao">Observações</label>
                                    <textarea name="observacao" id="bservacao" maxlength="400" class="form-control mb-3" style="resize: none;"><?php echo $row['observacao'];?></textarea>
                                </div>
                            </div>
                    
                        </div>
                        <div class="col-2">

                            <div class="card">
                                <div class="row mb-2 mt-4">
                                    <div class="col d-flex">
                                        <div class="custom-control custom-switch m-auto w-75">
                                            <input type="checkbox" class="custom-control-input" id="tipo_cliente" name="tipo_cliente" <?php echo $row['tipoCliente'] == "on" ? 'checked' : '' ;?>>
                                            <label class="custom-control-label" for="tipo_cliente">Cliente</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="row mb-2 mt-2">
                                    <div class="col d-flex">
                                        <div class="custom-control custom-switch m-auto w-75">
                                            <input type="checkbox" class="custom-control-input" id="tipo_fornecedor" name="tipo_fornecedor" <?php echo $row['tipoFornecedor'] == "on" ? 'checked' : '' ;?>>
                                           <label class="custom-control-label" for="tipo_fornecedor">Fornecedor</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="row mb-2 mt-2">
                                    <div class="col d-flex">
                                        <div class="custom-control custom-switch m-auto w-75">
                                            <input type="checkbox" class="custom-control-input" id="tipo_funcionario" name="tipo_funcionario" <?php echo $row['tipoFuncionario'] == "on" ? 'checked' : '' ;?>>
                                            <label class="custom-control-label" for="tipo_funcionario">Funcionário</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="row mb-4 mt-2">
                                    <div class="col d-flex">
                                        <div class="custom-control custom-switch m-auto w-75">
                                            <input type="checkbox" class="custom-control-input" id="tipo_tecnico" name="tipo_tecnico" <?php echo $row['tipoTecnico'] == "on" ? 'checked' : '' ;?>>
                                            <label class="custom-control-label" for="tipo_tecnico">Técnico</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <input id="needs-validation" class="d-none" type="submit" value="enviar">
                  </div>
				  
				    <div class="tab-pane fade" id="financeiro" role="tabpanel" aria-labelledby="financeiro-tab">
					
					
					<div class="row">
                        <div class="col">

                            <div class="row">
                                <div class="col">
                                    <label for="banco_cad">Banco</label>
                                    <input type="text" value="<?php echo $row['des_banco'];?>" class="form-control mb-3" name="banco_cad" id="banco_cad">
                                </div>
                                <div class="col-2">
                                    <label for="agencia_cad">Agência</label>
                                        <input type="text" value="<?php echo $row['des_agencia_banco'];?>" class="form-control mb-3" name="agencia_cad" id="agencia_cad">
                                </div>
                                <div class="col-3">
                                    <label for="conta_cad">Conta</label>
                                    <input type="text" value="<?php echo $row['des_conta_banco'];?>" class="form-control mb-3" name="conta_cad" id="conta_cad"  >
                                </div>
								<div class="col-4">
                                    <label for="cnpj_cpf_cad">CNPJ / CPF</label>
                                    <input type="text" value="<?php echo $row['cpf_cnpj_financeiro'];?>" class="form-control mb-3" name="cnpj_cpf_cad" id="cnpj_cpf_cad"  >
                                </div>
                            </div>                        
                    
                        </div>
                    </div>
	                </div>
					
					
					
					<div class="tab-pane fade" id="estrangeiro_tab" role="tabpanel" aria-labelledby="estrangeiro-tab">
					
					
					<div class="row">
                        <div class="col">

                            <div class="row">
                                <div class="col-6">
								  <label for="PaisOrigem">Pais de Origem</label>
								  <select class="form-control mb-2" name="PaisOrigem" id="PaisOrigem">
								  
								  <?php
                                      $query_paises = $con->query('select * from tbl_paises order by paisid ');
                                     while($row_paises = $query_paises->fetch_assoc()){    
                                      
									  if ($row_paises['paisId'] == $row['id_pais_origem']  ){
										echo '
								      <option value='.$row_paises['paisId'].' selected >'.$row_paises['paisNome'].'</option>';  
									  }else {
									  
									  echo '
								      <option value='.$row_paises['paisId'].' >'.$row_paises['paisNome'].'</option>';
							          }
							      }
							 
							       ?>   
                                   </select>
								   </div>
                                <div class="col-5">
                                    <label for="contato_cad">Contato</label>
                                        <input type="text" value="<?php echo $row['contato_estrangeiro'];?>" class="form-control mb-3" name="contato_cad" id="contato_cad">
                                </div>
								
								<div class="col-5">
                                    <label for="documento_cad">Documento</label>
                                        <input type="text" value="<?php echo $row['documento_estrangeiro'];?>" class="form-control mb-3" name="documento_cad" id="documento_cad">
                                </div>
                                
                            </div>     
							
							 <div class="divider"></div>
                           <div class="row">
                              <div class="col-3">
                                 <label for="cep_estrangeiro">CEP</label>
                                 <input type="text" value="<?php echo $row['cep_estrangeiro'];?>" class="form-control mb-3" name="cep_estrangeiro" id="cep_estrangeiro" >
                              </div>
                              <div class="col-4">
                                 <label for="estado_estrangeiro">Estado</label>
                                 <input type="text" value="<?php echo $row['estado_estrangeiro'];?>" class="form-control mb-3" name="estado_estrangeiro" id="estado_estrangeiro" >
                              </div>
                              <div class="col">
                                 <label for="cidade_estrangeiro">Cidade</label>
                                 <input type="text" value="<?php echo $row['cidade_estrangeiro'];?>" class="form-control mb-3 " name="cidade_estrangeiro" id="cidade_estrangeiro">
                                 <!--<select name="cidade" id="cidade" class="form-control"></select>-->
                              </div>
                           </div>
                           <div class="row">
                              <div class="col">
                                 <label for="logradouro_estrangeiro">Logradouro</label>
                                 <input type="text" value="<?php echo $row['logradouro_estrangeiro'];?>" class="form-control mb-3" name="logradouro_estrangeiro" id="logradouro_estrangeiro">
                              </div>
                              <div class="col">
                                 <label for="bairro_estrangeiro">Bairro</label>
                                 <input type="text" value="<?php echo $row['bairro_estrangeiro'];?>" class="form-control mb-3" name="bairro_estrangeiro" id="bairro_estrangeiro" >
                              </div>
                              <div class="col-2">
                                 <label for="numero_estrangeiro">Número</label>
                                 <input type="text" value="<?php echo $row['numero_estrangeiro'];?>" class="form-control mb-3" name="numero_estrangeiro" id="numero_estrangeiro" >
                              </div>
                           </div>
	
								
                    
                        </div>
                    </div>
	                </div>
				  
				  </div>
                </form>
				
                <script>
                    $(document).ready(function(){
                        $("#cpfResponsavel").mask('000.000.000-00', {reverse: true});
                        $("#telefoneEmpresa").mask('(99) 99999-9999');
                        $("#telefoneWhatsapp").mask('(99) 99999-9999');
						$("#ie").mask('99999999-99');
                        $("#cep").mask('99999-999');
                    });
                </script>

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