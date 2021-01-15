<div class="bg-white border border-top-0">
    <div class="container pt-3 pb-3">
        
        <div class="row mb-3">
            <label for="tpAmb" class="col-4 text-right"><strong>Ambiente</strong></label>
            <div class="col">
                <select name="tpAmb" id="tpAmb" class="form-control w-25" readonly>
                    <option value="1" <?=$_CONF['debug'] == 0?'selected':''?>>Produção</option>
                    <option value="2" <?=$_CONF['debug'] == 1?'selected':''?>>Homologação</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="cNF" class="col-4 text-right"><strong>Código da NFe</strong></label>
            <div class="col">
                <input type="text" class="form-control w-50" name="cNF" id="cNF" value="<?=str_pad(rand(0,99999999),8,0,STR_PAD_LEFT)?>" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Natureza da operação</strong></label>
            <div class="col">
                <input type="text" class="form-control w-75" name="natOp" id="natOp" list="cfopList">
                <datalist id="cfopList">
                    <?
                        $resp = $con->query('select cfop,descricao from tbl_cfop');
                        while($row = $resp->fetch_assoc()){
                            echo '<option value="'.$row['cfop'].' - '.$row['descricao'].'">';
                        }
                    ?>
                </datalist>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Modelo</strong></label>
            <div class="col">
                <select class="form-control w-25" name="mod" id="mod">
                    <option value="55">NFe</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Série</strong></label>
            <div class="col">
                <input type="text" class="form-control w-50" name="serie" id="serie">
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Número da NFe</strong></label>
            <div class="col">
                <input type="text" class="form-control w-50" name="nNF" id="nNF">
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Data emissão</strong></label>
            <div class="col">
                <div class="input-group w-50">
                    <input type="date" class="form-control w-25" name="dEmi" id="dEmi" value="<?=date('Y-m-d')?>" readonly>
                    <input type="time" class="form-control w-25" name="hEmi" id="hEmi" value="<?=date('H:i:s')?>" readonly>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Data entrada/saída</strong></label>
            <div class="col">
                <div class="input-group w-50">
                    <input type="date" class="form-control w-25" name="dSaiEnt" id="dSaiEnt" value="<?=date('Y-m-d')?>">
                    <input type="time" class="form-control w-25" name="hSaiEnt" id="hSaiEnt" value="<?=date('H:i:s')?>">
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Tipo da NFe</strong></label>
            <div class="col">
                <Select type="text" class="form-control w-25" name="tpNF" id="tpNF">
                    <option value="1">Saida</option>
                    <option value="0">Entrada</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Tipo da emissão</strong></label>
            <div class="col">
                <select class="form-control w-25" name="tpEmis" id="tpEmis">
                        <option value="1" selected>Normal</option>
                        <option value="2" disabled>Contingência FS-IA</option>
                        <option value="3" disabled>Contingência SCAN</option>
                        <option value="4" disabled>Contingência DPEC</option>
                        <option value="5" disabled>Contingência FS-DA</option>
                        <option value="6" disabled>Contingência SVC-AN</option>
                        <option value="7" disabled>Contingência SVC-RS</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Finalizadade da NFe</strong></label>
            <div class="col">
                <select class="form-control w-50" name="finNFe" id="finNFe">
                    <option value="1">Normal</option>
                    <option value="2">Suplementar</option>
                    <option value="3">Ajuste</option>
                    <option value="4">Devolução</option>
                <select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Consumidor final</strong></label>
            <div class="col">
                <select class="form-control w-50" name="indFinal" id="indFinal">
                    <option value="0" selected>Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label for="" class="col-4 text-right"><strong>Presença do consumidor</strong></label>
            <div class="col">
                <select class="form-control w-50" name="indPres" id="indPres">
                    <option value="0" selected>Não se aplica</option>
                    <option value="1">Operação presencial</option>
                    <option value="2">Operação pela internet</option>
                    <option value="3">Operação por tele venda</option>
                    <option value="4">Operação com entrega a domicílio</option>
                    <option value="5">Presencial, mas fora do estabelecimento</option>
                    <option value="9">Outros</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="idDest" id="idDest" value="1">
        <input type="hidden" name="cMunFG" id="cMunFG" value="4306452">
        <input type="hidden" name="tpImp" id="tpImp" value="1">
        <input type="hidden" name="procEmi" id="procEmi" value="0">
        <input type="hidden" name="verProc" id="verProc" value="Index 1.0">

    </div>
</div>