<?php include ('header.php'); ?>

<?php
if (isset($_POST['cmd']))
{
    $cmd = $_POST['cmd'];

    if ($cmd == "add")
    {
        $query = 'insert into tbl_ordemServico(cliente,descricao,solicitacao,prevEntrega,status) values(
            ' . $_POST['cliente'] . ',
            "' . $_POST['descricao'] . '",
            "' . $_POST['solicitacao'] . '",
            "' . $_POST['previsao'] . '",
            ' . $_POST['status'] . '
        )';
        $con->query($query);
        redirect($con->error);
    }
    elseif ($cmd == "edt")
    {
        $query = 'update tbl_ordemServico set
            cliente = ' . $_POST['cliente'] . ',
            descricao = "' . $_POST['descricao'] . '",
            solicitacao = "' . $_POST['solicitacao'] . '",
            prevEntrega = "' . $_POST['previsao'] . '",
            status = ' . $_POST['status'] . '
            where id = ' . $_POST['id'] . '
        ';
        $con->query($query);
        redirect($con->error);
    }
}
elseif (isset($_GET['del']))
{
    $con->query('update tbl_ordemServico set status = -1 where id = ' . $_GET['del']);
    redirect($con->error);
}

?>

<script>
$(document).on("click", "#submit_btn", function (e) {
    //Prevent Instant Click 

    rvlr_euro = $('#vlr_euro').val();
    rvlr_euro = rvlr_euro.replace("€", "");	
	rvlr_euro = rvlr_euro.replace(",", "."); 
	if (rvlr_euro == ''){
		rvlr_euro =0;
	}
	if ($("#cliente").val() == null){
		
	$("#cliente").effect( "shake" );
	
	 const Toast = Swal.mixin({
	  toast: true,
	  position: 'top-end',
	  showConfirmButton: false,
	  timer: 3000,
	  timerProgressBar: true,
	  didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	  }
	})

	Toast.fire({
	  icon: 'error',
	  title: 'Selecione um cliente para salvar!'
	})
	  return false;
	}
	
	if (rvlr_euro == 0){
		
	$("#vlr_euro").effect( "shake" );
	
	 const Toast = Swal.mixin({
	  toast: true,
	  position: 'top-end',
	  showConfirmButton: false,
	  timer: 3000,
	  timerProgressBar: true,
	  didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	  }
	})

	Toast.fire({
	  icon: 'error',
	  title: 'Informe o valor em euro!'
	})
	  return false;
	}
	
	
    e.preventDefault();
    // Create an FormData object 
    var formData = $("#form").submit(function (e) {
        return;
    });
    //formData[0] contain form data only 
    // You can directly make object via using form id but it require all ajax operation inside $("form").submit(<!-- Ajax Here   -->)
    var formData = new FormData(formData[0]);
    $.ajax({
        url: $('#form').attr('action'),
        type: 'POST',
        data: formData,
        success: function (response) {
            console.log(response);
        },
        contentType: false,
        processData: false,
        cache: false
    });
    return false;
});
</script>

