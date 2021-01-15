<?php
    include($_COOKIE['ROOT'].'/functions.php');
    if(isset($_GET['empresa'])){
        setcookie('empresa',$_GET['empresa']);
        $_COOKIE['empresa'] = $_GET['empresa'];
    }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sistema de gerenciamento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">

    <script src="<?=__ROOT__?>/assets/scripts/jquery.js"></script>
    <script src="<?=__ROOT__?>/assets/scripts/jquery.mask.js"></script>
    <script src="<?=__ROOT__?>/assets/scripts/jscookies.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?=__ROOT__?>/assets/scripts/comp.js"></script>

    <link href="<?=__ROOT__?>main.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=__ROOT__?>/comp.css">
    <link rel="stylesheet" href="<?=__ROOT__?>/assets/fafaicons/css/all.min.css">

</head>
<body>
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

                <div class="app-header-right">

                    <div class="header-btn-lg pr-0 pl-3">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    
                                    <div class="btn-group mr-3 pr-3 border-secondary border-right">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <div class="widget-heading">
                                                <?
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
                                            
                                            <?
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