<?
    #require_once('functions.php');
    require_once('_con.php');
    require_once('core/lib/juno/class.php');
    $juno = new Juno();
?>

<?
error_reporting(0);

$empresa = $con->query('select * from tbl_configuracao where id = '.$_GET['e'])->fetch_assoc();

$cobranca = $con->query('select * from tbl_contasReceber where referenciaPagamento = "'.$_GET['c'].'"');
if($cobranca){
    $cobranca = $cobranca->fetch_assoc();
}

$resp = $con->query('select token,pagamentoStatus from tbl_configuracao where id = '.$_GET['e']);
if($resp->num_rows > 0){
    $resp = $resp->fetch_assoc();
    if($resp['pagamentoStatus'] == '1'){
        $juno->loadRecipientToken($resp['token']);
    }
}

var_dump($juno->consulta('D2CE444503E914B86E0560501B436C510CF582D892B3C766E2E59C88B8C22F21'));

if(isset($_POST['cmd'])){
    $pagamento = array(
        "chargeId" => $_GET['c'],
        "billing" => array(
            "email" => $_POST['email'],
            "address" => array(
                "street" => $_POST['rua'],
                "number" => $_POST['numero'],
                "complement" => $_POST['complemento'],
                "neighborhood" => $_POST['bairro'],
                "city" => $_POST['cidade'],
                "state" => $_POST['estado'],
                "postCode" => str_replace('-','',$_POST['cep'])
            ),
            "delayed" => false
        ),
        "creditCardDetails" => array(
            "creditCardHash" => $_POST['hashCartao']
        )
    );

    $resp = $juno->pagamento($pagamento);
    var_dump($resp);
    $sucesso = true;
    foreach($resp->payments as $payment){
        if($payment->status == "CONFIRMED"){
            $con->query('update tbl_contasReceber set status = 1 where referenciaPagamento = "'.$payment->chargeId.'"');
        }
        else{
            $sucesso = false;
        }
    }
    if($sucesso) 
        echo "
            <script>
                alert('Pagamento finalizado com sucesso');
                window.close();
            </script>
        ";
}

?>

<html>
<head>
    <title></title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    
    <script src="assets/scripts/jquery.js"></script>
    <script src="assets/scripts/jquery.mask.js"></script>
    <script src="assets/scripts/jscookies.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="assets/scripts/lib/card/card.js"></script>

    <link href="./main.css" rel="stylesheet">
    <link rel="stylesheet" href="./comp.css">
    <link rel="stylesheet" href="assets/fafaicons/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-center">
    <h5><strong><span class="text-white"><?=ucfirst($empresa['razao_social'])?></span></strong></h5>
</nav>

