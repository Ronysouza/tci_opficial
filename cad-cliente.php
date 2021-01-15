<?php
include('header.php');
?>
<?php
if (isset($_POST['cmd'])) {
$cmd = $_POST['cmd'];
$temp   = explode('-', $_POST['cidade']);
$cidade = $_POST['cidade'];
$ibge   = 0; //$temp[0];


if ($cmd == "add") {
$existeDependente = $con->query('select id from tbl_clientes where cnpj_cpf = "' . $_POST['cnpj_cpf'] . '"');
while ($rowExiste = $existeDependente->fetch_assoc()) {
	$IdClienteNaBaseDeDados = $rowExiste['id']; 
}	

if ($_POST['cnpj_cpf'] == "" || $existe->num_rows == 0) {
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

if ($_POST['dependentes'] == 0) {
 $nome_dependente          = '';
 $data_nasc_dependente     = '';
 $idade_dependente         = '';
 $nome_dependente02         = '';
 $data_nasc_dependente02    = '';
 $idade_dependente02        = '';
 $nome_dependente03         = '';
 $data_nasc_dependente03    = '';
 $idade_dependente03        = '';
} else {
 $nome_dependente          = $_POST['nome_dependente'];
 $data_nasc_dependente     = $_POST['data_nasc_dependente'];
 $idade_dependente     = $_POST['idade_dependente'];
 $nome_dependente02          = $_POST['nome_dependente02'];
 $data_nasc_dependente02     = $_POST['data_nasc_dependente02'];
 $idade_dependente02     = $_POST['idade_dependente02'];
 $nome_dependente03          = $_POST['nome_dependente03'];
 $data_nasc_dependente03     = $_POST['data_nasc_dependente03'];
 $idade_dependente03     = $_POST['idade_dependente03'];
}




$con->query("insert into tbl_clientes(tipoPessoa,razaoSocial_nome, cnpj_cpf, nomeResponsavel, logradouro, numero, complemento, bairro, cidade, cep, email, telefoneEmpresa, telefoneWhatsapp, cpfResponsavel, observacao, estado, tipoCliente, tipoFornecedor, 
tipoFuncionario, tipoTecnico, ibge, ie,id_pais_origem,contato_estrangeiro, documento_estrangeiro,
cep_estrangeiro,estado_estrangeiro,cidade_estrangeiro,logradouro_estrangeiro,bairro_estrangeiro,numero_estrangeiro,dependentes,
nome_dependente,data_nasc_dependente,idade_dependente, 
nome_dependente02,data_nasc_dependente02,idade_dependente02, 
nome_dependente03,data_nasc_dependente03,idade_dependente03, 
estrangeiro) values(
'" . $_POST['tipoPessoa'] . "',
'" . $_POST['razaoSocial_nome'] . "',
'" . $_POST['cnpj_cpf'] . "',
'" . $_POST['nomeResponsavel'] . "',
'" . $_POST['logradouro'] . "',
'" . $_POST['numero'] . "',
'" . $_POST['complemento'] . "',
'" . $_POST['bairro'] . "',
'" . $cidade . "',
'" . $_POST['cep'] . "',
'" . $_POST['email'] . "',
'" . $_POST['telefoneEmpresa'] . "',
'" . $_POST['telefoneWhatsapp'] . "',
'" . $_POST['cpfResponsavel'] . "',
'" . $_POST['observacao'] . "',
'" . $_POST['estado'] . "',
'" . $_POST['tipo_cliente'] . "',
'" . $_POST['tipo_fornecedor'] . "',
'" . $_POST['tipo_funcionario'] . "',
'" . $_POST['tipo_tecnico'] . "',
'" . $ibge . "',
'" . $_POST['ie'] . "',
'" . $Id_Pais_Origem . "',
'" . $contato_cad_form . "',
'" . $documento_cad . "',
'" . $cep_estrangeiro . "',
'" . $estado_estrangeiro . "',
'" . $cidade_estrangeiro . "',
'" . $logradouro_estrangeiro . "',
'" . $bairro_estrangeiro . "',
'" . $numero_estrangeiro . "',
'" . $_POST['dependentes'] . "', 
'" . $nome_dependente . "',
'" . $data_nasc_dependente . "',
'" . $idade_dependente . "',
'" . $nome_dependente02 . "',
'" . $data_nasc_dependente02 . "',
'" . $idade_dependente02 . "',
'" . $nome_dependente03 . "',
'" . $data_nasc_dependente03 . "',
'" . $idade_dependente03 . "',
'" . $_POST['estrangeiro'] . "'
)");

for ($iQtdDependentesGridEmTela = 1; $iQtdDependentesGridEmTela <= $_POST['QtdDependentesTela']; $iQtdDependentesGridEmTela++) {
 $DeletaEsseDependente = $con->query('delete from tbl_clientes_dependentes where id_cliente = "' . $IdClienteNaBaseDeDados . '" and id_dependente = "'.$iQtdDependentesGridEmTela.'" ');
  
$existeDependente = $con->query('select id from tbl_clientes where cnpj_cpf = "' . $_POST['cnpj_cpf'] . '"');
while ($rowExiste = $existeDependente->fetch_assoc()) {
	$IdClienteNaBaseDeDados = $rowExiste['id']; 
}


$existeEsseDependente = $con->query('select id_cliente from tbl_clientes_dependentes where id_cliente = "' . $IdClienteNaBaseDeDados . '" and id_dependente = "'.$iQtdDependentesGridEmTela.'" ');
$myDate = date('d/m/Y');
if ($existeEsseDependente->num_rows == 0) {
	
	if ( Trim($_POST['nome_dependente'.$iQtdDependentesGridEmTela]) != '' ){ 
	$con->query(" insert into tbl_clientes_dependentes (id_cliente, id_dependente, nome_dependente, data_nascimento, 
	              data_maioridade, data_insercao_dependente)
				  values('" . $IdClienteNaBaseDeDados . "',
                         '" . $iQtdDependentesGridEmTela . "',
                         '" . $_POST['nome_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $_POST['data_nasc_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $_POST['data_maioridade_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $myDate. "')");
	}

} else {
	$con->query(" update  tbl_clientes_dependentes 
	                 set  nome_dependente = '" . $_POST['nome_dependente'.$iQtdDependentesGridEmTela] . "',
						  data_nascimento = '" . $_POST['data_nasc_dependente'.$iQtdDependentesGridEmTela] . "',
                          data_maioridade = '" . $_POST['data_maioridade_dependente'.$iQtdDependentesGridEmTela] . "',
                          data_insercao_dependente = '" . $myDate. "'
				   where id_cliente = '" . $IdClienteNaBaseDeDados . "'
                     and id_dependente = '" . $iQtdDependentesGridEmTela . "' ");
}	

}
var_dump($con->error);
//redirect($con->error);
} else {
var_dump($_POST['cnpj_cpf']);
echo "<script>alert('Erro! CNPJ/CPF já cadastrado, usuário não cadastrado');</script>";
}
} elseif ($cmd == "edt") {

 $IdClienteNaBaseDeDados = $_POST['id'];	


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

if ($_POST['dependentes'] == 0) {
 $nome_dependente          = '';
 $data_nasc_dependente     = '';
 $idade_dependente         = '';
 $nome_dependente02         = '';
 $data_nasc_dependente02    = '';
 $idade_dependente02        = '';
 $nome_dependente03         = '';
 $data_nasc_dependente03    = '';
 $idade_dependente03        = '';
} else {
 $nome_dependente          = $_POST['nome_dependente'];
 $data_nasc_dependente     = $_POST['data_nasc_dependente'];
 $idade_dependente     = $_POST['idade_dependente'];
 $nome_dependente02          = $_POST['nome_dependente02'];
 $data_nasc_dependente02     = $_POST['data_nasc_dependente02'];
 $idade_dependente02     = $_POST['idade_dependente02'];
 $nome_dependente03          = $_POST['nome_dependente03'];
 $data_nasc_dependente03     = $_POST['data_nasc_dependente03'];
 $idade_dependente03     = $_POST['idade_dependente03'];
}



$con->query("update tbl_clientes set
tipoPessoa = '" . $_POST['tipoPessoa'] . "',
razaoSocial_nome = '" . $_POST['razaoSocial_nome'] . "',
cnpj_cpf = '" . $_POST['cnpj_cpf'] . "',
nomeResponsavel = '" . $_POST['nomeResponsavel'] . "',
logradouro = '" . $_POST['logradouro'] . "',
numero = '" . $_POST['numero'] . "',
complemento = '" . $_POST['complemento'] . "',
bairro = '" . $_POST['bairro'] . "',
cidade = '" . $cidade . "',
cep = '" . $_POST['cep'] . "',
email = '" . $_POST['email'] . "',
telefoneEmpresa = '" . $_POST['telefoneEmpresa'] . "',
telefoneWhatsapp = '" . $_POST['telefoneWhatsapp'] . "',
cpfResponsavel = '" . $_POST['cpfResponsavel'] . "',
observacao = '" . $_POST['observacao'] . "',
estado = '" . $_POST['estado'] . "',
tipoCliente = '" . $_POST['tipo_cliente'] . "',
tipoFornecedor = '" . $_POST['tipo_fornecedor'] . "',
tipoFuncionario = '" . $_POST['tipo_funcionario'] . "',
tipoTecnico = '" . $_POST['tipo_tecnico'] . "',
ibge = '" . $ibge . "',
id_pais_origem = '" . $Id_Pais_Origem . "',
contato_estrangeiro = '" . $contato_cad_form . "',
documento_estrangeiro =   '" . $documento_cad . "',
cep_estrangeiro =   '" . $cep_estrangeiro . "',
estado_estrangeiro =   '" . $estado_estrangeiro . "',
cidade_estrangeiro =   '" . $cidade_estrangeiro . "',
logradouro_estrangeiro =   '" . $logradouro_estrangeiro . "',
bairro_estrangeiro =   '" . $bairro_estrangeiro . "',
numero_estrangeiro =   '" . $numero_estrangeiro . "',
dependentes =   '" . $_POST['dependentes'] . "',
nome_dependente =   '" . $_POST['nome_dependente'] . "',
data_nasc_dependente =   '" . $_POST['data_nasc_dependente'] . "',
idade_dependente =   '" . $_POST['idade_dependente'] . "',
nome_dependente02 =   '" . $_POST['nome_dependente02'] . "',
data_nasc_dependente02 =   '" . $_POST['data_nasc_dependente02'] . "',
idade_dependente02 =   '" . $_POST['idade_dependente02'] . "',
nome_dependente03 =   '" . $_POST['nome_dependente03'] . "',
data_nasc_dependente03 =   '" . $_POST['data_nasc_dependente03'] . "',
idade_dependente03 =   '" . $_POST['idade_dependente03'] . "',
estrangeiro = '" . $_POST['estrangeiro'] . "'
where id  = " . $_POST['id']);


for ($iQtdDependentesGridEmTela = 1; $iQtdDependentesGridEmTela <= $_POST['QtdDependentesTela']; $iQtdDependentesGridEmTela++) {
$DeletaEsseDependente = $con->query('delete from tbl_clientes_dependentes where id_cliente = "' . $IdClienteNaBaseDeDados . '" and id_dependente = "'.$iQtdDependentesGridEmTela.'" ');
   

$existeEsseDependente = $con->query('select id_cliente from tbl_clientes_dependentes where id_cliente = "' . $IdClienteNaBaseDeDados . '" and id_dependente = "'.$iQtdDependentesGridEmTela.'" ');
	$myDate = date('d/m/Y');
if ($existeEsseDependente->num_rows == 0) {

	if ( ($_POST['nome_dependente'.$iQtdDependentesGridEmTela]) != '' ){ 
	
	$con->query(" insert into tbl_clientes_dependentes (id_cliente, id_dependente, nome_dependente, data_nascimento, 
	              data_maioridade, data_insercao_dependente)
				  values('" . $IdClienteNaBaseDeDados . "',
                         '" . $iQtdDependentesGridEmTela . "',
                         '" . $_POST['nome_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $_POST['data_nasc_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $_POST['data_maioridade_dependente'.$iQtdDependentesGridEmTela]. "',
                         '" . $myDate. "')");
    }
} else {
	$con->query(" update  tbl_clientes_dependentes 
	                 set  nome_dependente = '" . $_POST['nome_dependente'.$iQtdDependentesGridEmTela] . "',
						  data_nascimento = '" . $_POST['data_nasc_dependente'.$iQtdDependentesGridEmTela] . "',
                          data_maioridade = '" . $_POST['data_maioridade_dependente'.$iQtdDependentesGridEmTela] . "',
                          data_insercao_dependente = '" . $myDate. "'
				   where id_cliente = '" . $IdClienteNaBaseDeDados . "'
                     and id_dependente = '" . $iQtdDependentesGridEmTela . "' ");
}	

}



redirect($con->error);
}
} elseif (isset($_GET['del'])) {
$con->query('update tbl_clientes set status = 0 where id =' . $_GET['del']);
redirect($con->error);
}
?>
<script>
    async function imprimir() {
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
    function getCidade(self) {
        const xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "https://viacep.com.br/ws/" + $(self).val().replace('-', '') + "/json/", false);
        // false for synchronous request
        xmlHttp.send(null);
        const resp = JSON.parse(xmlHttp.responseText);
        console.log(resp);
        $("#estado").val(resp['uf']);
        listarCidades();
        $("#cidade").val(resp['localidade']);
    }
    function listarCidades() {
        $.post('core/ajax/cad-cliente/getCidade.php', {
            estado: $('#estado').val()
        }
            , function (resp) {
                $('#cidade').empty();
                $('#cidade').append(resp);
            }
        );
    }
    function est(self) {
        if ($(self).val() == '1') {
            $('.estrangeiroInput').removeAttr('required');
            $('.estrangeiroLabel').addClass('d-none');
            $("#TabEstrageiroCad").removeClass('nav-item invisible');
            $("#TabEstrageiroCad").addClass('nav-item visible');
        }
        else {
            $('.estrangeiroInput').attr('required', true);
            $('.estrangeiroLabel').removeClass('d-none');
            $("#TabEstrageiroCad").removeClass('nav-item visible');
            $("#TabEstrageiroCad").addClass('nav-item invisible');
        }
    }

    function DependentesSelecionado(self) {
        if ($(self).val() == '1') {
            $("#TabDependentesCad").removeClass('nav-item invisible');
            $("#TabDependentesCad").addClass('nav-item visible');
        }
        else {
            $("#TabDependentesCad").removeClass('nav-item visible');
            $("#TabDependentesCad").addClass('nav-item invisible');
        }
    }
	
	function CalculaDataMaioridade(self, self2) {
		//var DataMaioridadeDependente = 'data_maioridade_dependente'+self2;
		//alert(DataDependente);
		var DataDependente = $(self).val(); // $('#data_nasc_dependente1').val();     
		var time = new Date(DataDependente);
        var outraData = new Date();
    	time.setFullYear(time.getFullYear() + 18);
		var DiaNascimento = (time.getDate() + 1 );
		if (DiaNascimento > 31){
			DiaNascimento = DiaNascimento -1;
		}
		
		if ((time.getMonth() + 1) < 10){
		  if ((DiaNascimento) < 10){	
		  var minhaData = time.getFullYear() + '-0' +(time.getMonth() + 1) + '-0' + (DiaNascimento);
		  } else{
		  var minhaData = time.getFullYear() + '-0' +(time.getMonth() + 1) + '-' + (DiaNascimento);
		  	  
		  }
        }else{
		  if ((DiaNascimento) < 10){	 
		 var minhaData = time.getFullYear() + '-' +(time.getMonth() + 1) + '-0' + (DiaNascimento);
		  }else{
		var minhaData = time.getFullYear() + '-' +(time.getMonth() + 1) + '-' + (DiaNascimento);
		 	  
		  }
        }
		$("#data_maioridade_dependente"+self2).val(minhaData);
    }
	
	function InserirNovoDependentes() {
		rQtdDependentesTela = $('#QtdDependentesTela').val();
		rQtdDependentesTela = parseFloat(rQtdDependentesTela) + 1;
		$("#QtdDependentesTela").val(rQtdDependentesTela);
		$('#linhaDependentes').append('<div class="row"><div class="col-5"><label for="nome_dependente'+(rQtdDependentesTela)+'">Nome</label><input type="text" value="" class="form-control mb-3" name="nome_dependente'+(rQtdDependentesTela)+'" id="nome_dependente'+(rQtdDependentesTela)+'"></div><div class="col-3"><label for="data_nasc_dependente'+(rQtdDependentesTela)+'">Data Nascimento</label><input type="date" value="" class="form-control mb-3" name="data_nasc_dependente'+(rQtdDependentesTela)+'"   onchange="CalculaDataMaioridade(this,'+(rQtdDependentesTela)+')"  id="data_nasc_dependente'+(rQtdDependentesTela)+'"> </div><div class="col-3"> <label for="idade_dependente'+(rQtdDependentesTela)+'">Data Maioridade</label> <input type="date" value="" class="form-control mb-3" name="data_maioridade_dependente'+(rQtdDependentesTela)+'" id="data_maioridade_dependente'+(rQtdDependentesTela)+'"> </div> <div class="col-1"> <br><span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span> </div> </div> ');
        
    }
	



    $(document).ready(function () {
        $("#campoPesquisa").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#tablePrint tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            }
            );
        }
        );
        $(':input:not(textarea)').on("keydown", function () {
            if (event.key == "Enter") {
                event.preventDefault();
            }
        }
        );
    }
    );
</script>
<!-- cabeçalho da página -->
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fas fa-user-alt icon-gradient bg-happy-itmeo">
                </i>
            </div>
            <div>
                <span>Cadastro de Clientes
                </span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de clientes
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal"
                data-target="#mdl-cliente">
                <i class="fas fa-user-plus">
                </i>
            </button>
            <div class="d-inline-block dropdown">
                <button class="btn-shadow dropdown-toggle btn btn-info" type="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20">
                        </i>
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
                    <h5 class="card-title">Clientes cadastrados
                    </h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">
                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead>
                            <tr>
                                <th style="width:2%">ID
                                </th>
                                <th style="width:4%">Pessoa
                                </th>
                                <th style="width:20%">Nome
                                </th>
                                <th style="width:10%">CNPJ
                                </th>
                                <th style="width:14%">Contato
                                </th>
                                <th style="width:8%">Cidade
                                </th>
                                <th>Observações
                                </th>
                                <th class="noPrint">
                                </th>
                                <th class="noPrint">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$resp = $con->query('select * from tbl_clientes where tipoCliente = "on" and status = 1 order by id desc');
while ($row = $resp->fetch_assoc()) {
echo '
<tr>
<td>' . str_pad($row['id'], 3, "0", STR_PAD_LEFT) . '</td>
<td>' . ($row['tipoPessoa'] == "PF" ? "Física" : "Júridica") . '</td>
<td>' . $row['razaoSocial_nome'] . '</td>
<td>' . $row['cnpj_cpf'] . '</td>
<td>' . $row['telefoneEmpresa'] . '</td>
<td>' . $row['cidade'] . '/' . $row['estado'] . '</td>
<td>' . $row['observacao'] . '</td>
<td class="noPrint"><a href="?edt=' . $row['id'] . '" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
<td class="noPrint"><a href="?del=' . $row['id'] . '" class="btn"><i class="fas fa-trash icon-gradient bg-happy-itmeo"></i></a></td>
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
<?php
include('footer.php');
?>

<!-- modal -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo cliente
                </h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <?php
if (isset($_GET['edt'])) {
$resp = $con->query('select * from tbl_clientes where id = ' . $_GET['edt']);
$row  = $resp->fetch_assoc();
}
?>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true"
                            Style="font-weight: bold !important; color: #000000 !important;">Geral
                        </a>
                    </li>
                    <li id="TabEstrageiroCad" name="TabEstrageiroCad" <?php
          if ($row['estrangeiro'] == 0) {
          echo 'class="nav-item invisible"';
          } else {
          echo 'class="nav-item visible"';
          }
          ?>>
                        <a class="nav-link" id="estrangeiro-tab" data-toggle="tab" href="#estrangeiro_tab" role="tab"
                            aria-controls="estrangeiro_tab" aria-selected="false"
                            Style="font-weight: bold !important; color: #000000 !important;">Estrangeiro
                        </a>
                    </li>

                    <li id="TabDependentesCad" name="TabDependentesCad" <?php
          if ($row['dependentes'] == 0) {
          echo 'class="nav-item invisible"';
          } else {
          echo 'class="nav-item visible"';
          }
          ?>>
                        <a class="nav-link" id="estrangeiro-tab" data-toggle="tab" href="#dependentes_tab" role="tab"
                            aria-controls="dependentes_tab" aria-selected="false"
                            Style="font-weight: bold !important; color: #000000 !important;">Dependentes
                        </a>
                    </li>

                </ul>
                <form method="post">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <input type="hidden" value="<?php
                                        echo isset($_GET['edt']) ? 'edt' : 'add';
                                        ?>" name="cmd">
                            <input type="hidden" value="<?php
                                        echo $_GET['edt'];
                                        ?>" name="id" id="id">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <label for="razaoSocial_nome">Razão social / Nome
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                              echo $row['razaoSocial_nome'];
                                              ?>" class="form-control mb-3 estrangeiroInput" name="razaoSocial_nome"
                                                id="razaoSocial_nome" <?php
                    if ($row['estrangeiro'] == 0) {
                    echo ' required ';
                    } else {
                    echo ' ';
                    }
                    ?>>
                                        </div>
                                        <div class="col-2">
                                            <label for="tipoPessoa">Tipo Pessoa
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <select class="form-control mb-3 estrangeiroInput" name="tipoPessoa"
                                                id="tipoPessoa" <?php
                    if ($row['estrangeiro'] == 0) {
                    echo ' required ';
                    } else {
                    echo ' ';
                    }
                    ?>>
                                                <option value="PF" <?php
                    echo !isset($row['tipoPessoa']) || $row['tipoPessoa'] == "PF" ? "selected" : "";
                    ?>>Fisíca
                                                </option>
                                                <option value="PJ" <?php
                  echo $row['tipoPessoa'] == "PJ" ? "selected" : "";
                  ?>>Jurídica
                                                </option>
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
                                            <label for="estrangeiro">Estrangeiro
                                            </label>
                                            <select class="form-control mb-3" name="estrangeiro" id="estrangeiro"
                                                onchange="est(this)">
                                                <option value="0" <?= $row['estrangeiro'] == 0 ? 'selected' : '' ?>>Não
                                                </option>
                                                <option value="1" <?= $row['estrangeiro'] == 1 ? 'selected' : '' ?>>Sim
                                                </option>
                                                <select>
                                        </div>
                                        <div class="col-3">
                                            <label for="ie">IE
                                            </label>
                                            <input type="text" class="form-control mb-3" name="ie" id="ie">
                                        </div>
                                        <div class="col">
                                            <label for="nomeResponsavel">Nome responsável
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['nomeResponsavel'];
                                        ?>" class="form-control mb-3" name="nomeResponsavel" id="nomeResponsavel">
                                        </div>
                                        <div class="col-3">
                                            <label for="cpfResponsavel">CPF responsável
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['cpfResponsavel'];
                                        ?>" class="form-control mb-3" name="cpfResponsavel" id="cpfResponsavel">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="email">Email
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['email'];
                                        ?>" class="form-control mb-3 estrangeiroInput" name="email" id="email" <?php
              if ($row['estrangeiro'] == 0) {
              echo ' required ';
              } else {
              echo ' ';
              }
              ?>>
                                        </div>
                                        <div class="col">
                                            <label for="telefoneEmpresa">Telefone/Celular
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['telefoneEmpresa'];
                                        ?>" class="form-control mb-3 estrangeiroInput" name="telefoneEmpresa"
                                                id="telefoneEmpresa" <?php
              if ($row['estrangeiro'] == 0) {
              echo ' required ';
              } else {
              echo ' ';
              }
              ?>>
                                        </div>
                                        <div class="col">
                                            <label for="telefoneWhatsapp">Whatsapp
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['telefoneWhatsapp'];
                                        ?>" class="form-control mb-3" name="telefoneWhatsapp" id="telefoneWhatsapp">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="dependentes">Dependentes
                                            </label>
                                            <select class="form-control mb-3" onchange="DependentesSelecionado(this)"
                                                name="dependentes" id="dependentes">
                                                <option value="0" <?= $row['dependentes'] == 0 ? 'selected' : '' ?>>Não
                                                </option>
                                                <option value="1" <?= $row['dependentes'] == 1 ? 'selected' : '' ?>>Sim
                                                </option>
                                                <select>
                                        </div>
                                    </div>
                                    <div class="divider">
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="cep">CEP
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                        echo $row['cep'];
                                        ?>" class="form-control mb-3 estrangeiroInput" name="cep" id="cep" <?php
              if ($row['estrangeiro'] == 0) {
              echo ' required ';
              } else {
              echo ' ';
              }
              ?> onchange="getCidade(this)">
                                        </div>
                                        <div class="col-4">
                                            <label for="estado">Estado
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <select name="estado" value="<?php
                                           echo $row['estado'];
                                           ?>" id="estado" class="form-control mb-3 estarngeiroInput" <?php
              if ($row['estrangeiro'] == 0) {
              echo ' required ';
              } else {
              echo ' ';
              }
              ?>>
                                                <!-- onchange="listarCidades()" -->
                                                <option value="AC">Acre
                                                </option>
                                                <option value="AL">Alagoas
                                                </option>
                                                <option value="AP">Amapá
                                                </option>
                                                <option value="AM">Amazonas
                                                </option>
                                                <option value="BA">Bahia
                                                </option>
                                                <option value="CE">Ceará
                                                </option>
                                                <option value="DF">Distrito Federal
                                                </option>
                                                <option value="ES">Espírito Santo
                                                </option>
                                                <option value="GO">Goiás
                                                </option>
                                                <option value="MA">Maranhão
                                                </option>
                                                <option value="MT">Mato Grosso
                                                </option>
                                                <option value="MS">Mato Grosso do Sul
                                                </option>
                                                <option value="MG">Minas Gerais
                                                </option>
                                                <option value="PA">Pará
                                                </option>
                                                <option value="PB">Paraíba
                                                </option>
                                                <option value="PR">Paraná
                                                </option>
                                                <option value="PE">Pernambuco
                                                </option>
                                                <option value="PI">Piauí
                                                </option>
                                                <option value="RJ">Rio de Janeiro
                                                </option>
                                                <option value="RN">Rio Grande do Norte
                                                </option>
                                                <option value="RS" selected>Rio Grande do Sul
                                                </option>
                                                <option value="RO">Rondônia
                                                </option>
                                                <option value="RR">Roraima
                                                </option>
                                                <option value="SC">Santa Catarina
                                                </option>
                                                <option value="SP">São Paulo
                                                </option>
                                                <option value="SE">Sergipe
                                                </option>
                                                <option value="TO">Tocantins
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="cidade">Cidade
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['cidade'];
                                      ?>" class="form-control mb-3 estrangeiroInput" name="cidade" id="cidade" <?php
            if ($row['estrangeiro'] == 0) {
            echo ' required ';
            } else {
            echo ' ';
            }
            ?>>
                                            <!--<select name="cidade" id="cidade" class="form-control"></select>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="logradouro">Logradouro
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['logradouro'];
                                      ?>" class="form-control mb-3 estrangeiroInput" name="logradouro" id="logradouro" <?php
            if ($row['estrangeiro'] == 0) {
            echo ' required ';
            } else {
            echo ' ';
            }
            ?>>
                                        </div>
                                        <div class="col">
                                            <label for="bairro">Bairro
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['bairro'];
                                      ?>" class="form-control mb-3 estrangeiroInput" name="bairro" id="bairro" <?php
            if ($row['estrangeiro'] == 0) {
            echo ' required ';
            } else {
            echo ' ';
            }
            ?>>
                                        </div>
                                        <div class="col-2">
                                            <label for="numero">Número
                                                <span
                                                    class="ml-2 text-danger estrangeiroLabel <?= $row['estrangeiro'] != 0 ? 'd-none' : '' ?>">*
                                                </span>
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['numero'];
                                      ?>" class="form-control mb-3 estrangeiroInput" name="numero" id="numero" <?php
            if ($row['estrangeiro'] == 0) {
            echo ' required ';
            } else {
            echo ' ';
            }
            ?>>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="complemento">Complemento
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['complemento'];
                                      ?>" class="form-control mb-3" name="complemento" id="complemento">
                                        </div>
                                    </div>
                                    <div class="divider">
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="observacao">Observações
                                            </label>
                                            <textarea name="observacao" id="bservacao" maxlength="400"
                                                class="form-control mb-3" style="resize: none;">
              <?php
echo $row['observacao'];
?>
            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="card">
                                        <div class="row mb-2 mt-4">
                                            <div class="col d-flex">
                                                <div class="custom-control custom-switch m-auto w-75">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="tipo_cliente" name="tipo_cliente" checked>
                                                    <label class="custom-control-label" for="tipo_cliente">Cliente
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="divider">
                                        </div>
                                        <div class="row mb-2 mt-2">
                                            <div class="col d-flex">
                                                <div class="custom-control custom-switch m-auto w-75">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="tipo_fornecedor" name="tipo_fornecedor" <?php
                echo $row['tipoFornecedor'] == "on" ? 'checked' : '';
                ?>>
                                                    <label class="custom-control-label" for="tipo_fornecedor">Fornecedor
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="divider">
                                        </div>
                                        <div class="row mb-2 mt-2">
                                            <div class="col d-flex">
                                                <div class="custom-control custom-switch m-auto w-75">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="tipo_funcionario" name="tipo_funcionario" <?php
                echo $row['tipoFuncionario'] == "on" ? 'checked' : '';
                ?>>
                                                    <label class="custom-control-label"
                                                        for="tipo_funcionario">Funcionário
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="divider">
                                        </div>
                                        <div class="row mb-4 mt-2">
                                            <div class="col d-flex">
                                                <div class="custom-control custom-switch m-auto w-75">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="tipo_tecnico" name="tipo_tecnico" <?php
                echo $row['tipoTecnico'] == "on" ? 'checked' : '';
                ?>>
                                                    <label class="custom-control-label" for="tipo_tecnico">Técnico
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input id="needs-validation" class="d-none" type="submit" value="enviar">
                        </div>
                        <div class="tab-pane fade" id="estrangeiro_tab" role="tabpanel"
                            aria-labelledby="estrangeiro-tab">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="PaisOrigem">Pais de Origem
                                            </label>
                                            <select class="form-control mb-2" name="PaisOrigem" id="PaisOrigem">
                                                <?php
$query_paises = $con->query('select * from tbl_paises order by paisid ');
while ($row_paises = $query_paises->fetch_assoc()) {
if ($row_paises['paisId'] == $row['id_pais_origem']) {
echo '
<option value=' . $row_paises['paisId'] . ' selected >' . $row_paises['paisNome'] . '</option>';
} else {
echo '
<option value=' . $row_paises['paisId'] . ' >' . $row_paises['paisNome'] . '</option>';
}
}
?>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label for="contato_cad">Contato
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['contato_estrangeiro'];
                                      ?>" class="form-control mb-3" name="contato_cad" id="contato_cad">
                                        </div>
                                        <div class="col-5">
                                            <label for="documento_cad">Documento
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['documento_estrangeiro'];
                                      ?>" class="form-control mb-3" name="documento_cad" id="documento_cad">
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="cep_estrangeiro">CEP
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['cep_estrangeiro'];
                                      ?>" class="form-control mb-3" name="cep_estrangeiro"
                                                id="cep_estrangeiro">
                                        </div>
                                        <div class="col-4">
                                            <label for="estado_estrangeiro">Estado
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['estado_estrangeiro'];
                                      ?>" class="form-control mb-3" name="estado_estrangeiro"
                                                id="estado_estrangeiro">
                                        </div>
                                        <div class="col">
                                            <label for="cidade_estrangeiro">Cidade
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['cidade_estrangeiro'];
                                      ?>" class="form-control mb-3 " name="cidade_estrangeiro" id="cidade_estrangeiro">
                                            <!--<select name="cidade" id="cidade" class="form-control"></select>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="logradouro_estrangeiro">Logradouro
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['logradouro_estrangeiro'];
                                      ?>" class="form-control mb-3" name="logradouro_estrangeiro"
                                                id="logradouro_estrangeiro">
                                        </div>
                                        <div class="col">
                                            <label for="bairro_estrangeiro">Bairro
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['bairro_estrangeiro'];
                                      ?>" class="form-control mb-3" name="bairro_estrangeiro"
                                                id="bairro_estrangeiro">
                                        </div>
                                        <div class="col-2">
                                            <label for="numero_estrangeiro">Número
                                            </label>
                                            <input type="text" value="<?php
                                      echo $row['numero_estrangeiro'];
                                      ?>" class="form-control mb-3" name="numero_estrangeiro"
                                                id="numero_estrangeiro">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="dependentes_tab" role="tabpanel"
                            aria-labelledby="dependentes-tab">
                               <?php
										$idClienteAbaDependentes =  $_GET['edt'];
										if ($idClienteAbaDependentes == null){
											$idClienteAbaDependentes = 0;
										}	
										$QtdRegistrosDependentes =0;
										
										$respDependentes = $con->query('select * from tbl_clientes_dependentes where id_cliente = ' .$idClienteAbaDependentes.' order by id_dependente asc');
										while ($rowDependentes = $respDependentes->fetch_assoc()) {
										$QtdRegistrosDependentes = $rowDependentes['id_dependente'];
										echo '
										
												<div class="row">
												
                                				<div class="col-5">
                                    				<label for="nome_dependente'. $rowDependentes['id_dependente'] .'">Nome
                                    				</label>
                                                      <input type="text" value="'. $rowDependentes['nome_dependente'] .'" class="form-control mb-3" name="nome_dependente'. $rowDependentes['id_dependente'] .'"
                                                          id="nome_dependente'. $rowDependentes['id_dependente'] .'">
                                                </div>
								                 <div class="col-3">
                                                 <label for="data_nasc_dependente'. $rowDependentes['id_dependente'] .'">Data Nascimento</label>
                                                  <input type="date" value="'. $rowDependentes['data_nascimento'] .'" class="form-control mb-3" name="data_nasc_dependente'. $rowDependentes['id_dependente'] .'"
                                                     onchange="CalculaDataMaioridade(this,'. $rowDependentes['id_dependente'] .')"           id="data_nasc_dependente'. $rowDependentes['id_dependente'] .'"> 
												 </div>
								                 <div class="col-3">
                                                 <label for="data_maioridade_dependente'. $rowDependentes['id_dependente'] .'">Data Maioridade</label>
                                                 <input type="date" value="'. $rowDependentes['data_maioridade'] .'" class="form-control mb-3" name="data_maioridade_dependente'. $rowDependentes['id_dependente'] .'"
                                                   id="data_maioridade_dependente'. $rowDependentes['id_dependente'] .'">
                                                 </div>
												 <div class="col-1">
												   <br>
													<span id="excluir'. $rowDependentes['id_dependente'] .'" name="excluir'. $rowDependentes['id_dependente'] .'" onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
												 
												 </div>
												 
												 
                                                 </div> ';
										
										}
										
										if ($QtdRegistrosDependentes == 0 ){
										$QtdRegistrosDependentes = $QtdRegistrosDependentes +1;
										echo '
										
												<div class="row">
                                				<div class="col-5">
                                    				<label for="nome_dependente'. $QtdRegistrosDependentes .'">Nome
                                    				</label>
                                                      <input type="text" value="" class="form-control mb-3" name="nome_dependente'. $QtdRegistrosDependentes .'"
                                                          id="nome_dependente'. $QtdRegistrosDependentes .'">
                                                </div>
								                 <div class="col-3">
                                                 <label for="data_nasc_dependente'. $QtdRegistrosDependentes .'">Data Nascimento</label>
                                                  <input type="date" value="" class="form-control mb-3" name="data_nasc_dependente'. $QtdRegistrosDependentes .'"
                                                      onchange="CalculaDataMaioridade(this,'. $QtdRegistrosDependentes.')"          id="data_nasc_dependente'. $QtdRegistrosDependentes .'"> 
												 </div>
								                 <div class="col-3">
                                                 <label for="data_maioridade_dependente'. $QtdRegistrosDependentes .'">Data Maioridade</label>
                                                 <input type="date" value="" class="form-control mb-3" name="data_maioridade_dependente'. $QtdRegistrosDependentes .'"
                                                   id="data_maioridade_dependente'. $QtdRegistrosDependentes .'">
                                                 </div>
												 <div class="col-1">
												   <br>
												   <span id="excluir'. $QtdRegistrosDependentes.'" name="excluir'. $QtdRegistrosDependentes.'" onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
												 </div>
                                                 </div> ';

										
										}
										
                                ?>
							
							<div  id="linhaDependentes">
                        
							</div>
							<input type="hidden" id="QtdDependentesTela" name="QtdDependentesTela" value="<?php echo $QtdRegistrosDependentes; ?>">
							<button type="button" class="btn btn-primary" onclick="InserirNovoDependentes();">Novo</button>
                        </div>



                    </div>
                </form>
                <script>
                    $(document).ready(function () {
                        $("#cpfResponsavel").mask('000.000.000-00', {
                            reverse: true
                        }
                        );
                        $("#telefoneEmpresa").mask('(99) 99999-9999');
                        $("#telefoneWhatsapp").mask('(99) 99999-9999');
                        $("#ie").mask('99999999-99');
                        $("#cep").mask('99999-999');
                    }
                    );
                </script>
            </div>
            <div class="modal-footer">
                <p class="text-start">
                    <span class="ml-2 text-danger">*
                    </span> Campos obrigatórios
                </p>
                <button type="button" class="btn btn-secondary" onclick="location.href='?'">Cancelar
                </button>
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('needs-validation').click();">
                    <?php
echo isset($_GET['edt']) ? 'Atualizar' : 'Salvar';
?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- fim modal -->
<div id="toast-container" class="toast-top-center">
    <div id="toast-success" class="toast toast-success" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Sucesso!
        </div>
    </div>
    <div id="toast-error" class="toast toast-error hidden" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Erro!
        </div>
    </div>
    <?php
if (isset($_GET['s']))
echo "<script>loadToast(true);</script>";
else if (isset($_GET['e']))
echo "<script>loadToast(false);</script>";
?>
</div>
<?php
if (isset($_GET['edt']))
echo "<script>$('#btn-modal').click()</script>";
?>