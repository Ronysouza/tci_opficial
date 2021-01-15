<?php

if(isset($_GET['tbl'])){
    $con = new mysqli("localhost","uniserve","Ag147258@","data_uniserve");
    
    header('Content-Type: application/csv');
    $f = fopen('php://output', 'a');

    switch($_GET['tbl']){
        case 'clientes':
            header('Content-Disposition: attachment; filename="clientes.csv";');
            $resp = $con->query('select * from tbl_clientes where tipoCliente = "on"');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        
        case 'fornecedores':
            header('Content-Disposition: attachment; filename="fornecedores.csv";');
            $resp = $con->query('select * from tbl_clientes where tipoFornecedor = "on"');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        
        case 'funcionarios':
            header('Content-Disposition: attachment; filename="funcionarios.csv";');
            $resp = $con->query('select * from tbl_clientes where tipoFuncionario = "on" or tipoTecnico = "on"');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        
        case 'servicos':
            header('Content-Disposition: attachment; filename="servicos.csv";');
            $resp = $con->query('select * from tbl_servicos');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        case 'addServicos':
            header('Content-Disposition: attachment; filename="addServicos.csv";');
            $resp = $con->query('select * from tbl_clienteServicos');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        case 'ordemServico':
            header('Content-Disposition: attachment; filename="ordemServico.csv";');
            $resp = $con->query('select * from tbl_ordemServico');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        case 'unidades':
            header('Content-Disposition: attachment; filename="unidades.csv";');
            $resp = $con->query('select * from tbl_unidades');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        case 'cfop':
            header('Content-Disposition: attachment; filename="cfop.csv";');
            $resp = $con->query('select * from tbl_cfop');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
        case 'ncm_cest':
            header('Content-Disposition: attachment; filename="ncm_cest.csv";');
            $resp = $con->query('select * from tbl_ncm_cest');
            foreach($resp->fetch_all() as $row){
                fwrite($f, implode(";",$row)."\n");
            }
        break;
    }

    return;
}

?>