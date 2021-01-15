<?php     include ('header.php');
          require 'bancoPDO.php';        
 ?>

<?php
  




if (isset($_POST['cmd']))
{
    $cmd = $_POST['cmd'];

    if ($cmd == "add")
    {     
          
            
            if($_POST['parcelamento'] == 0){

               echo '<div class = "alert alert-warning">Escolha pelo menos um opção de parcelamento</div>';exit();

            }




          $valorTotal = '';  
          $id_c = ''; 
          if(isset($_POST['1cliente']) && !empty($_POST['1cliente'])){
            $id_c = $_POST['1cliente'];
                    
              
          }else{ 
             
                  $id_c = $_POST['cliente'];
                  
             }          
                          
              if(strpos($_POST['vlr_total'], '€')){

                 $valorTotal = str_replace('€', '',$_POST['vlr_total']);

                  
              }else{
                $$valorTotal = str_replace('R$', '',$_POST['vlr_total']);

                 
              }

                  
                  
               
                
          
         
        $con->autocommit(false);
        
        $qError = false;   
         
         if($_POST['moeda'] == "1"){
                 //$cotacao = str_replace('R$ ','',str_replace(',','.',$_POST['cotacoa_vlr_euro']));
             $vlr_real = $_POST['vlr_euro'];

             //print_r($vlr_real);exit();
         }else{
               


             $cotacao = str_replace('R$ ','',str_replace(',','.',$_POST['cotacoa_vlr_euro']));
             $vlr_real = $_POST['vlr_euro']*$cotacao;
             //print_r($vlr_real);exit();
         }

        
         if(!empty($id_c)){        
   
        
        $con->query('INSERT INTO `tbl_recebimentos`(`id_cliente`, `cotacao_euro`, `data_cotacao_euro`, `id_moeda`, `valor_recebimento`, `valor_real`, `parcelamento`, `valor_entrada`, `valor_total`, `tipoEntrada`,`id_servico`) VALUES (
            "'.$id_c.'",
            "'.$cotacao.'",
            "'.date('Y-m-d').'",
            "'.($_POST['moeda']).'",
            "'.($_POST['moeda']=="2"?str_replace(',','.',$_POST['vlr_euro']):$vlr_real).'",
            "'.($vlr_real).'",
            "'.$_POST['condicoes_parc'].'",
            "'.$_POST['vlr_entrada'].'",
            "'.($valorTotal).'",
            "'.$_POST['tipoValor'].'",
            "'.$_POST['servicos'].'"
        )');
        if($con->error != ""){
            $qError = true;
            var_dump($con->error);
        }

        $last_id = $con->insert_id;
              
            

        for($i = 0; $i < $_POST['condicoes_parc']; $i++){
            $con->query('INSERT INTO `tbl_parcelas_recebimentos`(`id_recebimento`, `des_parcela`, `valor_parcela`, `valor_pago_parcela`, `ind_pago`, `caminho_arquivo_comprovante`, `nome_arquivo_comprovante`,`data_parcela`,`id_moeda`) VALUES (
                "'.$last_id.'",
                "0",
                "'.$_POST['procentagem'][$i].'",
                "0",
                "0",
                "",
                "",
                "'.$_POST['data'][$i].'",
                "'.($_POST['moeda']).'"
            )');


             

            if($con->error != ""){
                $qError = true;
                var_dump($con->error);
            }
        }   

    }else{
             
         echo "<script>alert('o campo cliente não pode estar vazio,você será redirecionado a tela principal')</script>";
       echo "<script>location.href='index.php'</script>";  
    }

        if(!$qError){
            $con->commit();
            echo "<script>location.href='cad-novo-recebimento.php?s'</script>";
        }
        else{
            $con->rollback();
        }
    }
    elseif ($cmd == "edt")
    {
        if($_FILES['arquivo']['name'] != ""){
            $uploaddir = 'upload/';
            $uploadfile = $uploaddir.basename($_FILES['arquivo']['name']);
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile);
        }
        else{
            $uploadfile = "";
        }
             
           

        $con->query('update tbl_parcelas_recebimentos set valor_parcela = "'.$_POST['valor_parc'].'", ind_pago = "'.($_POST['ind_pago']=='on'?1:0).'", data_parcela = "'.$_POST['data'].'", caminho_arquivo_comprovante = "'.$uploadfile.'", valor_pago_parcela="'.$_POST['valor_parc'].'" where id_parcela = '.$_POST['id']);
  


        redirect($con->error);
    }
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
	
	
    /*e.preventDefault();
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
    return false;*/
});
</script>

