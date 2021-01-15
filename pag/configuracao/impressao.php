<div class="row shadow rounded bg-white">
    <div class="col p-2 ml-2">
        <h3 class="mb-3 text-center">Impressora Etiqueta</h3>

        <form method="POST">
            <?$confImp = $con->query('select * from tbl_confImp')->fetch_all();?>

            <input type="hidden" name="cmd" value="impressora">

            <div class="row mb-3">
                <div class="col-4 d-flex">
                    <span class="mt-auto mb-auto">Configuração geral</span>
                </div>
                <div class="col">
                    <div class="row mb-3">
                        <div class="col">
                            <label>Alt. Etiqueta</label>
                            <input type="number" class="form-control" value="<?=$confImp[0][11]?>" name="geral[linha]">
                        </div>
                        <div class="col">
                            <label>Larg. Etiqueta</label>
                            <input type="number" class="form-control" value="<?=$confImp[0][12]?>" name="geral[coluna]">
                        </div>
                        <div class="col">
                            <label>Margens</label>
                            <input type="number" class="form-control" value="<?=$confImp[0][13]?>" name="geral[margem]">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Esp. linhas</label>
                            <input type="number" class="form-control" value="<?=$confImp[0][10]?>" name="geral[espacamento]">
                        </div>
                        <div class="col">
                            <label>Esp. etiqueta</label>
                            <input type="number" class="form-control" value="<?=$confImp[0][14]?>" name="geral[tEspacamento]">
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="text-center">
                <strong>Largura</strong>
            </div>
            <table class="table">
                <tr>
                    <th style="width:30%"></th>
                    <th>1ª Coluna</th>
                    <th>2ª Coluna</th>
                    <th>3ª Coluna</th>
                    <th>4ª Coluna</th>
                </tr>
                <tr>
                    <td>Campo código:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[codigo][]" value="<?=$confImp[0][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[codigo][]" value="<?=$confImp[1][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[codigo][]" value="<?=$confImp[2][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[codigo][]" value="<?=$confImp[3][3]?>"></td>
                </tr>
                <tr>
                    <td>Campo peso:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[peso][]" value="<?=$confImp[0][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[peso][]" value="<?=$confImp[1][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[peso][]" value="<?=$confImp[2][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[peso][]" value="<?=$confImp[3][4]?>"></td>
                </tr>
                <tr>
                    <td>Campo preço:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[preco][]" value="<?=$confImp[0][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[preco][]" value="<?=$confImp[1][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[preco][]" value="<?=$confImp[2][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[preco][]" value="<?=$confImp[3][5]?>"></td>
                </tr>
                <tr>
                    <td>Campo tamanho:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[tamanho][]" value="<?=$confImp[0][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[tamanho][]" value="<?=$confImp[1][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[tamanho][]" value="<?=$confImp[2][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[tamanho][]" value="<?=$confImp[3][6]?>"></td>
                </tr>
                <tr>
                    <td>Campo quantidade:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[quantidade][]" value="<?=$confImp[0][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[quantidade][]" value="<?=$confImp[1][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[quantidade][]" value="<?=$confImp[2][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[quantidade][]" value="<?=$confImp[3][7]?>"></td>
                </tr>
                <tr>
                    <td>Campo descrição:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[descricao][]" value="<?=$confImp[0][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[descricao][]" value="<?=$confImp[1][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[descricao][]" value="<?=$confImp[2][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[descricao][]" value="<?=$confImp[3][8]?>"></td>
                </tr>
                <tr>
                    <td>Campo código de barras:</td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[barras][]" value="<?=$confImp[0][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[barras][]" value="<?=$confImp[1][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[barras][]" value="<?=$confImp[2][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="larg[barras][]" value="<?=$confImp[3][9]?>"></td>
                </tr>
            </table>

            <div class="text-center">
                <strong>Altura</strong>
            </div>
            <table class="table">
                <tr>
                    <th style="width:30%"></th>
                    <th>1ª Coluna</th>
                    <th>2ª Coluna</th>
                    <th>3ª Coluna</th>
                    <th>4ª Coluna</th>
                </tr>
                <tr>
                    <td>Campo código:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[codigo][]" value="<?=$confImp[4][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[codigo][]" value="<?=$confImp[5][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[codigo][]" value="<?=$confImp[6][3]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[codigo][]" value="<?=$confImp[7][3]?>"></td>
                </tr>
                <tr>
                    <td>Campo peso:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[peso][]" value="<?=$confImp[4][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[peso][]" value="<?=$confImp[5][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[peso][]" value="<?=$confImp[6][4]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[peso][]" value="<?=$confImp[7][4]?>"></td>
                </tr>
                <tr>
                    <td>Campo preço:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[preco][]" value="<?=$confImp[4][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[preco][]" value="<?=$confImp[5][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[preco][]" value="<?=$confImp[6][5]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[preco][]" value="<?=$confImp[7][5]?>"></td>
                </tr>
                <tr>
                    <td>Campo tamanho:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[tamanho][]" value="<?=$confImp[4][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[tamanho][]" value="<?=$confImp[5][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[tamanho][]" value="<?=$confImp[6][6]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[tamanho][]" value="<?=$confImp[7][6]?>"></td>
                </tr>
                <tr>
                    <td>Campo quantidade:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[quantidade][]" value="<?=$confImp[4][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[quantidade][]" value="<?=$confImp[5][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[quantidade][]" value="<?=$confImp[6][7]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[quantidade][]" value="<?=$confImp[7][7]?>"></td>
                </tr>
                <tr>
                    <td>Campo descrição:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[descricao][]" value="<?=$confImp[4][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[descricao][]" value="<?=$confImp[5][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[descricao][]" value="<?=$confImp[6][8]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[descricao][]" value="<?=$confImp[7][8]?>"></td>
                </tr>
                <tr>
                    <td>Campo código de barras:</td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[barras][]" value="<?=$confImp[4][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[barras][]" value="<?=$confImp[5][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[barras][]" value="<?=$confImp[6][9]?>"></td>
                    <td><input type="number" class="form-control" step="0.01" name="alt[barras][]" value="<?=$confImp[7][9]?>"></td>
                </tr>
            </table>

            <input type="submit">
        </form>
    </div>

</div>