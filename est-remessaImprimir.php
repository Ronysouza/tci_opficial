<?
    require_once('_con.php');

    $pedido = $con->query('select * from tbl_remessa where id = '.$_GET['prt'])->fetch_assoc();
?>
<html>
<head>
    <link href="./main.css" rel="stylesheet">
    <link href="./assets/css/print.css" rel="stylesheet">
</head>
<body>

    <style>
        .linha{
            border-color: black;
            border-bottom-style: solid;
            border-bottom-width: 2px;
        }
    </style>

    <div class="content p-2">
        
        <div class="row linha pb-1">
            <div class="col">
                Remessa <strong>#<span><?=str_pad($_GET['prt'],5,0,STR_PAD_LEFT)?></span></strong>
            </div>
            <div class="col text-right">
                Iniciado: <strong><span><?=date('d/m/Y',strtotime($pedido['data']))?></span></strong>
            </div>
        </div>

        <div class="row linha pt-1 pb-1">
            <div class="col">
                Impresso em <span><?=date('d/m/Y');?></span>
            </div>
        </div>

        <div class="row linha">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Est. Inicial</th>
                            <th>Est. Final</th>
                            <th>Vendidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                            $resp = $con->query('select * from tbl_remessaItem where remessa = '.$_GET['prt']);
                            while($item = $resp->fetch_assoc()){
                                $produto = $con->query('select * from tbl_produtos where id = '.$item['produto'])->fetch_assoc();
                                $vendidos = $con->query('select sum(a.quantidade) as final from tbl_pedidoItem a inner join tbl_pedido b on b.id = a.pedido where a.produto = '.$item['produto'].' and b.remessa = '.$_GET['prt'].' group by a.produto')->fetch_assoc();
                                echo '
                                    <tr>
                                        <td>'.$produto['nome'].'</td>
                                        <td>'.$item['quantia'].'</td>
                                        <td>'.($item['quantia']-$vendidos['final']).'</td>
                                        <td>'.$vendidos['final'].'</td>
                                    </tr>
                                ';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>