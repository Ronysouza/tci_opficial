<?php include('header.php'); ?>

<?php

if(isset($_POST['cmd'])){
    if($_POST['cmd'] == "edt"){
        if($_POST['senha']){
            $query = 'UPDATE `tbl_usuario` SET
                `nome`= "'.$_POST['nome'].'",
                `telefone`= "'.$_POST['telefone'].'",
                `email`= "'.$_POST['email'].'",
                `usuario`= "'.$_POST['usuario'].'",
                `senha`= "'.md5($_POST['senha']).'",
                `permissao`= "'.$_POST['permissao'].'",
                `cargo`= "'.$_POST['cargo'].'",
                `altSenha`= 0 
                WHERE id = '.$_POST['id'].'
            ';
        }
        else{
            $query = 'UPDATE `tbl_usuario` SET
                `nome`= "'.$_POST['nome'].'",
                `telefone`= "'.$_POST['telefone'].'",
                `email`= "'.$_POST['email'].'",
                `usuario`= "'.$_POST['usuario'].'",
                `permissao`= "'.$_POST['permissao'].'",
                `cargo`= "'.$_POST['cargo'].'"
                WHERE id = '.$_POST['id'].'
            ';
        }
        //$con->query($query);
        if($_POST['id'] != 0){
            $con->query('delete from tbl_usuarioMeta where usuario = '.$_POST['id']);
            foreach($_POST['menus'] as $menu){
                $con->query('insert into tbl_usuarioMeta(meta,valor,descricao,status,usuario) values("habilitar_menu",1,"'.$menu.'",1,'.$_POST['id'].')');
            }
            foreach($_POST['empresas'] as $empresa){
                $con->query('insert into tbl_usuarioMeta(meta,valor,descricao,status,usuario) values("habilitar_empresa",'.$empresa.',"",1,'.$_POST['id'].')');
            }
        }
    }
    else if($_POST['cmd'] == "add"){
        if($_POST['senha']){
            $query = 'INSERT INTO `tbl_usuario`(`nome`, `telefone`, `email`, `usuario`, `senha`, `permissao`, `cargo`) VALUES (
                "'.$_POST['nome'].'",
                "'.$_POST['telefone'].'",
                "'.$_POST['email'].'",
                "'.$_POST['usuario'].'",
                "'.md5($_POST['senha']).'",
                "'.$_POST['permissao'].'",
                "'.$_POST['cargo'].'"
            )';
        }
        else{
            $query = 'INSERT INTO `tbl_usuario`(`nome`, `telefone`, `email`, `usuario`, `permissao`, `cargo`) VALUES (
                "'.$_POST['nome'].'",
                "'.$_POST['telefone'].'",
                "'.$_POST['email'].'",
                "'.$_POST['usuario'].'",
                "'.$_POST['permissao'].'",
                "'.$_POST['cargo'].'"
            )';
        }
        $con->query($query);
        $lastId = $con->insert_id;
        foreach($_POST['menus'] as $menu){
            $con->query('insert into tbl_usuarioMeta(meta,valor,descricao,status,usuario) values("habilitar_menu",1,"'.$menu.'",1,'.$lastId.')');
        }
        foreach($_POST['empresas'] as $empresa){
            $con->query('insert into tbl_usuarioMeta(meta,valor,descricao,status,usuario) values("habilitar_empresa",'.$empresa.',"",1,'.$lastId.')');
        }
    }

    redirect($con->error);
}

if(isset($_GET['edt'])){
    $resp = $con->query('select * from tbl_usuario where id = "'.$_GET['edt'].'"');
    $usuario = $resp->fetch_assoc();
    redirect($con->error);
}
if(isset($_GET['del'])){
    if($_GET['del'] != 0){
        $con->query('delete from tbl_usuario where id = "'.$_GET['del'].'"');
        $con->query('delete from tbl_usuarioMeta where usuario = "'.$_GET['del'].'"');
        redirect($con->error);
    }
}

?>

<script>
    function gerarSenha(){
        $('#senha').val(Math.floor((Math.random() * 100000000000)+100000000000).toString(16));
    }

    function modal(target){
        $('.collapse:not("'+target+'")').hide('slow');
        $(target).toggle('slow');
    }
    