<div class="container">
    <form method="post" id="formPagamento">
        <input type="hidden" name="cmd" value="pagar">
        <div class="row pt-4">
            
            <div class="col mb-4">
                <div><h5><b>Geral</b></h5></div>
                <div class="border mb-4 shadow"></div>

                <div class="row">
                    <div class="col">
                        <b>Descricao</b><br>
                        <p class="mb-4"><?=$cobranca['descricao']?></p>
                    </div>
                    <div class="col">
                        <b>Valor Total</b>
                        <p style="font-size:44px;" class="text-success"><u><?=number_format($cobranca['valor'],2,',',' ')?></u></p>
                    </div>            
                </div>

                <div class="mt-4"><h5><b>Cliente</b></h5></div>
                <div class="border mb-4 shadow"></div>

                <div class="row">
                    <div class="col">
                        <label for="email"><b>Email<span class="ml-2 text-danger">*</span></b></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>

                <div class="mt-4"><h5><b>Endereço</b></h5></div>
                <div class="border mb-4 shadow"></div>

                <div class="row">
                    <div class="col">
                        <label for="cidade"><b>Cidade<span class="ml-2 text-danger">*</span></b></label>
                        <input type="text" class="form-control" name="cidade" required>
                    </div>
                    <div class="col-4">
                        <label for="estado"><b>Estado<span class="ml-2 text-danger">*</span></b></label>
                        <select class="form-control" name="estado" required>
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
                            <option value="EX">Estrangeiro</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <label for="cep"><b>CEP<span class="ml-2 text-danger">*</span></b></label>
                        <input type="text" class="form-control" name="cep" id="cep" required>
                    </div>
                    <div class="col">
                        <label for="bairro"><b>Bairro<span class="ml-2 text-danger">*</span></b></label>
                        <input type="text" class="form-control" name="bairro" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <label for="rua"><b>Rua<span class="ml-2 text-danger">*</span></b></label>
                        <input type="text" class="form-control" name="rua" required>
                    </div>
                    <div class="col-4">
                        <label for="numero"><b>Número<span class="ml-2 text-danger">*</span></b></label>
                        <input type="text" class="form-control" name="numero" required>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <label for="complemento"><b>Complemento</b></label>
                        <input type="text" class="form-control" name="complemento">
                    </div>
                </div>
            </div>

            <div class="col border-left">
            <div class="sticky-top">
                    <div><h5><b>Pagamento</b></h5></div>
                    <div class="border mb-4 shadow"></div>

                    <div class='card-wrapper'></div>

                    <div id="form-cartao">
                        <div class="row mt-4">
                            <div class="col">
                                <label for="number"><b>Número do cartão<span class="ml-2 text-danger">*</span></b></label>
                                <input type="text" class="form-control" name="number" id="number" onkeyup="gerarHash()">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <label for="name"><b>Nome impresso no cartão<span class="ml-2 text-danger">*</span></b></label>
                                <input type="text" class="form-control" name="name"  id="name" onkeyup="gerarHash()"/>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <label for="expiry"><b>Validade<span class="ml-2 text-danger">*</span></b></label>
                                <input type="text" class="form-control" name="expiry" id="expiry" onkeyup="gerarHash()"/>
                            </div>
                            <div class="col-4">
                                <label for="cvc"><b>CVC<span class="ml-2 text-danger">*</span></b></label>
                                <input type="text" class="form-control" name="cvc" id="cvc" onkeyup="gerarHash()"/>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="hashCartao" id="hashCartao">

                    <div class="row mt-4">
                        <div class="col">
                            <button class="btn btn-success w-25" id="btnPagar" disabled><b>Pagar</b></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script type="text/javascript" src="<?=$juno->jsUrl?>"></script>
<script>
    var card = new Card({
        form: '#form-cartao',
        container: '.card-wrapper',

        placeholders: {
            number: '**** **** **** ****',
            name: 'Arya Stark',
            expiry: '**/****',
            cvc: '***'
        }
    });

    $('#cep').mask('99999-999');

    function gerarHash(){
        var flag = false;

        $('#form-cartao input').filter(function(id,item){if($(item).val() == "") flag = true;});

        if(flag) return;

        /* Em sandbox utilizar o construtor new DirectCheckout('PUBLIC_TOKEN', false); */   
        var checkout = new DirectCheckout('<?=$juno->publicToken?>'<?=$juno->debug?',false':''?>);      

        expiry = $('#expiry').val().split(' / ');
        var cardData = {
            cardNumber: $('#number').val().replace(/\s/g,''),
            holderName: $('#name').val(),
            securityCode: $('#cvc').val(),
            expirationMonth: expiry[0],
            expirationYear: '20'+expiry[1]
        };

        if(
            checkout.isValidCardNumber(cardData.cardNumber) &&
            checkout.isValidSecurityCode(cardData.cardNumber, cardData.securityCode) &&
            checkout.isValidExpireDate(cardData.expirationMonth, cardData.expirationYear)
        ){
            checkout.getCardHash(cardData, function(cardHash) {
                $('#hashCartao').val(cardHash);
                $('#btnPagar').removeAttr('disabled');

            }, function(error) { console.log(error); });
        }
    }

    $(document).ready(function(){
        $('#formPagamento').submit(function(){
            $('#number').val('');
            $('#name').val('');
            $('#cvc').val('');
            $('#expiry').val('');
        });
    });
</script>

</body>
</html>