<script>


   async function imprimir(){
        const divPrint = document.getElementById('tablePrint');
        newWin = window.open('');
        newWin.document.write('<link href="./main.css" rel="stylesheet">');
        newWin.document.write('<link href="./assets/css/print.css" rel="stylesheet">');
        newWin.document.write('<button class="btn m-2 bg-primary noPrint" onclick="window.print();window.close()"><i class="fa fa-print text-white"></i></button><br><br><h5 class="mb-3">Clientes de Recebimentos</h5>');
        newWin.document.write(divPrint.outerHTML);
        //await new Promise(r => setTimeout(r, 150));
        //newWin.print();
        //newWin.close();
    }
	
	
	
	function HabilitarCamposParcelasGrid(){
		iparcelamento = $('#parcelamento').val(); 
		if (iparcelamento == 0) {
			LimpaParcelasDoGrid();
			$("#vlr_entrada").val("R$ 0,00");
			$("#vlr_entrada").prop( "disabled", true );
			$("#condicoes_parc").prop( "disabled", true );
			$("#condicoes_parc").val("00").change();
            $('#tipoValor').prop('disabled',true);
			
		} else{
			$( "#vlr_entrada" ).prop( "disabled", false );
			$( "#condicoes_parc" ).prop( "disabled", false );
            $('#tipoValor').prop('disabled',false);	
			
		}
	}
	
	function LimpaParcelasDoGrid(){
		$("#parcelas_listadas_grid_1").remove(); 
		$("#parcelas_listadas_grid_2").remove(); 
		$("#parcelas_listadas_grid_3").remove(); 
		$("#parcelas_listadas_grid_4").remove(); 
		$("#parcelas_listadas_grid_5").remove(); 
		$("#parcelas_listadas_grid_6").remove(); 
		$("#parcelas_listadas_grid_7").remove(); 
		$("#parcelas_listadas_grid_8").remove(); 
		$("#parcelas_listadas_grid_9").remove(); 
		$("#parcelas_listadas_grid_10").remove(); 
		$("#parcelas_listadas_grid_11").remove(); 
		$("#parcelas_listadas_grid_12").remove(); 		
	}
	
	function CalcularEuroParaReal(){
        rvlr_euro = $('#vlr_euro').val();
        
        rvlr_euro = rvlr_euro.replace("€", "");	
        rvlr_euro = rvlr_euro.replace(",", ".");	
        rcotacoa_vlr_euro = $('#cotacoa_vlr_euro').val();
        rcotacoa_vlr_euro = rcotacoa_vlr_euro.replace("R$", "");	
        rcotacoa_vlr_euro = rcotacoa_vlr_euro.replace(",", ".");

        // checa se o total será em real ou euro
        rValorReal = $("#moeda").val()==1?parseFloat(rvlr_euro):parseFloat(rvlr_euro) * parseFloat(rcotacoa_vlr_euro);

        if($("#moeda").val()==2){
            console.log($('.simboloEuro'));
            $('.simboloEuro').hide();
            $('.simboloReal').show();
        }
        else{
            console.log($('.simboloEuro'));
            $('.simboloEuro').show();
            $('.simboloReal').hide();
        }


        var n = rValorReal.toFixed(2);
        //alert(n);
        //rValorReal = rValorReal.toFixedDown(2);
        n = n.toString();
        n = n.replace(".", ",");

        //document.getElementById('vlr_real').value = rValorReal; 	
        $("#vlr_real").val("R$ "+n);
        //alert(rValorReal);
        
        if($('#parcelamento').val() == 1){
            CalcularParcelas();
        }
    }	
        
        
    function CalcularParcelas(){
        rvlr_real = $('#moeda').val()==1?$('#vlr_real').val():$('#vlr_euro').val();
        rvlr_real = rvlr_real.replace("R$", "");	
        rvlr_real = rvlr_real.replace(",", ".");		
        
        rvlr_entrada = $('#vlr_entrada').val();
        rvlr_entrada = rvlr_entrada.replace("R$", "");	
        rvlr_entrada = rvlr_entrada.replace(",", ".");
        
        // calcula parcelas por valor
        if($('#tipoValor').val() == 1){
            var rValorMenosEntrada = (rvlr_real-rvlr_entrada);
            if (rValorMenosEntrada < 1) {
                
                $("#vlr_entrada").effect( "shake" );
            
                    const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

                Toast.fire({
                icon: 'error',
                title: 'Entrada não pode ser maior que o valor!'
                })
                LimpaParcelasDoGrid();
                return false;
            
                
            }	
            rcondicoes_parc = $('#condicoes_parc').val();
            rValorPorParcela = (rvlr_real-rvlr_entrada) / rcondicoes_parc;
            var n = rValorPorParcela.toFixed(2);
            var TotalDasCondicoes = (n * rcondicoes_parc);
            TotalDasCondicoes = TotalDasCondicoes.toFixed(2);
            //alert(TotalDasCondicoes);
            var DiferencaQueSobrouNasParcelas = ((rvlr_real-rvlr_entrada) - TotalDasCondicoes);
            DiferencaQueSobrouNasParcelas = DiferencaQueSobrouNasParcelas.toFixed(2);
            //alert(DiferencaQueSobrouNasParcelas);
        //	var PrimeiraParcela = (n  (DiferencaQueSobrouNasParcelas));
            
            if (DiferencaQueSobrouNasParcelas <= 0){
                
                DiferencaQueSobrouNasParcelas = Math.abs(DiferencaQueSobrouNasParcelas) ;
                DiferencaQueSobrouNasParcelas = (n  - DiferencaQueSobrouNasParcelas);
                var Novo = DiferencaQueSobrouNasParcelas.toFixed(2);
                //alert(Novo);
            } else{
                
                if (DiferencaQueSobrouNasParcelas >0 ){
                    //  alert(n);
                DiferencaQueSobrouNasParcelas = (parseFloat(n)  + parseFloat(DiferencaQueSobrouNasParcelas));
            //alert(DiferencaQueSobrouNasParcelas);
                        
                var Novo = DiferencaQueSobrouNasParcelas.toFixed(2);
                // alert(Novo);
                }
            }

            $('#vlr_total').val(rvlr_entrada);
        }
        /* -- calcula parcelas por porcentagem -- */
        else{
            rcondicoes_parc = $('#condicoes_parc').val();

            sobraPorcentagem = 100 - rvlr_entrada;

            $('#vlr_total').val((rvlr_real/100)*rvlr_entrada);
            
            rValorPorParcela = ((rvlr_real/100)*sobraPorcentagem) / rcondicoes_parc;

            
            n = rValorPorParcela.toFixed(2);
            Novo = n;
        }
        
        //alert(Novo);
        Novo = Novo.toString();
        Novo = Novo.replace(".", ",");
        
        
        
        //alert(n);
        //rValorReal = rValorReal.toFixedDown(2);
        n = n.toString();
        n = n.replace(".", ",");
        rValorPorParcela = n;

        if($('#tipoValor').val() == 1){
            restaPorcentagem =  100-(rvlr_entrada/(rvlr_real/100));
        }
        else{
            restaPorcentagem = 100-rvlr_entrada;
        }
        porcentagem = rvlr_real/100;
        valorPorcentagemParcela = restaPorcentagem / rcondicoes_parc;
        
        
        LimpaParcelasDoGrid(); 
        
        for (var i = 0; i < rcondicoes_parc; i++) {
            if (i == 0) { 
                //DiferencaQueSobrouNasParcelas = 0;
                $('#linhaProdutos').append(`
                    <div name="parcelas_listadas_grid_`+(i+1)+`" id="parcelas_listadas_grid_`+(i+1)+`" >
                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" name="`+(i+1)+`" value="`+(i+1)+`">
                                <input type="text" class="form-control" name="`+(i+1)+`_parc" value="Parcela `+(i+1)+`" readonly>
                            </div>
                            <div class="col d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-percentage"></i>
                                <input type="number" name="procentagem[]" value="`+valorPorcentagemParcela+`" placeholder="%" class="form-control" onchange="calculaValor(this,1,`+porcentagem+`)">
                            </div>
                            <div class="col-2 d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-euro-sign simboloEuro" style="display:none"></i>
                                <i class="mb-auto mt-auto mr-2 fas fa-dollar-sign simboloReal"></i>
                                <input class="form-control" name="valor_parc[]" value="`+Novo+`" type="number" step="0.01" onchange="calculaValor(this,2,`+porcentagem+`)">
                            </div>
                            <div class="col-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="`+(i+1)+`_pago" id="`+(i+1)+`_pago" >
                                    <label class="form-check-label" for="`+(i+1)+`_pago" >Pago</label> 
                                </div>  
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" id="`+(i+1)+`_data"  name="`+(i+1)+`_data" value="5">
                            </div>  
                            <div class="col-0"> 
                                <span onclick="$(this).parent().parent().remove()"   class="btn text-success">
                                    <i class="fas fa-download"></i>
                                </span>  
                            </div>  
                            <div class="col-3"> 
                                <input  class="form-control-file"  type="file" name="`+(i+1)+`_file" id="`+(i+1)+`_file" >
                            </div>
                        </div>
                    </div>
                `);
            } else{
                $('#linhaProdutos').append(`
                    <div name="parcelas_listadas_grid_`+(i+1)+`" id="parcelas_listadas_grid_`+(i+1)+`" > 
                        <div class="row mb-2">
                            <div class="col"> 
                                <input type="hidden" name="`+(i+1)+`" value="`+(i+1)+`"> 
                                <input type="text" class="form-control" name="`+(i+1)+`_parc" value="Parcela `+(i+1)+`" readonly>
                            </div>
                            <div class="col d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-percentage"></i>
                                <input type="number" name="procentagem[]" value="`+valorPorcentagemParcela+`" placeholder="%" class="form-control" onchange="calculaValor(this,1,`+porcentagem+`)">
                            </div>
                            <div class="col-2 d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-euro-sign simboloEuro" style="display:none"></i>
                                <i class="mb-auto mt-auto mr-2 fas fa-dollar-sign simboloReal"></i>
                                <input class="form-control" name="valor_parc[]" value="`+rValorPorParcela+`" type="number" step="0.01" onchange="calculaValor(this,2,`+porcentagem+`)">
                            </div>
                            <div class="col-0"> 
                                <div class="form-check"> 
                                    <input class="form-check-input" type="checkbox" value="" name="`+(i+1)+`_pago" id="`+(i+1)+`_pago" > 
                                    <label class="form-check-label" for="`+(i+1)+`_pago" >Pago</label> 
                                </div>  
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" id="`+(i+1)+`_data"  name="`+(i+1)+`_data" value="5">
                            </div>  
                            <div class="col-0"> 
                                <span onclick="$(this).parent().parent().remove()"   class="btn text-success">
                                    <i class="fas fa-download"></i>
                                </span>  
                            </div>  
                            <div class="col-3"> 
                                <input  class="form-control-file"  type="file" name="`+(i+1)+`_file" id="`+(i+1)+`_file" >
                            </div>
                        </div>
                    </div>
                `);
            
            
            }
            
        }
    }	
	
    function myFunction() {
	 
        // swal("Cadastro incompleto!", "Selecione o cliente para gravar!", "warning");
        //alert('teste');
        
        $("#cliente").effect( "shake" );
        
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
            icon: 'error',
            title: 'Selecione um cliente para salvar!'
        })

	 
    }

    function calculaValor(input,tipo,porcentagem){
        if(tipo == 1){
            alvo = $(input).parent().parent().find('input[name="valor_parc[]"]');
            $(alvo).val($(input).val()*porcentagem);
        }
        else{
            alvo = $(input).parent().parent().find('input[name="procentagem[]"]');
            $(alvo).val($(input).val()/porcentagem);
        }
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
                <i class="fas fa-newspaper icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de Recebimentos</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de recebimentos
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=ordemServico">
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

                    <h5 class="card-title">Recebimentos</h5>
                    <input type="text" class="mb-2 form-control w-25" placeholder="Pesquisar" id="campoPesquisa">

                    <table class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th style="width:26%">Nome</th>
                                <th>Descrição</th>
                                <th style="width:14%">Data de solicitação</th>
                                <th style="width:14%">Previsão de entrega</th>
                                <th style="width:6%">status</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$resp = $con->query('select * from tbl_ordemServico where status > 0');

while ($row = $resp->fetch_assoc())
{
    $nome = $con->query('select razaoSocial_nome from tbl_clientes where id = ' . $row['cliente'] . ' and status = 1')->fetch_assoc() ['razaoSocial_nome'];
    $status = '';
    $corStatus = '';
    switch ($row['status'])
    {
        case 1:
            $status = 'Aguardando';
            $corStatus = 'focus';
        break;
        case 2:
            $status = 'Em andamento';
            $corStatus = 'info';
        break;
        case 3:
            $status = 'Aguardando aprovação';
            $corStatus = 'warning';
        break;
        case 4:
            $status = 'Finalizado';
            $corStatus = 'success';
        break;
    }

    echo '
                                        <tr>
                                            <td>' . str_pad($row['id'], 3, "0", STR_PAD_LEFT) . '</td>
                                            <td>' . $nome . '</td>
                                            <td>' . $row['descricao'] . '</td>
                                            <td>' . date('d / m / Y', strtotime($row['solicitacao'])) . '</td>
                                            <td>' . date('d / m / Y', strtotime($row['prevEntrega'])) . '</td>
                                            <td><div class="badge badge-' . $corStatus . ' p-2">' . $status . '</div></td>
                                            <td class="noPrint text-center"><a href="?edt=' . $row['id'] . '" class="btn"><i class="fas fa-user-edit icon-gradient bg-happy-itmeo"></i></a></td>
                                            <td class="noPrint text-center"><a href="?del=' . $row['id'] . '" class="btn"><i class="fas fa-trash icon-gradient bg-happy-itmeo"></i></a></td>
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

<?php include ('footer.php'); ?>

<!-- modal -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo recebimento</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="form" method="post" action="cad-grava-recebimento.php" enctype="multipart/form-data" >

                    <?php
                        if (isset($_GET['edt']))
                        {
                            $resp = $con->query('select * from tbl_ordemServico where id = ' . $_GET['edt']);
                            $ordemServico = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="<?php echo isset($_GET['edt']) ? 'edt' : 'add'; ?>" name="cmd">
                    <input type="hidden" value="<?php echo $_GET['edt']; ?>" name="id" id="id">

                    <div class="row mb-3">
                        <div class="col">
                            <label for="cliente">Cliente<span class="ml-2 text-danger">*</span></label>
                            <select class="form-control select2modal" name="cliente" id="cliente" required>
                                <option <?php echo isset($_GET['edt']) ? '' : 'selected'; ?> disabled>Selecione o cliente</option>
                                <?php
                                    $resp = $con->query('select id, razaoSocial_nome from tbl_clientes where tipoCliente="on"');
                                    while ($row = $resp->fetch_assoc())
                                    {
                                        $selected = $ordemServico['cliente'] == $row['id'] ? 'selected' : '';
                                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['razaoSocial_nome'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <label for="cotacoa_vlr_euro">Cotação Euro €</label>
                             <input type="text"  value="<?php echo $EuroCotacao;?>" onchange="CalcularEuroParaReal()" class="form-control mb-3 estrangeiroInput" name="cotacoa_vlr_euro" id="cotacoa_vlr_euro" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                        </div>

                        <div class="col-3">
                            <label for="moeda">Moeda</label>
                            <select name="moeda" id="moeda" class="form-control" onclick="CalcularEuroParaReal()">
                                <option value="1">Real</option>
                                <option value="2" selected>Euro</option>
                            </select>
                        </div>
						
						<div class="col-3">
                            <label for="vlr_euro">Valor<span class="ml-2 text-danger">*</span></label>
                             <input type="text" onchange="CalcularEuroParaReal()" value="<?php echo isset($row['logradouro'])?$row['logradouro']:0; ?>" class="form-control mb-3 estrangeiroInput" name="vlr_euro" id="vlr_euro" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
                        </div>
						<div class="col-3 simboloReal">
                            <label for="vlr_real">Valor Real</label>
                             <input type="text" disabled value="R$ 0,00" class="form-control mb-3 estrangeiroInput" name="vlr_real" id="vlr_real" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
                        </div>
                        
                        
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <label for="parcelamento">Parecelamento</label>
                            <select class="form-control mb-3" name="parcelamento" id="parcelamento" onchange="HabilitarCamposParcelasGrid()">
                                <option value="0" selected >Não</option>
                                <option value="1" >Sim</option>
                            <select>                       		
                        </div>

                        <div class="col-2">
                            <label for="tipoValor">Tipo</label>
                            <select name="tipoValor" id="tipoValor" class="form-control" onchange="CalcularParcelas()" disabled>
                                <option value="1">Valor</option>
                                <option value="2">%</option>
                            </select>
                        </div>
									
						<div class="col-3">
                            <label for="vlr_entrada">Entrada</label>
                             <input type="text"  value="0,00" disabled="true"  onchange="CalcularParcelas()" class="form-control mb-3 estrangeiroInput" name="vlr_entrada" id="vlr_entrada" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
                        </div>

                        <div class="col">
                            <label for="vlr_total">Valor Total</label>
                             <input type="text"  value="0,00"  class="form-control mb-3" name="vlr_total" id="vlr_total" readonly>
                             
                        </div>

                    </div>
                    <div class="row">	
						
						<div class="col-4">
                            <label for="condicoes_parc">Condições</label>
                            <select name="condicoes_parc" disabled="true"  onchange="CalcularParcelas()" value="00" id="condicoes_parc" class="form-control mb-2 estarngeiroInput" > <!-- onchange="listarCidades()" -->
                                         <option value="00" >Selecione</option>
										<option value="01" >Entrada + 1 Parcela</option>
                                        <option value="02">Entrada + 2 Parcelas</option>
                                        <option value="03">Entrada + 3 Parcelas</option>
                                        <option value="04">Entrada + 4 Parcelas</option>
										<option value="05">Entrada + 5 Parcelas</option>
										<option value="06">Entrada + 6 Parcelas</option>
										<option value="07">Entrada + 7 Parcelas</option>
										<option value="08">Entrada + 8 Parcelas</option>
										<option value="09">Entrada + 9 Parcelas</option>
										<option value="10">Entrada + 10 Parcelas</option>
										<option value="11">Entrada + 11 Parcelas</option>
                                        <option value="12">Entrada + 12 Parcelas</option>
                            </select>
						
							
                        </div>
						
						
						
			
									
                    </div>
					
					<div class="divider mb-3"></div>

                    <div class="row">
                        <div class="col" id="linhaProdutos">
                            <?
                                $resp = $con->query('select * from tbl_remessaItem where remessa = '.$_GET['edt']);
                                if($resp){
                                    while($row = $resp->fetch_assoc()){
                                        $produto = $con->query('select nome from tbl_produtos where id = '.$row['produto'])->fetch_assoc();
                                        echo '
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <input type="hidden" name="id[]" value="'.$row['produto'].'">
                                                    <input type="text" class="form-control" name="produto[]" value="'.$produto['nome'].'" readonly>
                                                </div>
                                                <div class="col-3">
                                                    <input type="number" class="form-control" name="qtd[]" value="'.$row['quantia'].'">
                                                </div>
                                                <div class="col-1">
                                                    <span onclick="$(this).parent().parent().remove()" class="btn text-danger"><i class="fas fa-trash-alt"></i></span>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            ?>
                        </div>
                    </div> 

                    <input id="needs-validation" class="d-none"  type="submit" value="enviar">
					
                </form>
                <script>
                    $(document).ready(function(){
                        $("#cpfResponsavel").mask('000.000.000-00', {reverse: true});
                        $("#telefoneEmpresa").mask('(99) 99999-9999');
                        $("#telefoneWhatsapp").mask('(99) 99999-9999');
                        $("#cep").mask('99999-999');
						$("#vlr_euro").mask('9999,99');
						//$("#vlr_real").mask('R$ 9999,99');
						$("#vlr_entrada").mask('9999,99');
						$("#cotacoa_vlr_euro").mask('R$ 9999,99');
						
						
						
						
						
                    });
					
					
				
                </script>
				
				<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
				<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

            </div>
            <div class="modal-footer">
                <p class="text-start"><span class="ml-2 text-danger">*</span> Campos obrigatórios</p>
                <button type="button" class="btn btn-secondary" onclick="location.href='?'">Cancelar</button>
                <button type="button" class="btn btn-primary" id='submit_btn'><?php echo isset($_GET['edt']) ? 'Atualizar' : 'Salvar'; ?></button>
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
if (isset($_GET['s'])) echo "<script>loadToast(true);</script>";
else if (isset($_GET['e'])) echo "<script>loadToast(false);</script>";
?>
</div>
 
<?php if (isset($_GET['edt'])) echo "<script>$('#btn-modal').click()</script>"; ?>
