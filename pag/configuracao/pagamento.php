<?
    $resp = $con->query('select * from tbl_configuracao where id = 1')->fetch_assoc();
?>

<div class="row shadow bg-white rounded">
    <div class="col shadow p-2 pl-4">

        <form id="ativarPagamento">
            <div class="row mb-4 mt-4">
                <div class="col-2 text-right"><strong>Empresa</strong></div>
                <div class="col d-flex">
                    <select class="form-control w-25" onchange="getEmpresa(this)" id="habilitarPagamentoEmpresa">
                        <option selected disabled>Selecione</option>
                        <?
                            $resp = $con->query('select id,razao_social from tbl_configuracao');
                            while($row = $resp->fetch_assoc()){
                                echo '<option value="'.$row['id'].'">'.$row['razao_social'].'</option>';
                            }
                        ?>
                    </select>
                    <!--<div class="ml-4 spinner-border"></div>-->
                </div>
            </div>
        </form>
        
        <form method="post" id="compEmpresa" style="display:none;">
            <h3>Empresa</h3>
            <div class="divider"></div>

            <input type="hidden" name="empresa" id="compEmpresaID" value="">

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Área de atuação</strong></div>
                <div class="col">
                    <select name="area" id="area" class="form-control w-25">
                        <option selected disabled>Selecione</option>
                        <?
                            foreach($juno->getBusinessAreas() as $item){
                                echo '<option value="'.$item->code.'">'.$item->category.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Tipo de companhia</strong></div>
                <div class="col">
                    <select name="tipo" id="tipo" class="form-control w-25">
                        <option selected disabled>Selecione</option>
                        <?
                            foreach($juno->getCompanyTypes() as $item){
                                echo '<option value="'.$item.'">'.$item.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right"><strong>Descrição</strong></div>
                <div class="col">
                    <input type="text" name="descricao" id="descricao" class="form-control w-50" maxlength="200">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right"><strong>Responsável legal</strong></div>
                <div class="col">
                    <input type="text" name="respLegal" id="respLegal" class="form-control w-50" maxlength="200">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right"><strong>Documento</strong></div>
                <div class="col">
                    <input type="text" name="docResp" id="docResp" class="form-control w-50" maxlength="200">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right"><strong>Data de aniversário</strong></div>
                <div class="col">
                    <input type="date" name="aniversarioResp" id="aniversarioResp" class="form-control w-25">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right">
                    <input class="btn btn-success" type="submit" value="Salvar e continuar">
                </div>
            </div>

        </form>

        <form id="bancoEmpresa" style="display:none;">
            <h3 class="mt-4">Banco</h3>
            <div class="divider"></div>

            <input type="hidden" name="empresa" id="bancoEmpresaID" value="">

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Banco</strong></div>
                <div class="col">
                    <select name="banco" id="banco" class="form-control w-25" onchange="selecionaBanco(this)">
                        <option select disabled>Selecione</option>
                        <option value="001">Banco do Brasil</option>
                        <option value="003">Banco da Amazônia</option>
                        <option value="004">Banco do Nordeste</option>
                        <option value="021">Banestes</option>
                        <option value="025">Banco Alfa</option>
                        <option value="027">Besc</option>
                        <option value="029">Banerj</option>
                        <option value="031">Banco Beg</option>
                        <option value="033">Banco Santander Banespa</option>
                        <option value="036">Banco Bem</option>
                        <option value="037">Banpará</option>
                        <option value="038">Banestado</option>
                        <option value="039">BEP</option>
                        <option value="040">Banco Cargill</option>
                        <option value="041">Banrisul</option>
                        <option value="044">BVA</option>
                        <option value="045">Banco Opportunity</option>
                        <option value="047">Banese</option>
                        <option value="062">Hipercard</option>
                        <option value="063">Ibibank</option>
                        <option value="065">Lemon Bank</option>
                        <option value="066">Banco Morgan Stanley Dean Witter</option>
                        <option value="069">BPN Brasil</option>
                        <option value="070">Banco de Brasília – BRB</option>
                        <option value="072">Banco Rural</option>
                        <option value="073">Banco Popular</option>
                        <option value="074">Banco J. Safra</option>
                        <option value="075">Banco CR2</option>
                        <option value="076">Banco KDB</option>
                        <option value="096">Banco BMF</option>
                        <option value="104">Caixa Econômica Federal</option>
                        <option value="107">Banco BBM</option>
                        <option value="116">Banco Único</option>
                        <option value="151">Nossa Caixa</option>
                        <option value="175">Banco Finasa</option>
                        <option value="184">Banco Itaú BBA</option>
                        <option value="204">American Express Bank</option>
                        <option value="208">Banco Pactual</option>
                        <option value="212">Banco Matone</option>
                        <option value="213">Banco Arbi</option>
                        <option value="214">Banco Dibens</option>
                        <option value="217">Banco Joh Deere</option>
                        <option value="218">Banco Bonsucesso</option>
                        <option value="222">Banco Calyon Brasil</option>
                        <option value="224">Banco Fibra</option>
                        <option value="225">Banco Brascan</option>
                        <option value="229">Banco Cruzeiro</option>
                        <option value="230">Unicard</option>
                        <option value="233">Banco GE Capital</option>
                        <option value="237">Bradesco</option>
                        <option value="241">Banco Clássico</option>
                        <option value="243">Banco Stock Máxima</option>
                        <option value="246">Banco ABC Brasil</option>
                        <option value="248">Banco Boavista Interatlântico</option>
                        <option value="249">Investcred Unibanco</option>
                        <option value="250">Banco Schahin</option>
                        <option value="252">Fininvest</option>
                        <option value="254">Paraná Banco</option>
                        <option value="263">Banco Cacique</option>
                        <option value="265">Banco Fator</option>
                        <option value="266">Banco Cédula</option>
                        <option value="300">Banco de la Nación Argentina</option>
                        <option value="318">Banco BMG</option>
                        <option value="320">Banco Industrial e Comercial</option>
                        <option value="356">ABN Amro Real</option>
                        <option value="341">Itau</option>
                        <option value="347">Sudameris</option>
                        <option value="351">Banco Santander</option>
                        <option value="353">Banco Santander Brasil</option>
                        <option value="366">Banco Societe Generale Brasil</option>
                        <option value="370">Banco WestLB</option>
                        <option value="376">JP Morgan</option>
                        <option value="389">Banco Mercantil do Brasil</option>
                        <option value="394">Banco Mercantil de Crédito</option>
                        <option value="399">HSBC</option>
                        <option value="409">Unibanco</option>
                        <option value="412">Banco Capital</option>
                        <option value="422">Banco Safra</option>
                        <option value="453">Banco Rural</option>
                        <option value="456">Banco Tokyo Mitsubishi UFJ</option>
                        <option value="464">Banco Sumitomo Mitsui Brasileiro</option>
                        <option value="477">Citibank</option>
                        <option value="479">Itaubank (antigo Bank Boston)</option>
                        <option value="487">Deutsche Bank</option>
                        <option value="488">Banco Morgan Guaranty</option>
                        <option value="492">Banco NMB Postbank</option>
                        <option value="494">Banco la República Oriental del Uruguay</option>
                        <option value="495">Banco La Provincia de Buenos Aires</option>
                        <option value="505">Banco Credit Suisse</option>
                        <option value="600">Banco Luso Brasileiro</option>
                        <option value="604">Banco Industrial</option>
                        <option value="610">Banco VR</option>
                        <option value="611">Banco Paulista</option>
                        <option value="612">Banco Guanabara</option>
                        <option value="613">Banco Pecunia</option>
                        <option value="623">Banco Panamericano</option>
                        <option value="626">Banco Ficsa</option>
                        <option value="630">Banco Intercap</option>
                        <option value="633">Banco Rendimento</option>
                        <option value="634">Banco Triângulo</option>
                        <option value="637">Banco Sofisa</option>
                        <option value="638">Banco Prosper</option>
                        <option value="643">Banco Pine</option>
                        <option value="652">Itaú Holding Financeira</option>
                        <option value="653">Banco Indusval</option>
                        <option value="654">Banco A.J. Renner</option>
                        <option value="655">Banco Votorantim</option>
                        <option value="707">Banco Daycoval</option>
                        <option value="719">Banif</option>
                        <option value="721">Banco Credibel</option>
                        <option value="734">Banco Gerdau</option>
                        <option value="735">Banco Pottencial</option>
                        <option value="738">Banco Morada</option>
                        <option value="739">Banco Galvão de Negócios</option>
                        <option value="740">Banco Barclays</option>
                        <option value="741">BRP</option>
                        <option value="743">Banco Semear</option>
                        <option value="745">Banco Citibank</option>
                        <option value="746">Banco Modal</option>
                        <option value="747">Banco Rabobank International</option>
                        <option value="748">Banco Cooperativo Sicredi</option>
                        <option value="749">Banco Simples</option>
                        <option value="751">Dresdner Bank</option>
                        <option value="752">BNP Paribas</option>
                        <option value="753">Banco Comercial Uruguai</option>
                        <option value="755">Banco Merrill Lynch</option>
                        <option value="756">Banco Cooperativo do Brasil</option>
                        <option value="757">KEB</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Número da agência</strong></div>
                <div class="col">
                    <input type="text" name="agencia" id="agencia" class="form-control w-25" maxlength="8">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Número da conta</strong></div>
                <div class="col">
                    <input type="text" name="conta" id="conta" class="form-control w-25" maxlength="10">
                </div>
            </div>

            <div class="row mb-3" id="caixaComplemento">
                    
            </div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Nome do responsável</strong></div>
                <div class="col">
                    <input type="text" name="responsavel" id="responsavel" class="form-control w-25" maxlength="120">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong>Documento do responsável</strong></div>
                <div class="col">
                    <input type="text" name="documento" id="documento" class="form-control w-25" maxlength="60">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-2 text-right">
                    <input class="btn btn-success" type="submit" value="Salvar e continuar">
                </div>
            </div>
        </form>

        <form id="docsEmpresa" style="display:none">
            <h3 class="mb-3">Documentos</h3>
            <div class="divider"></div>

            <div id="campDocs"></div>

            <div class="row mb-4">
                <div class="col-2 text-right">
                    <input class="btn btn-success" type="submit" value="Salvar e continuar">
                </div>
            </div>

        </form>

        <form id="habilitarEmpresa" style="display:none">
            <h3 class="mb-3">Pagamentos</h3>
            <div class="divider"></div>

            <div class="row mb-3">
                <div class="col-2 text-right"><strong><span><label for="habilitar">Habilitar</label></span></strong></div>
                <div class="col"><input type="checkbox" name="habilitar" id="habilitarPagamento"></div>
            </div>
        </form>

    </div>
</div>