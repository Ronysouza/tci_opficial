<?

use NFePHP\NFe\Make;
use NFePHP\NFe\Complements;

class Nfe
{
    # armazena a classe que gera o XML para assinatura
    private $nfe;
    # armazena o status de produção ou homologação
    private $debug;
    # conexão com o banco de dados
    private $con;
    # XML gerado para assinatura
    private $xml;
    # JSON com informações para a assinatura do XML
    private $configJSON;
    # armazena as informações do certificado
    private $certificado;
    # armazena o XML assinado no sefaz
    private $xmlAssinado;
    # armazena o recibo da autorização
    private $reciboXML;
    # armazena protocolo do status
    private $protocoloStatus;
    # armazena xml protocolado
    private $xmlProtocolado;

    function __construct($con,$debug=true){
        $this->nfe = new Make();
        
        $this->debug = false;
        $this->con = $con;

        $this->configJSON = json_encode(array(
            'atualizacao' => date('Y-m-d h:i:s'),
            'tpAmb' => 2,
            'razaosocial' => 'RAZAO SOCIAL DO EMISSOR',
            'cnpj' => '32279123000108',
            'ie' => '2650006093',
            'siglaUF' => 'RS',
            'schemes' => 'core/nfe/schemes',
            'versao' => '4.00'
        ));

        $resp = $con->query('select certificado,certSenha from tbl_configuracao where id = '.$_COOKIE['empresa'])->fetch_assoc();
        $this->certificado = NFePHP\Common\Certificate::readPfx(file_get_contents('upload/certificado/'.$resp['certificado']),$resp['certSenha']);

    }

    # -- INFORMAÇÕES DO EMITENTE -- #
    private function infEmit(){
        $resp = $this->con->query('select razao_social,ie,crt,cnpj,endereco,numero,bairro,cidade,estado,ibge,cep from tbl_configuracao where id = '.$_COOKIE['empresa'])->fetch_assoc();

        // informação do emitente
        $tagemit = (object) array(
            'xNome' => $resp['razao_social'], // nome da empresa
            'IE' => str_replace('/','',$resp['ie']), // inscrição estadual da empresa
            'CRT' => $resp['crt'], // código de regime tributário: 1 para simples, 2 pra excesso sublime de receita bruta, 3 regime normal
            'CNPJ' => str_replace(array('/','.','-'),'',$resp['cnpj']) // CNPJ da empresa
        );
        $this->nfe->tagemit($tagemit);


        // informação de endereço do emitente
        $tagenderEmit = (object) array(
            'xLgr' => $resp['endereco'], // nome da rua do emitente
            'nro' => $resp['numero'], // número da casa do emitente
            'xBairro' => $resp['bairro'], // nome do bairro
            'cMun' => $resp['ibge'], // código do município no IBGE
            'xMun' => $resp['cidade'], // nome do município
            'UF' => $resp['estado'], // unidade federativa
            'CEP' => str_replace('-','',$resp['cep']), // CEP
            'cPais' => '1058', // código do pais
            'xPais' => 'BRASIL' // nome do pais
        );
        $this->nfe->tagenderEmit($tagenderEmit);
    }



    # -- INFORMAÇÕES DO DESTINATÁRIO -- #
    private function infDest($cFinal = false,$cliente = 24){
        // ie, ibge, pais, codigo pais
        $resp = $this->con->query('select razaoSocial_nome,cnpj_cpf,logradouro,numero,bairro,cidade,estado,cep,ie,ibge from tbl_clientes where id = '.$cliente)->fetch_assoc();
        $resp['pais'] = 'BRASIL';
        $resp['cPais'] = '1058';

        // informação do destinatário
        $tagdest = (object) array(
            'xNome' => $resp['razaoSocial_nome'], // nome do destinatário 
            'indIEDest' => $resp['ie'] == '' && $cFinal?2:1 // caso 1 é obrigatório informar a IE, caso 2
        );

        if($cFinal){
            $tagdest->CPF = str_replace(array('.','-'),'',$resp['cnpj_cpf']); // CPF do cliente
        }
        else{
            $tagdest->CNPJ = str_replace(array('.','-','/'),'',$resp['cnpj_cpf']); // cnpj do cliente
            
            if($resp['ie'] != ''&& $cFinal)
                $tagdest->IE = $resp['ie']; // obrigatório apenas se indIEDest for = 1
        }

        $this->nfe->tagdest($tagdest);

        // informação de endereço do destinatário
        $tagenderDest = (object) array(
            'xLgr' => $resp['logradouro'], // nome da rua do destinatário
            'nro' => $resp['numero'], // número da casa do destinatário
            'xBairro' => $resp['bairro'], // nome do bairro
            'cMun' => $resp['ibge'], // código do município no IBGE
            'xMun' => $resp['cidade'], // nome do município
            'UF' => $resp['estado'], // unidade federativa
            'CEP' => str_replace('-','',$resp['cep']), // CEP
            'cPais' => $resp['cPais'], // código do pais
            'xPais' => $resp['pais'] // nome do pais
        );
        $this->nfe->tagenderDest($tagenderDest);
    }