</script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-user-tie icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Contas</span>
                <div class="page-title-subheading">
                    Campo para configurações do contas cadastradas
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

                    <form method="post" autocomplete="off">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" value="<?=isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                                <input type="hidden" value="<?=$_GET['edt'];?>" name="id">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="text-right" style="width:14%">UID</th>
                                        <td><input type="text" class="form-control" name="id" value="<?php echo $usuario['id'];?>" readonly required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Nome</th>
                                        <td><input type="text" name="nome" class="form-control" value="<?php echo $usuario['nome'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Telefone</th>
                                        <td><input type="text" name="telefone" class="form-control" value="<?php echo $usuario['telefone'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Cargo</th>
                                        <td><input type="text" name="cargo" class="form-control" value="<?php echo $usuario['cargo'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Email</th>
                                        <td><input type="text" name="email" class="form-control" value="<?php echo $usuario['email'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Usuário</th>
                                        <td><input type="text" name="usuario" class="form-control" value="<?php echo $usuario['usuario'];?>" required></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Senha</th>
                                        <td class="d-flex"><input type="text" name="senha" id="senha" class="form-control" autocomplete="off"><span class="btn btn-link" onclick="gerarSenha()">Gerar</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" style="width:14%">Permissão</th>
                                        <td>
                                            <select name="permissao" class="form-control" required>
                                                <option value="0" selected>Usuário</option>
                                                <option value="1">Administrador</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <?php if(isset($_GET['edt'])):?>
                                                <button class="btn btn-primary">Alterar</button>
                                            <?php else:?>
                                                <button class="btn btn-primary m-auto">Salvar</button>
                                            <?php endif;?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col border-left">
                                <div class="">
                                    <div id="accordion">
                                        <div class="card mb-2">
                                            <div class="card-header btn d-flex" onclick="modal('#c-permissoes')">
                                                <a class="card-link">Menus</a>
                                            </div>
                                            <div class="collapse campPerm" id="c-permissoes">
                                                <div class="card-body">
                                                
                                                    <?php
                                                        $resp = $con->query('select id,descricao from tbl_usuarioMeta where meta = "habilitar_menu" and usuario = 0 order by descricao');
                                                        while($row = $resp->fetch_assoc()){
                                                            if($row['descricao'] == 'inicio')
                                                                $hasPerm = true;
                                                            elseif(isset($_GET['edt']))
                                                                $hasPerm = $con->query('select id from tbl_usuarioMeta where meta = "habilitar_menu" and descricao = "'.$row['descricao'].'" and usuario = '.$_GET['edt'])->num_rows;
                                                            else
                                                                $hasPerm = false;

                                                            echo '
                                                                <div class="row mb-2 border-bottom">
                                                                    <div class="col" >
                                                                        <input type="checkbox" name="menus[]" value="'.$row['descricao'].'" id="p-'.$row['id'].'" '.($hasPerm?'checked':'').'>
                                                                        <label class="ml-3 w-75" for="p-'.$row['id'].'">'.$row['descricao'].'</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        }
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2">
                                            <div class="card-header btn d-flex" onclick="modal('#c-empresas')">
                                                <a class="card-link">Empresas</a>
                                            </div>
                                            <div class="collapse campPerm" id="c-empresas">
                                                <div class="card-body">
                                                
                                                    <?php
                                                        $resp = $con->query('select id,valor from tbl_usuarioMeta where meta = "habilitar_empresa" and usuario = 0');
                                                        while($row = $resp->fetch_assoc()){
                                                            if(isset($_GET['edt']))
                                                                $hasPerm = $con->query('select id from tbl_usuarioMeta where meta = "habilitar_empresa" and valor = "'.$row['valor'].'" and usuario = '.$_GET['edt'])->num_rows;
                                                            else
                                                                $hasPerm = false;

                                                            $empresa = $con->query('select razao_social from tbl_configuracao where id = '.$row['valor'])->fetch_assoc();
                                                            echo '
                                                                <div class="row mb-2 border-bottom">
                                                                    <div class="col" >
                                                                        <input type="checkbox" name="empresas[]" value="'.$row['valor'].'" id="e-'.$row['id'].'" '.($hasPerm?'checked':'').'>
                                                                        <label class="ml-3 w-75" for="e-'.$row['id'].'">'.$empresa['razao_social'].'</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        }
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>        

                </div>
            </div>

        </div>
    </div>

</div>
<div class="content">

    <div class="row">
        <div class="col">
            
            <div class="card main-card mb-3">
                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Permissão</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $resp = $con->query('select * from tbl_usuario');
                                while($row = $resp->fetch_assoc()){
                                    echo '
                                        <tr>
                                            <td>'.$row['nome'].'</td>
                                            <td>'.$row['cargo'].'</td>
                                            <td>'.$row['email'].'</td>
                                            <td>'.$row['usuario'].'</td>
                                            <td>'.($row['permissao']=='1'?'Administrador':'Usuário').'</td>
                                            <td><a href="?edt='.$row['id'].'"><i class="fas fa-wrench"></i></a></td>
                                            <td><a href="?del='.$row['id'].'"><i class="fas fa-trash"></i></a></td>
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