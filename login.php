<?php
    session_start();
    
    require_once('con.php');

   
   // require_once('_con.php');
    
    if(isset($_POST['cmd'])){ 

        if($_POST['cmd'] == "acessar"){
              
                     
               


            $resp = $con->query('select * from tbl_usuario where usuario = "'.$_POST['usuario'].'" and senha = "'.md5($_POST['senha']).'"');
                


            if($resp->num_rows == 1){


                $usr = $resp->fetch_assoc();
                $_SESSION['usuario'] = $_POST['usuario'];
                $_SESSION['nome'] = $usr['nome'];
                $_SESSION['senha'] = md5($_POST['senha']);
                $_SESSION['id'] = $usr['id'];
                $_SESSION['perm'] = $usr['permissao'];
                $_SESSION['cargo'] = $usr['cargo'];
                setcookie('nome',$usr['nome']);
                setcookie('cargo',$usr['cargo']);
                echo $usr['altSenha']?"<script>location.href='index.php'</script>":"<script>location.href='?n'</script>";
            }
            else{
                echo "<script>location.href='?e'</script>";
            }
        }
        elseif($_POST['cmd'] == "alterar"){

                 
            $con->query('update tbl_usuario set altSenha = 1, senha = "'.md5($_POST['nSenha']).'" where id = '.$_SESSION['id']);
            $_SESSION['senha'] = md5($_POST['nSenha']);
            echo "<script>location.href='index.php'</script>";
        }
    }
    else{


        $_SESSION['usuario'] = null;
        $_SESSION['nome'] = null;
        $_SESSION['senha'] = null;
        $_SESSION['id'] = null;
        $_SESSION['perm'] = null;
        $_SESSION['cargo'] = null;
        setcookie('nome', time() - 3600);
        setcookie('cargo', time() - 3600);
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistema de gerenciamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">

    <script src="assets/scripts/jquery.js"></script>
    <script src="assets/scripts/jquery.mask.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="./assets/scripts/comp.js"></script>

    <link href="./main.css" rel="stylesheet">
    <link rel="stylesheet" href="./comp.css">
    <link rel="stylesheet" href="assets/fafaicons/css/all.min.css">
</head>


<body>
    <div class="app-container bg-dark body-tabs-shadow fixed-sidebar fixed-header">
        <?if(isset($_GET['e'])):?>
            <div class="fixed-top m-3 alert alert-danger mt-2">
                <strong>Erro!</strong> usuário ou senha inválidos.
            </div>
        <?endif;?>
        <div class="content d-flex h-100">
            <div class="login-area p-2 m-auto">

                <!--formulario login-->
                <form method="post">
                    
                    <div class="row">
                        <div class="col text-center mb-3">
                            <h2><strong class="text-white">Login</strong></h2>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">                       
                            <div class="group-inputs">
                                <i class="fas fa-user"></i>
                                <input type="hidden" name="cmd" value="acessar">
                                <input type="text" name="usuario" placeholder="Usuário ...">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="group-inputs">
                                <i class="fas fa-key"></i>
                                <input type="password" name="senha" placeholder="Senha">
                            </div>
                        </div>
                    </div>                    
                    <div class="row mt-4">
                        <div class="col">
                            <button class="btn btn-outline-secondary w-100 p-3 mt-3 float-right text-white d-flex"><strong class="ml-auto mr-auto">Entrar</strong><i class="fas fa-sign-in-alt ml-auto mt-auto mb-auto"></i></button>
                        </div>

                    </div>
                </form>
                
            </div>
        </div>
    </div>
</body>
</html>