    # -- INFORMAÇÃO DOS ITENS -- # 
    private function infItens($cont = 1,$item){
        $query = '
        select A.id as idProd, A.nome as xProd,A.*, B.*, C.simbolo, D.codigo as cst_csosn, D.simples as regGeral from tbl_produtos A 
        inner join tbl_classificacaoFiscal B on B.id = A.classificacaoFiscal 
        inner join tbl_unidades C on C.id = A.unidadeEstoque
        left join tbl_cst D on D.id = B.cst  
        where A.id = '.$item['id'];

        $prod = $this->con->query($query)->fetch_assoc();

        var_dump($prod,'<br><br>');

        #exit();

        // informação sobre o produto N
        $tagprod = (object) array(
            'item' => $cont, // contador de itens
            'cEAN' => strpos($prod['codBarras'],'00000') == 0?'SEM GTIN':$prod['codBarras'], // código de barras
            'cEANTrib' => strpos($prod['codBarras'],'00000') == 0?'SEM GTIN':$prod['codBarras'], // código de barras
            'cProd' => $prod['idProd'], // código do produto
            'xProd' => $prod['xProd'], // nome do produto
            'NCM' => $prod['ncm'], // NCM do produto 
            'CFOP' => $item['cfop'], // CFOP do produto
            'uCom' => $prod['simbolo'], // unidade comercial
            'qCom' => number_format($item['quantidade'],4,'.',''), // quantidade comercial
            'vUnCom' => number_format($item['valor'],10,'.',''), // valor unitário
            'vProd' => number_format($item['quantidade']*$item['valor'],2,'.',''), // valor total do produto
            'uTrib' => $prod['simbolo'], // unidade tibutável 
            'qTrib' => number_format($item['quantidade'],4,'.',''), // quantidade tributável
            'vUnTrib' => number_format($item['valor'],10,'.',''), // valor unitário tributável
            'indTot' => 1
        );
        $this->nfe->tagprod($tagprod);

        // imposto do produto N
        $tagimposto = (object) array(
            'item' => $cont, // id do item N
            'vTotTrib' => 210.00, // valor tota a ser tributado
        );
        $this->nfe->tagimposto($tagimposto);

        if($prod['regGeral'] == 0){
            // ICMS do produto N
            $tagICMS = (object) array(
                'item' => $cont, // id do item N
                'orig' => $prod['origem'], // origem do produto
                'CST' => $prod['cst_csosn'], // CST do produto
                'modBC' => 0, // 
                'vBC' => '0', // valor de base de calculo
                'pICMS' => '0', // porcentagem de icms do produto
                'vICMS' => 0.00 // valor do icms do produto
            );
            $this->nfe->tagICMS($tagICMS);
        }
        else{
            // ICMS simples nacional do produto N
            $tagICMSSN = (object) array(
                'item' => $cont, // id do item N
                'orig' => $prod['origem'], // origem do produto
                'CSOSN' => $prod['cst_csosn'], //
                'pCredSN' => 0.00, // crédito SN
                'pCredICMSSN' => 0.00 // crédito ICMS SN
            );
            $this->nfe->tagICMSSN($tagICMSSN);
        }

        /*// IPI do produto N
        $tagIPI = (object) array(
            'item' => 1, // id do item N
            'cEnq' => 999, // código do ipi
            'CST' => $prod['cst_ipi'], // código do CST
            'vIPI' => 0, // valor do IPI
            'vBC' => 0, // valor da base de cálculo
            'pIPI' => 0, //
        );
        $this->nfe->tagIPI($tagIPI);*/

        // PIS do produto N
        $tagPIS = (object) array(
            'item' => $cont, // id do item N
            'CST' => $prod['cst_pis'], // código do CST
            'vBC' => $item['bc'], // valor da base de cálculo
            'pPIS' => 0, //
            'vPIS' => 0, // valor do PIS
        );
        $this->nfe->tagPIS($tagPIS);

        /*// CONFINS ST do produto N
        $tagCOFINSST = (object) array(
            'item' => 1, // id do item N
            'vCOFINS' => 0, // valor do cofins
            'vBC' => 0, // valor da base de calculo
            'pCOFINS' => 0, //
        );
        $this->nfe->tagCOFINSST($tagCOFINSST);*/

        // COFINS do produto N
        $tagCOFINS = (object) array(
            'item' => $cont, // id do item N
            'CST' => $prod['cst_cofins'], // código do CST
            'vBC' => $item['bc'], // valor da base de cálculo
            'pCOFINS' => 0.00, //
            'vCOFINS' => 0 // valor do COFINS
            #'qBCProd' => 0, //
            #'vAliqProd' => 0
        );
        $this->nfe->tagCOFINS($tagCOFINS);
    }



