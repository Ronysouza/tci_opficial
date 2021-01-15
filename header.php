<?php
    require_once('functions.php');
	//busca cotacao do euro
	$req_url = 'https://economia.awesomeapi.com.br/json/all';
    $response_json = file_get_contents($req_url);
    $response_object = json_decode($response_json);
    $EuroCotacao = $response_object->EUR->high;
	$EuroCotacao = $EuroCotacao +0.20; // taxas estimadas 0.20
	$EuroCotacao = round($EuroCotacao, 2);
    $EuroCotacao = str_replace(".",",",$EuroCotacao);
	
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="content-type" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistema de gerenciamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">

    <script src="assets/scripts/jquery.js"></script>
    <script src="assets/scripts/jquery.mask.js"></script>
    <script src="assets/scripts/jscookies.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="assets/scripts/lib/select2/dist/js/select2.min.js"></script>


    <script type="text/javascript" src="./assets/scripts/comp.js"></script>
    

    <link href="./main.css" rel="stylesheet">
    <link href="./assets/css/cad-recebimento.css" rel="stylesheet">
    <link rel="stylesheet" href="./comp.css">
    <link rel="stylesheet" href="assets/fafaicons/css/all.min.css">
    <link href="assets/scripts/lib/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="assets/css/select2-bootstrap.min.css" rel="stylesheet" />

    <script>
        $(document).ready(function(){
            $('.select2').select2({
                theme: "bootstrap",
                enabled: true
            });
            $('.select2modal').select2({
                theme: "bootstrap",
                enabled: true,
                dropdownParent: $(".modal")
            });
        });
    </script>

</head>
<body>
    <br>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">    
        
    <!-- CABECALHO -->
        <div class="app-header header-shadow bg-dark header-text-light">
            <div class="app-header__logo">
                <div class="header__pane">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
            <div class="app-header__content">
                <?if($_SESSION['perm'] == '1'):?>
                <div class="app-header-left">
                    <ul class="header-menu nav">
                        <li class="dropdown nav-item">
                            <a href="configuracao.php" class="nav-link">
                                <i class="nav-link-icon fa fa-cog"></i>
                                Configurações
                            </a>
                        </li>
                    </ul>
                </div>
                <?endif;?>
				
				<div class="app-header-left">
                    <ul class="header-menu nav">
                        <li class="dropdown nav-item">
                            <a class="nav-link">
                                <i class="nav-link-icon fa fa-eur"></i>
                                Cotação Euro R$ <?php  echo $EuroCotacao;?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="app-header-right">

                    <div class="header-btn-lg pr-0 pl-3">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    
                                    <div class="btn-group mr-3 pr-3 border-secondary border-right">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <div class="widget-heading">
                                                <?php
                                                    if(isset($_COOKIE['empresa'])){
                                                        echo $con->query('select razao_social from tbl_configuracao where id = '.$_COOKIE['empresa'])->fetch_assoc()['razao_social'];
                                                    }
                                                    else{
                                                        echo 'Selecione a empresa';
                                                    }
                                                ?>
                                            </div>
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            
                                            <?php
                                                $resp = $con->query('select id, razao_social from tbl_configuracao where id in (select valor from tbl_usuarioMeta where meta = "habilitar_empresa" and status = 1 and usuario = "'.$_SESSION['id'].'" )');
                                                while($row = $resp->fetch_assoc()){
                                                    echo '<a class="dropdown-item" tabindex="0" href="?empresa='.$row['id'].'">'.$row['razao_social'].'</a>';
                                                }
                                            ?>

                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <div class="widget-heading">
                                                <?php echo ucfirst($_COOKIE['nome']);?>
                                            </div>
                                            <div class="widget-subheading">
                                                <?php echo ucfirst(utf8_encode($_COOKIE['cargo']));?>
                                            </div>
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">

                                            <a href="perfil.php" tabindex="0" class="dropdown-item">Perfil</a>
                                            <?php if($_SESSION['perm'] == '1'):?>
                                                <a href="contas.php" tabindex="0" class="dropdown-item">Contas</a>
                                            <?php endif;?>

                                            <!--<button type="button" tabindex="0" class="dropdown-item">Configurações</button>-->

                                            <div tabindex="-1" class="dropdown-divider"></div>

                                            <a class="dropdown-item" href="login.php?exit">Sair</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                </div>

            </div>
        </div>
        <div class="app-main">
        <?php include('menu.php'); ?>