<script>
    $('#cliente').select2({
        theme: "bootstrap",
        enabled: true,
        dropdownParent: $("#mdl-cliente")
    });

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
                console.log('euro')

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
     var valor_tot = 0;


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
                   
                        
           if($("#moeda").val()==2){
              
          $('#vlr_total').val(rValorMenosEntrada.toLocaleString('de-DE',{style:'currency',currency:'EUR'}));
             
        }
        else{
           $('#vlr_total').val(rValorMenosEntrada.toLocaleString('pt-BR',{style:'currency',currency:'BRL'}));
                       
          
        }



          
         // restaValor =  valor_tot - rvlr_entrada ;           
            
         // valorPorcentagemParcela = rValorMenosEntrada / rcondicoes_parc;








       // console.log('linha 406 ->' + valorPorcentagemParcela)


















            



        }
        /* -- calcula parcelas por porcentagem -- */
        else{
               
               
            rcondicoes_parc = $('#condicoes_parc').val();
             valor_r = $("#vlr_euro").val();
             valor_tot = parseFloat(valor_r);
                sobraP  =  (valor_tot/100) * rvlr_entrada;
                sobraPorcentagem =  valor_tot - sobraP;
                
             
             if(isNaN(sobraPorcentagem)){
                    
             $('#vlr_total').val((rvlr_real/100)*rvlr_entrada);

             }  else{  
                
                if($("#moeda").val()==2){
              
          $('#vlr_total').val(sobraPorcentagem.toLocaleString('de-DE',{style:'currency',currency:'EUR'}));
             










        }
        else{
           $('#vlr_total').val(sobraPorcentagem.toLocaleString('pt-BR',{style:'currency',currency:'BRL'}));         
                  
        }

                 
             }

                        
            rValorPorParcela = sobraPorcentagem / rcondicoes_parc;

             
                   
            Novo = rValorPorParcela.toFixed(2);

             
             
        }
        
        
        n = Novo.toString();    
               
          
        n = n.toString();
        n = n.replace(".", ",");
        rValorPorParcela = n;
   
              
               
        if($('#tipoValor').val() == 1){

            restaValor =  rvlr_real - rvlr_entrada ;           
            
            valorVParcela = restaValor / rcondicoes_parc;   
            novo = valorVParcela.toFixed(2)
             n = novo.toString();
             valorPorcentagemParcela = n.replace(".",",");   
         
                  

        }else{

              

            restaPorcentagem = (valor_tot/100) * rvlr_entrada;
            result_p = valor_tot - restaPorcentagem;          
            valorVParcela = result_p / rcondicoes_parc;
             novo = valorVParcela.toFixed(2)
             n = novo.toString();
             valorPorcentagemParcela = n.replace(".",",");  

             console.log('pequei valor das parcelas 484 ->'+valorPorcentagemParcela)

        }
             

        porcentagem = rvlr_real/100;
        

        
          
        
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

                            
                                <input type="hidden" name="procentagem[]" value="`+valorPorcentagemParcela+`" placeholder="%" class="form-control" onchange="calculaValor(this,1,`+porcentagem+`)">
                             
                            
                            <div class="col-2 d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-euro-sign simboloEuro" style="display:none"></i>
                                <i class="mb-auto mt-auto mr-2 fas fa-dollar-sign simboloReal"></i>
                                <input class="form-control" name="valor_parc[]"   type="number" step="0.01" onchange="calculaValor(this,2,`+porcentagem+`)" value="`+valorPorcentagemParcela+`" placeholder="`+valorPorcentagemParcela+`" disabled="disabled"  >  
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" id="`+(i+1)+`_data"  name="data[] " value="5">
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


                             
                                <input type="hidden" name="procentagem[]" value="`+valorPorcentagemParcela+`" placeholder="%" class="form-control" onchange="calculaValor(this,1,`+porcentagem+`)">
                             
                             
                            <div class="col-2 d-flex">
                                <i class="mb-auto mt-auto mr-2 fas fa-euro-sign simboloEuro" style="display:none"></i>
                                <i class="mb-auto mt-auto mr-2 fas fa-dollar-sign simboloReal"></i>
                                <input class="form-control" name="valor_parc[]"  type="number" step="0.01" onchange="calculaValor(this,2,`+porcentagem+`)" placeholder="`+valorPorcentagemParcela+`" disabled="disabled" value="`+valorPorcentagemParcela+`">
                            </div>
                            <div class="col-2">
                                <input type="date" class="form-control" id="data[]"  name="data[] "  >
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

                    <h5 class="card-title">Recebimentos </h5>
                        
                     <div class="alert alert-warning nao_encontrado"><center>Nenhum resultado encontrado</center></div>      
                         <table style="display: none;" class="table table-hover tabela_result">
                        <thead  >
                            
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Cliente</th>
                             
                            </tr>
                        </thead>
                        <tbody id="body_busca_1" >
                            
                        </tbody>
                    </table>
                        
                              
                        
                    <input type="text" class="mb-2 form-control w-25 pesquisa_cliente" placeholder="Pesquisar" id="campoPesquisa">
                             
                       


                    <div  class="div-cliente-re"> 
                                                   
                    </div>

                    <table id="body_busca_2" class="table mb-0 table-striped table-hover" id="tablePrint">
                        <thead >
                            <tr>
                                <th style="width:2%">ID</th>
                                <th>Cliente</th>
                               <!-- <th style="width:14%">Moeda</th>
                                <th style="width:14%">Valor parcela</th>
                                <th style="width:14%">Vencimento</th>
                                <th style="width:6%">Status</th>
                                <th class="noPrint"></th>
                                <th class="noPrint"></th>-->
                            </tr>
                        </thead>
                        

                        <tbody id="body_busca_2">
                             
                            <?php  
                            $result = array();
                                  $mysql = "SELECT tbl_clientes.id,tbl_clientes.razaoSocial_nome FROM tbl_clientes INNER JOIN tbl_recebimentos ON tbl_recebimentos.id_cliente = tbl_clientes.id GROUP BY tbl_clientes.id ";



                                    $mysql = $pdo->query($mysql);
                                    if($mysql->rowCount() > 0){
                                        $result = $mysql->fetchAll();                         
                                       } 


                                       ?>                                  

             <?php foreach($result as $resultado): ?> 
                <tr>
                    <td><?php echo $resultado['id']; ?></td>
                    <td><?php echo $resultado['razaoSocial_nome']; ?></td>
                    <td><i style="cursor: pointer;"
                     onclick="verDados(<?php echo $resultado['id']; ?>)"  class="fas fa-user-edit icon-gradient bg-happy-itmeo clickA"></i>  ver</td>                                         

                </tr>               

                                        <tr><td><div class="resultado_item " >sadasd</div></td></tr>                        
                                          
                                     <?php endforeach;?>                                                  



                                <?php
                                  

                            

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


 <!-- modal_result -->
