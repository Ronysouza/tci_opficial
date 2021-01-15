<?php include('header.php'); ?>

<?php
if(isset($_POST['cmd'])){
    $cmd = $_POST['cmd'];

    if($cmd == "add"){
        $query = 'insert into tbl_agenda(responsavel,data,hora,compromisso) values(
            '.$_POST['responsavel'].',
            "'.$_POST['data'].'",
            "'.$_POST['hora'].'",
            "'.$_POST['compromisso'].'"
        );';
        $con->query($query);
        echo '<script>location.href="?novo&data='.$_GET['data'].'&s"</script>';
    }
    elseif($cmd == "edt"){
        $query = 'update tbl_agenda set
            responsavel = '.$_POST['responsavel'].',
            data = "'.$_POST['data'].'",
            hora = "'.$_POST['hora'].'",
            compromisso = "'.$_POST['compromisso'].'"
            where id = '.$_POST['id'].'
        ';
        $con->query($query);
        echo '<script>location.href="?novo&data='.$_GET['data'].'&s"</script>';            
    }
}
elseif(isset($_GET['del'])){
    $con->query('delete from tbl_agenda where id ='.$_GET['del']);
    echo '<script>location.href="?novo&data='.$_GET['data'].'&s"</script>';
}

?>
<script src="assets/scripts/agenda.js"></script>

<!-- cabeçalho da página -->
<div class="app-page-title">

    <div class="page-title-wrapper">

        <div class="page-title-heading">

            <div class="page-title-icon">
                <i class="fas fa-file-contract icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                <span>Cadastro de Contratos</span>
                <div class="page-title-subheading">
                    Campo para adição, remoção e edição de contratos
                </div>
            </div>

        </div>
        <div class="page-title-actions">

            <div class="d-inline-block dropdown">
                <button class="btn-shadow dropdown-toggle btn btn-info" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Ações
                </button>
                <div class="dropdown-menu dropdown-menu-right" tabindex="-1" role="menu" x_placement="bottom-end">
                    <ul class="nav flex-column">
                        <li class="nav-item">

                            <a class="nav-link text-dark" onclick="imprimir()">
                                Imprimir
                            </a>
                            <a class="nav-link text-dark" target="_blank" href="exp.php?tbl=clientes">
                                Exportar
                            </a>
                        
                        </li>
                    </ul>
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
                    <div class="row">

                        <div class="col">
                            <div id="calendario">
                                <?php
                                    if(isset($_GET['mes'])){
                                        $mes = strtotime($_GET['ano'].'/'.$_GET['mes'].'/01');
                                    }
                                    else{
                                        $mes = strtotime(date("Y/m/01"));
                                    }
                                    $sInicio = date("N",$mes);
                                    $dFim = date("t",$mes);
                                    $meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
                                    
                                    $nMes = date("m",$mes);
                                    $nAno = date("Y",$mes);
                                    $nDia = date("d");

                                    $pMes = 0;
                                    $pAno = 0;
                                    if($nMes + 1 > 12){
                                        $pMes = 1;
                                        $pAno = $nAno + 1;
                                    }
                                    else{
                                        $pMes = $nMes + 1;
                                        $pAno = $nAno;
                                    }

                                    $aMes = 0;
                                    $aAno = 0;
                                    if($nMes - 1 < 1){
                                        $aMes = 12;
                                        $aAno = $nAno - 1;
                                    }
                                    else{
                                        $aMes = $nMes - 1;
                                        $aAno = $nAno;
                                    }
                                ?>
                                
                                <div class="fc-toolbar fc-header-toolbar">
                                    <div class="fc-left">
                                        <div class="btn-group">
                                            <a href="?mes=<?php echo $aMes;?>&ano=<?php echo $aAno;?>">
                                                <button type="button" class="fc-prev-button btn btn-light mr-1" aria-label="prev"><span class="fa fa-chevron-left"></span></button>
                                            </a>
                                            <a href="?hoje"><button type="button" class="fc-today-button btn btn-light mr-1">Hoje</button></a>
                                            <a href="?mes=<?php echo $pMes;?>&ano=<?php echo $pAno;?>">
                                                <button type="button" class="fc-next-button btn btn-light" aria-label="next"><span class="fa fa-chevron-right"></span></button>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="fc-center">
                                        <h2><?php echo $meses[$nMes-1].' '.date('Y',$mes)?></h2>
                                    </div>
                                </div>

                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Domingo</th>
                                            <th>Segunda-feira</th>
                                            <th>Terça-feira</th>
                                            <th>Quarta-feira</th>
                                            <th>Quinta-feira</th>
                                            <th>Sexta-feira</th>
                                            <th>Sábado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cont = 1;
                                            while($cont <= $dFim){
                                                echo "<tr>";
                                                    for($i = 0; $i < 7; $i++){
                                                        if(($cont == 1 && $sInicio % 7 != $i) || $cont > $dFim){
                                                            echo '<td></td>';
                                                        }
                                                        else{
                                                            $resp = $con->query('select id from tbl_agenda where data = "'.date('Y-m',$mes)."-".$cont.'"');
                                                            $bg = $resp->num_rows > 0? 'bg-success text-white':'';
                                                            $selecionar = isset($_GET['hoje']) && $cont == intval(date('d'))? 'bg-info text-light':'';
                                                            echo '<td class="data '.$selecionar.' '.$bg.'" dia="'.$cont.'" mes="'.$nMes.'" ano="'.$nAno.'" onclick="selectData(this)">'.$cont++.'</td>';
                                                        }
                                                    }
                                                echo "<tr>";
                                            }
                                        
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    
                        <div class="col-4" id="jTblDia">
                            <?php require_once('core/ajax/agenda-dia.php');?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- fim conteúdo -->