    # -- INFORMAÇÃO DO ICMS -- #
    private function infCMS($icms){
        // ICMS total
        $tagICMSTot = (object) array(
            'vBC' => '0.00', // valor da base de calculos
            'vICMS' => 0.00, // valor total do ICMS
            'vICMSDeson' => 0.00, //
            'vBCST' => 0.00, // valor da base de calculo do ST
            'vST' => 0.00, // valor ST
            'vProd' => number_format($icms['vProd'],2,'.',''), // valor do produto
            'vFrete' => 0.00, // valor do frete
            'vSeg' => 0.00, // valor do seguro
            'vDesc' => 0.00, // valor do desconto
            'vII' => 0.00, // valor do II
            'vIPI' => 0.00, // valor do IPI
            'vPIS' => 0.00, // valor do PIS
            'vCOFINS' => 0.00, // valor do COFINS
            'vOutro' => 0.00, // valor do outros
            'vNF' => number_format($icms['vNF'],2,'.',''), // valor final da NF total de produtos mais total de impostos
            'vTotTrib' => 210.00 // valor total tributavel
        );
        $this->nfe->tagICMSTot($tagICMSTot);
    }



    # -- INFORMAÇÕES DO TRANSPORTE -- #
    private function infTransp($transp){
        // Informações do transporte
        $tagtransp = (object) array(
            'modFrete' => $transp // modalidade do frete: 0 frete remetente, 1 frete destinatário, 2 frete terceiros, 3 próprio remetente, 4 próprio destinatário, 9 sem transporte
        );
        $this->nfe->tagtransp($tagtransp);
    }



    # -- INFORMAÇÕES DO VOLUME -- #
    private function infVol($data=null){
        /*$data = array(
            'item' => '',

        );*/

        // informações do volume do item N
        $tagvol = (object) array(
            #'item' => 1, // código do produto N
            'qVol' => 0, // quantidade do volume
            'esp' => 'caixa' //
            #'marca' => 'OLX', //
            #'nVol' => '11111', //
            #'pesoL' => 2000.000, // peso liquido do produto
            #'pesoB' => 2000.000, // peso bruto do produto
        );
        $this->nfe->tagvol($tagvol);
    }