<div    class="modal show modal_result" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xg">
  <div class="modal-content">
<div class="content">
     
        <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">
<div class="alert alert-warning nao_encontrado"><center>Nenhum resultado encontrado</center></div>

                    <div style="float: right;">
                    <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
            <i class="fas fa-plus"><br></i>
            </button>

              <button class="btn-shadow mr-3 btn btn-danger modal_result_fechar" type="button" data-toggle="modal"  >
                  Sair
            </button>
        </div>

                   <br>
                    <h5 class="card-title">Recebimentos</h5>
                          
                          <div class="modal-header">
            </div>
            <div class="modal-body receb_calendar">
               
            </div>
               




                 <!-- campo adicionado por roney -->
                 <!-- modal parcela -->
 
     
        
            
         
    
 
<!-- modal parcelas -->


             <!-- campo adicionado por roney -->








              <table class="table mb-0 table-striped table-hover" id="tablePrint">
    <thead >
        <tr>
            <th style="width:2%">ID</th>
            <th>Cliente</th>
            <th style="width:14%">Serviço</th>
            <th style="width:14%">Moeda</th>
            <th style="width:14%">Valor Real</th>
            <th style="width:14%">Data Cotação</th>
            <th style="width:14%">Ações</th>
            <th style="width:14%">Status</th>
             
            <th class="noPrint"></th>
            <th class="noPrint"></th> 
        </tr>
    </thead>
    <tbody  id="result_modal">


  
         </tbody>
     </table>