<?php include('footer.php');?>

<!-- modal -->
<div class="modal show" tabindex="-1" role="dialog" id="mdl-cliente">
    <div class="modal-dialog modal-xg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agenda</h5>
            </div>
            <div class="modal-body">
                <form method="post" id="frmEnviar">
                    <div class="row">
                        <div class="col-4">
                            <?php
                                if(isset($_GET['edt'])){
                                    $resp = $con->query('select * from tbl_agenda where id = '.$_GET['edt']);
                                    $agenda = $resp->fetch_assoc();
                                }
                            ?>

                            <input type="hidden" value="<?php echo isset($_GET['edt'])?'edt':'add';?>" name="cmd">
                            <input type="hidden" value="<?php echo $_GET['edt'];?>" name="id" id="id">


                            <div class="row">
                                <div class="col">
                                    <label for="responsavel">Responsavel<span class="ml-2 text-danger">*</span></label>
                                    <select name="responsavel" id="responsavel" class="form-control" required>
                                        <?php
                                            $resp = $con->query('select id,razaoSocial_nome from tbl_clientes where tipoFuncionario = "on" or tipoTecnico = "on"');
                                            $cont = 0;
                                            while($row = $resp->fetch_assoc()){
                                                $select = (($cont++ == 0 && !isset($_GET['edt'])) || $row['id'] == $agenda['responsavel'])? 'selected':'';
                                                echo '<option value="'.$row['id'].'" '.$select.'>'.$row['razaoSocial_nome'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label for="data">Data<span class="ml-2 text-danger">*</span></label>
                                    <input type="date" class="form-control" name="data" id="data" value="<?php echo isset($agenda)?$agenda['data']:$_GET['data'];?>" required>
                                </div>
                                <div class="col">
                                    <label for="hora">Hora<span class="ml-2 text-danger">*</span></label>
                                    <input type="time" class="form-control" name="hora" id="hora" value="<?php echo $agenda['hora'];?>" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col">
                                    <label for="compromisso">Compromisso<span class="ml-2 text-danger">*</span></label>
                                    <textarea name="compromisso" id="compromisso" rows="10" class="form-control" style="resize:none;" required><?php echo $agenda['compromisso'];?></textarea>
                                </div>
                            </div>

                            
                            <div class="float-right mt-3">
                                <a href="?novo&data=<?php echo $_GET['data'];?>"><div class="btn btn-danger">Limpar</div></a>
                                <input id="needs-validation" class="btn btn-success" type="submit" value="salvar">
                            </div>

                        </div>

                        <div class="col border-left">
                            <div id="tblListDia">
                                
                                <?php
                                    $resp = $con->query('select * from tbl_agenda where data = "'.$_GET['data'].'" order by hora');
                                    while($row = $resp->fetch_assoc()){
                                        $responsavel = $con->query('select razaoSocial_nome from tbl_clientes where id = '.$row['responsavel'])->fetch_assoc()['razaoSocial_nome'];
                                        echo '
                                            <div class="row border-bottom pb-2 pt-2" style="display: flex !important;width: 100%">
                                                <div class="col-1 m-auto text-center"><a href="?edt='.$row['id'].'&data='.$_GET['data'].'" class="btn icon-gradient bg-happy-itmeo"><i class="fas fa-chevron-left"></i></a></div>
                                                <div class="col-1 m-auto">'.substr($row['hora'],0,5).'</div>
                                                <div class="col-4 m-auto">'.$responsavel.'</div>
                                                <div class="col">
                                                    <p>'.$row['compromisso'].'</p>
                                                </div>
                                                <div class="col-1 m-auto"><a href="?del='.$row['id'].'&data='.$_GET['data'].'"><i class="fas fa-trash-alt icon-gradient bg-danger"></i></a></div>
                                            </div>
                                        ';
                                    }
                                ?>
                            </div>
                                    
                        </div>

                    </div>       
                </form>

            </div>
            <div class="modal-footer">
                <p class="text-start"><span class="ml-2 text-danger">*</span> Campos obrigatórios</p>
                <button type="button" class="btn btn-secondary" onclick="location.href='?hoje'">Fechar</button>
                <!--<button type="button" class="btn btn-primary" onclick="enviar()"><?php echo isset($_GET['edt'])? 'Atualizar':'Salvar';?></button>-->
            </div>
        </div>
    </div>
</div>
<!-- fim modal -->

<div id="toast-container" class="toast-top-center">
    <div id="toast-success" class="toast toast-success" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Sucesso!</div>
    </div>
    <div id="toast-error" class="toast toast-error hidden" aria-live="polite" style="opacity: 0.899999;display:none;">
        <div class="toast-title">Erro!</div>
    </div>
    <?php
        if(isset($_GET['s']))
            echo "<script>$('#toast-success').fadeIn('slow').delay(1000).fadeOut('slow');</script>";
        else if(isset($_GET['e']))
            echo "<script>$('#toast-error').fadeIn('slow').delay(1000).fadeOut('slow');</script>";
    ?>
</div>

<?php 
    if(isset($_GET['edt']) || isset($_GET['novo'])) echo "<script>$('#mdl-cliente').modal()</script>"; 
?>