    # -- INFORMAÇÕES DO PAGAMENTO -- #
    private function infPag(){
        // informações da fatura
        $tagfat = (object) array(
            'nFat' => '0000004', // numero da fatura
            'vOrig' => 5000.00, // valor original da fatura
            'vLiq' => 5000.00 // valor liquido da fatura
        );
        $this->nfe->tagfat($tagfat);

        /*// informações de parcelas
        $tagdup = (object) array(
            'nDup' => '001', // número da parcela
            'dVenc' => date('Y-m-d'), // data do vencimento da parcela
            'vDup' => 11.03, // valor da parcela
        );
        $this->nfe->tagdup($tagdup);*/

        // informação do troco
        $tagpag = (object) array(
            'vTroco' => 0 // valor do troco
        );
        $this->nfe->tagpag($tagpag);

        // informação do pagamento
        $tagdetPag = (object) array(
            'indPAg' => 0, // indicador da forma de pagamento: 0 para pagamento à vista, 1 para pagamento percelado
            'tPag' => '99', // tipo de pagamento: 01 para dinheiro, 02 por cheque, 03 por cartão de crédito, 04 para débito, 05 para crédito loja, 10 para vale alimentação, 11 para vale refeição, 12 para vale presente, 13 para vale combustivel, 15 para boleto, 90 sem pagamento, 99 outros
            'vPag' => 5000.00 // valor do pagamento
        );
        $this->nfe->tagdetPag($tagdetPag);
    }



    
    public function gerarXML(){
        // cabeçalho da nota
        $taginfNFe = (object) array(
            'versao' => '4.00', // versão do layout
            'Id' => null, // ID da nota, passar null para gerar automaticamente
            'pk_nItem' => null // deixar sempre como null
        );
        $this->nfe->taginfNFe($taginfNFe);

        // informações da nota
        $tagide = (object) array(
            'cUF' => 43, // código da unidade federativa 
            'cNF' => '83851397', // numero aleatório gerado para cada nfe
            'natOp' => 'COMPRA PARA INDUSTRIALIZACAO', // natureza da operação: venda, compra, transferência, devolução, importação, consignação, remessa
            'mod' => 55, // modelo da nota: 55 para NFe e 65 para NFCe
            'serie' => 1, // série da nota
            'nNF' => 10, // número da nota
            'dhEmi' => date('c'), // data e hora da emissão 
            'dhSaiEnt' => date('c'), // data e hora da entrada ou saida
            'tpNF' => 1, // tipo da nota: 1 para saida e 0 para entrada
            'idDest' => 1, // id do destino
            'cMunFG' => 4306452, //código IBGE da cidade de emissão
            'tpImp' => 1, // tipo de impressão
            'tpEmis' => 1, // tipo de emissão: 1 = Emissão normal (não em contingência), 2 = Contingência FS-IA, com impressão do DANFE em formulário de segurança, 3 = Contingência SCAN (Sistema de Contingência do Ambiente Nacional), 4 = Contingência DPEC (Declaração Prévia da Emissão em Contingência), 5 = Contingência FS-DA, com impressão do DANFE em formulário de segurança, 6 = Contingência SVC-AN (SEFAZ Virtual de Contingência do AN), 7 = Contingência SVC-RS (SEFAZ Virtual de Contingência do RS)   
            'tpAmb' => 2, // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            'finNFe' => 1, // finalidade da NFe: 1 normal, 2 suplementar, 3 ajuste, 4 devolução 
            'indFinal' => 1, // consumidor final: 0 para não, 1 para sim
            'indPres' =>  0, // indica presença do consumidor no local da compra: 0 não se aplica, 1 operação presencial, 2 operação pela internet, 3 operação por televenda, 4 operação com entrega a domicilio, 5 presencial, mas fora do estabelecimento, 9 outros
            'procEmi' => '0',
            'verProc' => 'IndexNFe 2.0'
        );
        $this->nfe->tagide($tagide);

        $this->infEmit();
        $this->infDest(true,24);

        $itens = array(
            array('id' => 518,'quantidade' => 2000,'valor' => 2.5,'cfop' => '5102','bc' => 5000),
            array('id' => 519,'quantidade' => 2000,'valor' => 2.5,'cfop' => '5102','bc' => 5000)
        );
        $vNF = 0;
        for($i = 0; $i < sizeof($itens); $i++){
            $this->infItens($i+1,$itens[$i]);
            $vNF += $itens[$i]['quantidade'] * $itens[$i]['valor'];
        }

        $icms = array(
            'vProd' => $vNF,
            'vNF' => $vNF
        );
        $this->infCMS($icms);

        $this->infTransp(0);

        $this->infVol();
        $this->infPag();
        
        try{
            $this->xml = $this->nfe->getXML();
            return array('status' => 1,'xml' => $this->xml);
        }catch(Exception $e){
            return array('status' => -1 ,'erro' => $e->getMessage(),$this->nfe->getErrors());
        }

    }

    # -- REA
    public function assinarXML($nota = false){
        $xml = $nota? $nota:$this->xml;

        $tools = new NFePHP\NFe\Tools($this->configJSON,$this->certificado);
        try{
            $this->xmlAssinado = $tools->signNFe($xml);
            return array('status' => 1,'xml' => $this->xmlAssinado);
        }catch(Exception $e){
            return array('status' => -1,'erro' => $e->getMessage());
        }
    }

    public function enviarLote($lote = false){
        $tools = new NFePHP\NFe\Tools($this->configJSON,$this->certificado);
        $st = new NFePHP\NFe\Common\Standardize();

        try{
            $idLote = str_pad(1,15,'0',STR_PAD_LEFT);

            if($lote)
                $resp = $tools->sefazEnviaLote($lote,$idLote);
            else
                $resp = $tools->sefazEnviaLote([$this->xmlAssinado],$idLote);

            $std = $st->toStd($resp);
            if($std->cStat != 103){
                return array('status' => -2, 'erro' => '['.$std->cStat.'] '.$std->xMotivo);
            }

            $this->reciboXML = $std->infRec->nRec;

            return array('status' => 1,'recibo' => $this->reciboXML);

        }catch(Exception $e){
            return array('status' => -1,'erro' => $e->getMessage());
        }
    }

    public function consultarRecibo($recibo = false){
        $tools = new NFePHP\NFe\Tools($this->configJSON,$this->certificado);
        try{
            if($recibo)
                $this->protocoloStatus = $tools->sefazConsultaRecibo($recibo);
            else
                $this->protocoloStatus = $tools->sefazConsultaRecibo($this->reciboXML);

            return array('status' => 1,'protocolo' => $this->protocoloStatus);

        }catch(Exception $e){
            return array('status' => -1,'erro' => $e->getMessage());
        }
    }

    public function finProcesso($protocolo = false){
        $request = $this->xmlAssinado;
        $response = $this->protocoloStatus;

        try{
            $this->xmlProtocolado = Complements::toAuthorize($request,$response);
            return array('status' => 1,'xml' => $this->xmlProtocolado);
        }catch(Exception $e){
            return array('status' => -1,'erro' => $e->getMessage());
        }
    }

}

?>