</div>
</div>
</div>
</div>
</div>      
             
            </div>
             
        </div>
    </div>
</div>

 
<!-- fim modal_result -->









<!-- modal_result_receb -->
<div  class="modal show modal_result_receb" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xg">
  <div class="modal-content">
 


<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">
                   <div style="float: right;">
                    <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
            <i class="fas fa-plus"><br></i>
            </button>

              <button class="btn-shadow mr-3 btn btn-danger modal_result_receb_fechar" type="button" data-toggle="modal"  >
                  Sair
            </button>

            <button class="btn-shadow mr-3 btn btn-success modal_result_receb_fechar" type="button" data-toggle="modal"  onclick="imprimir_relatorio()" >
                  imprimir
            </button>




        </div>     
                  <div id="relatorio_receb">
                      

                 
                   <div style="clear:both;"></div>
                    <h5 class="card-title">Recebimentos Parcelados </h5>
                    <br><br>




                
              
              <table class="table mb-0 table-striped table-hover" id="tablePrint">
    <thead >
        <tr>
            
            <th style="width:2%">ID_Parcela</th>             
            <th style="width:14%">Valor da parcela</th>
            <th style="width:14%">Valor pago</th>
            <th style="width:14%">Data parcela</th>
            <th style="width:14%">Moeda</th>
            <th style="width:14%">Status</th>
            <th style="width:14%">Açoes</th>

             
            <th class="noPrint"></th>
            <th class="noPrint"></th> 
        </tr>
    </thead>
    <tbody  id="result_modal_parc">


         </tbody>
     </table>
        
       <br><br>

<div class="app-page-title">
                  
            <div  class="valor_tot_real">
                <span>Total em reais </span> <span class="tot_real"> 0 </span>
                
            </div>
            <div style="clear: both;"></div>

             <div class="valor_tot_euro estilo_campo_valor">
                <span>Total em euros </span > <span class="tot_euro"> 0</span>
                
            </div>
             
         
         
        
    
  

   
 


</div>
  
















      </div>

</div>
</div>
</div>
</div>
</div>


      
             
            </div>
             
        </div>
    </div>
</div>

 
<!-- fim modal_result_receb -->





<!-- modal status -->
<div  class="modal show modal_status_result" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xg">
  <div class="modal-content">
 


