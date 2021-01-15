<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $con->query('update tbl_usuario set
        nome = "'.$_POST['nome'].'",
        telefone = "'.$_POST['telefone'].'",
        email = "'.$_POST['email'].'",
        usuario = "'.$_POST['usuario'].'",
        senha = "'.md5($_POST['senha']).'"
        where id = "'.$_POST['id'].'"
    ');
    redirect($con->error);
}

$resp = $con->query('select * from tbl_usuario where id = "'.$_SESSION['id'].'"');
$usuario = $resp->fetch_assoc();
?>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-user-tie icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Perfil</span>
                <div class="page-title-subheading">
                    Campo para configurações do perfil
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

                    <form method="post">
                        <input type="hidden" value="add" name="cmd">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-right" style="width:14%">UID</th>
                                <td><input type="text" class="form-control w-25" name="id" value="<?php echo $usuario['id'];?>" readonly required></td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:14%">Nome</th>
                                <td><input type="text" name="nome" class="form-control w-50" value="<?php echo $usuario['nome'];?>" required></td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:14%">Telefone</th>
                                <td><input type="text" name="telefone" class="form-control w-25" value="<?php echo $usuario['telefone'];?>" required></td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:14%">Email</th>
                                <td><input type="text" name="email" class="form-control w-50" value="<?php echo $usuario['email'];?>" required></td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:14%">Usuário</th>
                                <td><input type="text" name="usuario" class="form-control w-25" value="<?php echo $usuario['usuario'];?>" required></td>
                            </tr>
                            <tr>
                                <th class="text-right" style="width:14%">Senha</th>
                                <td><input type="password" name="senha" class="form-control w-25" required></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button class="btn btn-primary">Salvar</button></td>
                            </tr>
                        </table>
                    </form>        

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