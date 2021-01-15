<?php
    $resp = $con->query('select * from tbl_usuarioMeta where meta = "habilitar_menu" and status = 1 and usuario = "'.$_SESSION['id'].'"');
    $menu = [];
    while($row = $resp->fetch_assoc()){
        $menu[$row['descricao']] = $row['valor'];
    }
?>
<!-- MENU -->
<div class="app-sidebar sidebar-shadow bg-dark sidebar-text-light">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
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
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">

                <li>
                    <a class="" <?php echo ($menu['inicio'])?'href="index.php"':'disabled';?>>
                        <i class="metismenu-icon fa fa-home"></i>
                        Início
                    </a>
                </li>

                <li>
                    <a <?php echo $menu['cadastro']?'href=""':'disabled';?> >
                        <i class="metismenu-icon fa fa-address-book"></i>
                        Cadastros
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['cadastro_clientes']?'href="cad-cliente.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Clientes
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['cadastro_servicos']?'href="cad-servico.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Serviços
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['cadastro_fornecedores']?'href="cad-fornecedor.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Fornecedores
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['cadastro_funcionarios']?'href="cad-funcionario.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Funcionários / Técnicos
                            </a>
                        </li>
                        
                    </ul>
                </li>

                <li>
                    <a <?php echo @$menu['estoque']?'href="#"':'disabled';?>>
                        <i class="metismenu-icon fa fa-cubes"></i>
                        Gestão de estoque
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['estoque_unidades']?'href="est-unidades.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Unidades de medidas
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_grupos']?'href="est-grupos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Grupos
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_subgrupos']?'href="est-subgrupos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Subgrupos
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_produtos']?'href="est-produtos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Produtos
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_importar_xml']?'href="est-impXml.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Importar XML
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_equipamentos']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Equipamentos
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_localEstoque']?'href="est-localEstoque.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Local de estoque
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_estoque']?'href="est-estoque.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Posição de estoque
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_movimentacaoEstoque']?'href="est-movimentacaoEstoque.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Movimentação do estoque
                            </a>
                        </li>  
                        <li>
                            <a <?php echo $menu['estoque_etiqueta']?'href="est-etiqueta.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Etiqueta
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['estoque_remessa']?'href="est-remessa.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Remessa
                            </a>
                        </li>                        
                    </ul>
                </li>

                <li>
                    <a <?php echo @$menu['contrato']?'':'disabled';?>>
                        <i class="metismenu-icon fas fa-file-signature"></i>
                        Gestão de contrato
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['contrato_contrato']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Contratos
                            </a>
                            <a <?php echo $menu['contrato_controle']?'href="con-controle.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Controle de medições
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a <?php echo @$menu['producao']?'href="#"':'disabled';?>>
                        <i class="metismenu-icon fas fa-hammer"></i>
                        Produção
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['producao_grade']?'href="prod-gradeProdutos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Grade de Produtos
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['producao_ordem']?'href="prod-ordem.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Ordem de Produção
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['producao_monitor']?'href="prod-monitor.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Monitor de Produção
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['producao_baixa']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Baixa de Produção
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['producao_liberacao']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Liberação de Produção
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a <?php echo @$menu['venda']?'':'disabled';?>>
                        <i class="metismenu-icon fas fa-shopping-basket"></i>
                        Venda
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['venda_pedido']?'href="ven-pedido.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Pedido de venda
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a <?php echo $menu['financeiro']?'':'disabled';?>>
                        <i class="metismenu-icon fas fa-money-bill-alt"></i>
                        Financeiro
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo $menu['financeiro_contasReceber']?'href="fisc-contasReceber.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Contas a receber
                            </a>
                        </li>
						<li>
                            <a <?php echo $menu['recebimentos_financeiro']?'':'disabled';?>>
                            <i class="metismenu-icon fas fa-money-bill-alt"></i>
                            Recebimentos
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="mm-collapse">
                                <li>
                                    <a <?php echo $menu['novo_recebimentos_financeiro']?'href="cad-novo-recebimento.php"':'disabled';?>>
                                        <i class="metismenu-icon">
                                        </i>Novo Recebimento
                                    </a>
                                </li>
                                
                                <li>
                                    <a <?php echo $menu['consulta_recebimentos_financeiro']?'href="fisc-contasReceber.php"':'disabled';?>>
                                        <i class="metismenu-icon">
                                        </i>Consultar Recebimentos
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
						
                    </ul>
					
					
                </li>

                <li>
                    <a <?php echo $menu['servicos']?'':'disabled';?>>
                        <i class="metismenu-icon fas fa-stream"></i>
                        Serviços
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <!--<li>
                            <a href="serv-adicionar.php">
                                <i class="metismenu-icon">
                                </i>Adicionar
                            </a>
                        </li>-->
                        <li>
                            <a <?php echo $menu['servicos_contratos']?'href="serv-criacontratos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Gestão de contratos
                            </a>
                        </li>
                        <!--<li>
                            <a <?php echo @$menu['criacao_contratos']?'href="serv-criacontratos.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Criação de contratos
                            </a>
                        </li>-->
                        <li>
                            <a <?php echo @$menu['servicos_chamados']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Chamados
                            </a>
                        </li>
                        <li>
                            <a <?php echo $menu['servicos_ordens']?'href="serv-ordemServico.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Ordens de Serviços
                            </a>
                        </li>
                        <li>
                            <a <?php echo @$menu['servicos_equipamentos']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Equipamentos
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a <?php echo @$menu['fiscal']?'href="#"':'disabled';?>>
                        <i class="metismenu-icon fas fa-file-invoice-dollar"></i>
                        Fiscal
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        <li>
                            <a <?php echo @$menu['fiscal_cfop']?'href="fisc-cfop.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>CFOP
                            </a>
                        </li>
                        <li>
                            <a <?php echo @$menu['fiscal_ncm_cest']?'href="fisc-ncm_cest.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>NCM/CEST
                            </a>
                        </li>
                        <li>
                            <a <?php echo @$menu['fiscal_nf']?'href="fisc-nfe.php"':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Lançar NF
                            </a>
                        </li>
                        <!--<li>
                            <a <?php echo @$menu['fiscal_nfe_manual']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Lançar NF (Manual)
                            </a>
                        </li>
                        <li>
                            <a <?php echo @$menu['fiscal_nfe_saida']?'href=""':'disabled';?>>
                                <i class="metismenu-icon">
                                </i>Emitir NF Saída
                            </a>
                        </li>-->
                    </ul>
                </li>

                <li>
                    <a <?php echo $menu['agenda']?'href="agenda.php?hoje"':'disabled';?>>
                        <i class="metismenu-icon fas fa-calendar-alt"></i>
                        Agenda
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
</div>
<div class="app-main__outer">
    <div class="app-main__inner">