<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">
                   <div style="float: right;">
                    <button class="btn-shadow mr-3 btn btn-dark" id="btn-modal" type="button" data-toggle="modal" data-target="#mdl-cliente">
            <i class="fas fa-plus"><br></i>
            </button>

              <button class="btn-shadow mr-3 btn btn-danger modal_status_fechar" type="button" data-toggle="modal"  >
                  Sair
            </button>

            <button class="btn-shadow mr-3 btn btn-success modal_result_receb_fechar" type="button" data-toggle="modal"  onclick="imprimir_relatorio_p()" >
                  imprimir
            </button>




        </div>     
                  <div id="relatorio_receb " class="r">
                      

                 
                   <div style="clear:both;"></div>
                    <h5 class="card-title">Transações Pago </h5>
                    <br><br>




                
              
              <table class="table mb-0 table-striped table-hover" id="tablePrint">
    <thead >
        <tr>
            
            <th style="width:2%">ID_Parcela</th>             
            <th style="width:14%">Valor da parcela</th>
            <th style="width:14%">Valor pago</th>
            <th style="width:14%">Data parcela</th>
            <th style="width:14%">Moeda</th>
            <th style="width:14%">Status</th>
             

             
            <th class="noPrint"></th>
            <th class="noPrint"></th> 
        </tr>
    </thead>
    <tbody  id="result_modal_status">


         </tbody>
     </table>
        
       <br><br>

<div class="app-page-title">
                  
            <div  class="valor_tot_real">
                <span>Total em reais </span> <span class="tot_status_real"> 0 </span>
                
            </div>
            <div style="clear: both;"></div>

             <div class="valor_tot_euro estilo_campo_valor">
                <span>Total em euros </span > <span class="tot_status_euro"> 0</span>
                
            </div>
             
         
         
        
    
  

   
 


</div>
  
















      </div>

</div>
</div>
</div>
</div>
</div>


      
             
            </div>
             
        </div>
    </div>
</div>

 
<!-- fim modal status -->



























