<link href="./main.css" rel="stylesheet">
<?php

include('functions.php');

$contrato = $con->query('select * from tbl_contratos where id = '.$_GET['id'])->fetch_assoc();
$cliente = $con->query('select * from tbl_clientes where id = '.$contrato['cliente'])->fetch_assoc();

switch($contrato['status']){
    case 1:
        $status = 'Assinar';
        $corStatus = 'primary';
    break;
    case 2:
        $status = 'Em vigência';
        $corStatus = 'success';
    break;
    case 3:
        $status = 'Encerrado';
        $corStatus = 'dark';
    break;
}

?>

<style>
    p{
        font-size: 14px;
    }
    *{
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<div>

    <div class="row text-center">
        <div class="col">
            <h4 class="mt-3"><?php echo ucfirst($cliente['razaoSocial_nome']).' - '.$cliente['cnpj_cpf']; ?></h4>
        </div>
    </div>

    <div class="row text-center">
        <div class="col">
            <p><?php echo $cliente['cidade'].' / '.$cliente['estado'];?></p>
        </div>
    </div>

    <div class="row text-center">
        <div class="col">
            <div class="badge badge-<?php echo $corStatus;?> mb-3 pl-4 pr-4 p-2"><strong><?php echo $status;?></strong></div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col">
            <p>Data inicial <?php echo date('d / m / Y', strtotime($contrato['dataInicial']));?> - Data Final <?php echo date('d / m / Y', strtotime($contrato['dataFinal']));?></p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Serviço</th>
                <th style="width:20%">Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $resp = $con->query('select * from tbl_contratosServicos where contrato = '.$contrato['id']);

                $total = 0; 
                while($row = $resp->fetch_assoc()){
                    $servico = $con->query('select nome from tbl_servicos where id = '.$row['servicos'])->fetch_assoc()['nome'];
                    $total += $row['valor'];
                    echo '
                        <tr>
                            <td>'.$servico.'</td>
                            <td>R$ '.$row['valor'].'</td>
                        </tr>
                    ';
                }
            
            ?>
        </tbody>
        <tfooter>
            <tr>
                <td></td>
                <td><strong>Total: R$ <?php echo number_format($total,2,",",".")?></strong></td>
            </tr>
        </tfooter>
    </table>

</div>

<script>
    window.print();
    window.close();
</script>