<div class="row shadow bg-white rounded">
    <div class="col p-2">

        <h3 class="mb-3 ml-4">Sistema</h3>
        <form method="POST" class="ml-4 mb-4">
            <div class="row mb-3">
                <label for="homologacao" class="col-2"><strong>Homologação</strong></label>
                <div class="col">
                    <input type="checkbox" name="homologacao" id="homologacao" <?=$debug==1?'checked':'';?>>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <input type="hidden" name="cmd" value="sistema">
                    <input type="submit" value="Salvar" class="btn btn-success">
                </div>
            </div>
        </form>

        <h3 class="mb-3 ml-4">Empresas</h3>
        <div id="accordion" data-children=".card">
            
            <?
            $resp = $con->query('select * from tbl_configuracao');
            while($usuario = $resp->fetch_assoc()):
            ?>

                <div class="card mb-2">
                    <div class="card-header btn d-flex" onclick="modal('#c-<?=$usuario['id']?>')">
                        <a class="card-link"><?=$usuario['razao_social']?> - <?=$usuario['cnpj']?></a><span class="btn ml-auto"><a class="btn text-danger" href="?del=<?=$usuario['id']?>"><i class="fa fa-trash"></i></a></span>
                    </div>
                    <div class="collapse" id="c-<?=$usuario['id']?>">
                        <div class="card-body">
                            
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" value="edt" name="cmd">
                                <input type="hidden" value="<?=$usuario['id'];?>" name="id">
                                <table class="table table-borderless" id="tbl_configuracao">
                                    
                                    <tr>
                                        <th class="text-right" style="width:14%">Razão social</th>
                                        <td><input type="text" name="razao_social" class="form-control w-50" value="<?php echo $usuario['razao_social'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Nome fantasia</th>
                                        <td><input type="text" name="nome_fantasia" class="form-control w-50" value="<?php echo $usuario['nome_fantasia'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Cnpj</th>
                                        <td><input type="text" name="cnpj" class="form-control w-50" value="<?php echo $usuario['cnpj'];?>" maxlength="20"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">I.E.</th>
                                        <td><input type="text" name="ie" class="form-control w-25" value="<?php echo $usuario['ie'];?>" maxlength="20"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">I.M.</th>
                                        <td><input type="text" name="im" class="form-control w-25" value="<?php echo $usuario['im'];?>" maxlength="20"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Cep</th>
                                        <td><input type="text" name="cep" class="form-control w-25" value="<?php echo $usuario['cep'];?>" maxlength="9"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Cidade</th>
                                        <td><input type="text" name="cidade" class="form-control w-50" value="<?php echo $usuario['cidade'];?>" maxlength="60"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">IBGE</th>
                                        <td><input type="number" name="ibge" class="form-control w-25" value="<?php echo $usuario['ibge'];?>"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Estado</th>
                                        <td><input type="text" name="estado" class="form-control w-25" value="<?php echo $usuario['estado'];?>" maxlength="2"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Bairro</th>
                                        <td><input type="text" name="bairro" class="form-control w-25" value="<?php echo $usuario['bairro'];?>" maxlength="40"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Endereco</th>
                                        <td><input type="text" name="endereco" class="form-control w-50" value="<?php echo $usuario['endereco'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Complemento</th>
                                        <td><input type="text" name="complemento" class="form-control w-50" value="<?php echo $usuario['complemento'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Número</th>
                                        <td><input type="text" name="numero" class="form-control w-25" value="<?php echo $usuario['numero'];?>" maxlength="10"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Telefone</th>
                                        <td><input type="text" name="telefone" class="form-control w-50" value="<?php echo $usuario['telefone'];?>" maxlength="15"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">CNAE</th>
                                        <td><input type="text" name="cnae" class="form-control w-50" value="<?php echo $usuario['cnae'];?>" maxlength="20"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">CRT</th>
                                        <td>
                                            <select name="crt" class="form-control w-25">
                                                <option value="1" <?php echo $usuario['crt'] == 1?'selected':'';?>>Simples nacional</option>
                                                <option value="3" <?php echo $usuario['crt'] == 3?'selected':'';?>>Regime normal</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Email</th>
                                        <td><input type="text" name="email" class="form-control w-50" value="<?php echo $usuario['email'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Site</th>
                                        <td><input type="text" name="site" class="form-control w-50" value="<?php echo $usuario['site'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Logo</th>
                                        <td><input type="text" name="logo" class="form-control w-50" value="<?php echo $usuario['logo'];?>" maxlength="120"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Msg. Fiscal</th>
                                        <td>
                                            <textarea style="resize:none;" name="msg_fiscal" class="form-control w-50"><?php echo $usuario['msg_fiscal'];?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">ISS %</th>
                                        <td><input type="number" step="0.01" name="iss" class="form-control w-25" value="<?php echo $usuario['iss'];?>"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Aliq. S.N. %</th>
                                        <td><input type="number" step="0.01" name="aliq_sn" class="form-control w-25" value="<?php echo $usuario['aliq_sn'];?>"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Aliq. PIS %</th>
                                        <td><input type="number" step="0.01" name="aliq_pis" class="form-control w-25" value="<?php echo $usuario['aliq_pis'];?>"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Aliq. Cofins %</th>
                                        <td><input type="number" name="aliq_cofins" step="0.01" class="form-control w-25" value="<?php echo $usuario['aliq_cofins'];?>"></td>
                                    </tr>
                                    
                                    <?if(isset($usuario['certificado']) && $usuario['certificado'] != ''):?>
                                        <tr>
                                            <th class="text-right" style="width:14%">Certificado</th>
                                            <td>
                                                <div class="input-group w-50">
                                                    <input type="file" name="certificado" class="form-control p-1">
                                                    <div class="input-group-append">
                                                        <input type="password" name="certSenha" value="<?php echo $usuario['certSenha'];?>">
                                                    </div>
                                                </div>
                                                <span class="badge badge-success"><?=$usuario['certificado']?></span>
                                            </td>
                                        </tr>
                                    <?else:?>
                                        <tr>
                                            <th class="text-right" style="width:14%">Certificado</th>
                                            <td>
                                                <div class="input-group w-50">
                                                    <input type="file" name="certificado" class="form-control p-1">
                                                    <div class="input-group-append">
                                                        <input type="password" name="certSenha">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?endif;?>

                                    <tr>
                                        <td></td>
                                        <td><button class="btn btn-primary">Salvar</button></td>
                                    </tr>
                                </table>
                            </form>

                        </div>
                    </div>
                </div>

            <?endwhile;?>

            <div class="card mb-2">
                <div class="card-header bg-success text-light text-center d-flex btn" onclick="modal('#c-novo')">
                    <h3 class="m-auto"><i class="fas fa-plus-circle"></i></h3>
                </div>
                <div class="collapse" id="c-novo">
                    <div class="card-body">
                        
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" value="add" name="cmd">
                            <table class="table table-borderless" id="tbl_configuracao">
                                
                                <tr>
                                    <th class="text-right" style="width:14%">Razão social</th>
                                    <td><input type="text" name="razao_social" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Nome fantasia</th>
                                    <td><input type="text" name="nome_fantasia" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Cnpj</th>
                                    <td><input type="text" name="cnpj" class="form-control w-50" value="" maxlength="20"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">I.E.</th>
                                    <td><input type="text" name="ie" class="form-control w-25" value="" maxlength="20"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">I.M.</th>
                                    <td><input type="text" name="im" class="form-control w-25" value="" maxlength="20"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Cep</th>
                                    <td><input type="text" name="cep" class="form-control w-25" value="" maxlength="9"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Cidade</th>
                                    <td><input type="text" name="cidade" class="form-control w-50" value="" maxlength="60"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">IBGE</th>
                                    <td><input type="number" name="ibge" class="form-control w-25" value=""></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Estado</th>
                                    <td><input type="text" name="estado" class="form-control w-25" value="" maxlength="2"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Bairro</th>
                                    <td><input type="text" name="bairro" class="form-control w-25" value="" maxlength="40"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Endereco</th>
                                    <td><input type="text" name="endereco" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Complemento</th>
                                    <td><input type="text" name="complemento" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Número</th>
                                    <td><input type="text" name="numero" class="form-control w-25" value="" maxlength="10"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Telefone</th>
                                    <td><input type="text" name="telefone" class="form-control w-50" value="" maxlength="15"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">CNAE</th>
                                    <td><input type="text" name="cnae" class="form-control w-50" value="" maxlength="20"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">CRT</th>
                                    <td>
                                        <select name="crt" class="form-control w-25">
                                            <option value="0" selected>Simples nacional</option>
                                            <option value="2" >Regime normal</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Email</th>
                                    <td><input type="text" name="email" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Site</th>
                                    <td><input type="text" name="site" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Logo</th>
                                    <td><input type="text" name="logo" class="form-control w-50" value="" maxlength="120"></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Msg. Fiscal</th>
                                    <td>
                                        <textarea style="resize:none;" name="msg_fiscal" class="form-control w-50"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">ISS %</th>
                                    <td><input type="number" step="0.01" name="iss" class="form-control w-25" value=""></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Aliq. S.N. %</th>
                                    <td><input type="number" step="0.01" name="aliq_sn" class="form-control w-25" value=""></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Aliq. PIS %</th>
                                    <td><input type="number" step="0.01" name="aliq_pis" class="form-control w-25" value=""></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Aliq. Cofins %</th>
                                    <td><input type="number" name="aliq_cofins" step="0.01" class="form-control w-25" value=""></td>
                                </tr>
                                <tr>
                                    <th class="text-right" style="width:14%">Certificado</th>
                                    <td>
                                        <div class="input-group w-50">
                                            <input type="file" name="certificado" class="form-control p-1">
                                            <div class="input-group-append">
                                                <input type="password" name="certSenha">
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td><button class="btn btn-primary">Adicionar</button></td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
    $('input[name="cnpj"]').mask('99.999.999/9999-99');
    $('input[name="cep"]').mask('99999-999');
    $('input[name="estado"]').mask('ZZ',{translation:{'Z':{pattern:/[A-Z]/}}});
    $('input[name="telefone"]').mask('(54)99999-9999');
    $('input[name="ie"]').mask('999/9999999');
</script>