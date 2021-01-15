<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $con->query("insert into tbl_clientes(tipoPessoa,razaoSocial_nome, cnpj_cpf, nomeResponsavel, logradouro, numero, complemento, bairro, cidade, cep, email, telefoneEmpresa, telefoneWhatsapp, cpfResponsavel, observacao, estado, tipoCliente, tipoFornecedor, tipoFuncionario, tipoTecnico) values(
            '".$_POST['tipoPessoa']."',
            '".$_POST['razaoSocial_nome']."',
            '".$_POST['cnpj_cpf']."',
            '".$_POST['nomeResponsavel']."',
            '".$_POST['logradouro']."',
            '".$_POST['numero']."',
            '".$_POST['complemento']."',
            '".$_POST['bairro']."',
            '".$_POST['cidade']."',
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
            '".$_POST['tipo_tecnico']."'
        )");
        redirect($con->error);
    }
    elseif($cmd == "edt"){
        $con->query("update tbl_clientes set
            tipoPessoa = '".$_POST['tipoPessoa']."',
            razaoSocial_nome = '".$_POST['razaoSocial_nome']."',
            cnpj_cpf = '".$_POST['cnpj_cpf']."',
            nomeResponsavel = '".$_POST['nomeResponsavel']."',
            logradouro = '".$_POST['logradouro']."',
            numero = '".$_POST['numero']."',
            complemento = '".$_POST['complemento']."',
            bairro = '".$_POST['bairro']."',
            cidade = '".$_POST['cidade']."',
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
            tipoTecnico = '".$_POST['tipo_tecnico']."'
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
        $("#estado").val(resp['uf']);
        $("#cidade").val(resp['localidade']);
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
                <i class="fas fa-id-card-alt icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de Funcionários e Técnicos</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de funcionários e técnicos
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
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=funcionarios">
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

                    <h5 class="card-title">Funcionários e Técnicos cadastrados</h5>
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
                                $resp = $con->query('select * from tbl_clientes where (tipoFuncionario = "on" or tipoTecnico = "on") and status = 1  order by id desc');
                            
                                while($row = $resp->fetch_assoc()){
                                    echo '
                                        <tr>
                                            <td>'.str_pad($row['id'],3,"0",STR_PAD_LEFT).'</td>
                                            <td>'.($row['tipoPessoa'] == "PF" ? "Física" : "Júridica").'</td>
                                            <td>'.$row['razaoSocial_nome'].'</td>
                                            <td>'.$row['cnpj_cpf'].'</td>
                                            <td>'.$row['telefoneEmpresa'].'</td>
                                            <td>'.$row['cidade'].'/'.$row['estado'].'</td>
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
                <h5 class="modal-title">Adicionar novo funcionário ou técnico</h5>
                <button type="button" class="close" onclick="location.href='?'" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">

                    <?php
                        if(isset($_GET['edt'])){
                            $resp = $con->query('select * from tbl_clientes where id = '.$_GET['edt']);
                            $row = $resp->fetch_assoc();
                        }
                    ?>

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
                                    <label for="cnpj_cpf">CNPJ / CPF<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['cnpj_cpf'];?>" class="form-control mb-3" name="cnpj_cpf" id="cnpj_cpf"  required>
                                </div>
                            </div>

                            <div class="row">
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
                                    <label for="telefoneEmpresa">Telefone/Celular<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['telefoneEmpresa'];?>" class="form-control mb-3" name="telefoneEmpresa" id="telefoneEmpresa" required>
                                </div>
                                <div class="col">
                                    <label for="telefoneWhatsapp">Whatsapp</label>
                                    <input type="text" value="<?php echo $row['telefoneWhatsapp'];?>" class="form-control mb-3" name="telefoneWhatsapp" id="telefoneWhatsapp">
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="row">
                                <div class="col-3">
                                    <label for="cep">CEP<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['cep'];?>" class="form-control mb-3" name="cep" id="cep" required onchange="getCidade(this)">
                                </div>
                                <div class="col-4">
                                    <label for="estado">Estado<span class="ml-2 text-danger">*</span></label>
                                    <select name="estado" value="<?php echo $row['estado'];?>" id="estado" class="form-control mb-3" required readonly>
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
                                    <label for="cidade">Cidade<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['cidade'];?>" class="form-control mb-3" name="cidade" id="cidade" required readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="logradouro">Logradouro<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['logradouro'];?>" class="form-control mb-3" name="logradouro" id="logradouro" required>
                                </div>
								<div class="col">
                                    <label for="bairro">Bairro<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['bairro'];?>" class="form-control mb-3" name="bairro" id="bairro" required>
                                </div>
                                
                                <div class="col-2">
                                    <label for="numero">Número<span class="ml-2 text-danger">*</span></label>
                                    <input type="text" value="<?php echo $row['numero'];?>" class="form-control mb-3" name="numero" id="numero" required>
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
                                            <input type="checkbox" class="custom-control-input" id="tipo_funcionario" name="tipo_funcionario" checked>
                                            <label class="custom-control-label" for="tipo_funcionario">Funcionário</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="row mb-4 mt-2">
                                    <div class="col d-flex">
                                        <div class="custom-control custom-switch m-auto w-75">
                                            <input type="checkbox" class="custom-control-input" id="tipo_tecnico" name="tipo_tecnico" <?php echo $row['tipoTecnico'] == "on" || !isset($row['tipoTecnico']) ? 'checked' : '' ;?>>
                                            <label class="custom-control-label" for="tipo_tecnico">Técnico</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <input id="needs-validation" class="d-none" type="submit" value="enviar">

                </form>
                <script>
                    $(document).ready(function(){
                        $("#cpfResponsavel").mask('000.000.000-00', {reverse: true});
                        $("#telefoneEmpresa").mask('(99) 99999-9999');
                        $("#telefoneWhatsapp").mask('(99) 99999-9999');
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