<!-- modal -->
<div  class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar novo recebimentos</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- action="cad-grava-recebimento.php" -->
                <form  id="form" method="post" enctype="multipart/form-data" >

                    <input type="hidden" value="add" name="cmd">

                    <div class="row mb-3">
                        <div class="col">
                            <label for="cliente">Cliente<span class="ml-2 text-danger">*</span></label>
                                     
                                     <div class="row">
                                         <div class="col-lg-6">
                                             
                                           <input type="text"   class="form-control cliente-id"  placeholder="Digite o nome do cliente" required>

                                           <input type="hidden" class="form-control cliente-id-evia" name="1cliente" id="cliente"   required>

                                                <div  class="div-cliente"> 
                                                   
                                                </div>
                                         </div>


                                         <div class="col-lg-6">
                                             
                                               <select class="form-control oculta_select" name="cliente" id="cliente" required>
                                <option selected disabled>Todos clientes</option>
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

                          
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <label for="cotacoa_vlr_euro">Cotação Euro €</label>
                             <input type="text"  value="<?php echo $EuroCotacao;?>" onchange="CalcularEuroParaReal()" class="form-control mb-3 estrangeiroInput" name="cotacoa_vlr_euro" id="cotacoa_vlr_euro" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                        </div>

                         
                         <!-- adicionado campo de servicos por roney -->

                        <div class="col-3"> 

                           <?php 
                                 $array_servicos = array();            
                                    $mysql = "SELECT * FROM tbl_servicos WHERE status = '1'";
                                      $mysql = $pdo->query($mysql);
                                    if($mysql->rowCount() > 0){

                                        $array_servicos = $mysql->fetchAll();                                    

                                    }
                           
                            ?>

                            <label for="servicos">Serviços</label>
                            <select  name="servicos" class="form-control">
                             
                             <?php foreach($array_servicos as $serv):  ?>
                                 
                                <option value="<?php echo $serv['id']; ?>"><?php echo $serv['nome'];?></option>
                                  
                                 
                             <?php endforeach;  ?>


                               
                            </select>
                        </div>


                        <!-- adicionado campo de servicos por roney -->



                        <div class="col-3">
                            <label for="vlr_euro">Valor<span class="ml-2 text-danger">*</span></label>
                             <input  type="text" onchange="CalcularEuroParaReal()" placeholder="Digite um valor"   class="form-control mb-3 estrangeiroInput valor_e" name="vlr_euro" id="vlr_euro" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
                        </div>





                        <div class="col-3">
                            <label for="moeda">Moeda</label>
                                  
                                <?php
                                $array_servicos = array();            
                                    $mysql = "SELECT * FROM tbl_moedas";
                                    $mysql = $pdo->query($mysql);
                                    
                                    if($mysql->rowCount() > 0){

                                        $array_servicos = $mysql->fetchAll();                                    

                                    }


                               ?>  

                           
                            <select name="moeda" id="moeda" class="form-control" onclick="CalcularEuroParaReal()">
                                  <option  >- Selecione a moeda -</option>
                               <?php foreach($array_servicos as $moed): ?>

                              <option value="<?php echo $moed['id'];  ?>"><?php echo $moed['nome_moeda'];  ?></option>
                                

                               <?php endforeach;  ?>



                                
                            </select>
                        </div>
						
						
						<div class="col-3 simboloReal">
                            <label for="vlr_real">Valor Real</label>
                             <input   type="text" disabled value="R$ 0,00" class="form-control mb-3 estrangeiroInput" name="vlr_real" id="vlr_real" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
                        </div>
                        
                        
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <label for="parcelamento">Parcelamento</label>
                            <select class="form-control mb-3" name="parcelamento" id="parcelamento" onchange="HabilitarCamposParcelasGrid()">
                                <option value="0" selected >Não</option>
                                <option value="1" >Sim</option>
                            <select>                       		
                        </div>

                        <div class="col-2">
                            <label for="tipoValor">Tipo</label>
                            <select name="tipoValor" id="tipoValor" class="form-control" onchange="CalcularParcelas()" disabled>
                                <option value="2">%</option>
                                <option value="1">Valor</option>
                            </select>
                        </div>
									
						<div class="col-3">
                            <label for="vlr_entrada">Entrada</label>
                             <input type="text"  placeholder="Digite o valor da entrada"  disabled="true"  onchange="CalcularParcelas()" class="form-control mb-3 estrangeiroInput" name="vlr_entrada" id="vlr_entrada" <?=$row['estrangeiro'] != 0 ? 'required' : '' ?>>
                             
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
										<option value="01" >Entrada e/ou 1 Parcela</option>
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
                <button type="button" class="btn btn-primary" id='submit_btn' onclick="$('#form').submit()">Salvar</button>
            </div>
        </div>
    </div>
</div>


  

  



<script type="text/javascript" src="./assets/scripts/car-novo-recebimento.js"></script>
<!-- fim modal -->

<!-- modal parcela -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-parcela">
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="form-parcela">
                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_parcelas_recebimentos where id_parcela = '.$_GET['edt']);
                            $parcela = $resp->fetch_assoc();
                        }
                    ?>

                    <input type="hidden" value="edt" name="cmd">
                    <input type="hidden" value="<?=$_GET['edt']?>" name="id">
                    <div class="row">
                        <div class="col d-flex">
                            <input class="form-control" name="valor_parc" value="<?=$parcela['valor_parcela']?>" type="number" step="0.01">
                        </div>
                        <div class="col-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ind_pago" id="ind_pago" <?=$parcela['ind_pago']==0?'':'checked';?>>
                                <label class="form-check-label" for="" >Pago</label> 
                            </div>  
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" id="data"  name="data" value="<?=$parcela['data_parcela']?>">
                        </div>  
                        <div class="col"> 
                            <input  class="form-control-file"  type="file" name="arquivo" id="arquivo">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <p class="text-start"><span class="ml-2 text-danger">*</span> Campos obrigatórios</p>
                <button type="button" class="btn btn-secondary" onclick="location.href='?'">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="$('#form-parcela').submit()">Atualizar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal parcelas -->

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
 
<?php if (isset($_GET['edt'])) echo "<script>$('#mdl-parcela').modal()</script>"; ?>











                    


 