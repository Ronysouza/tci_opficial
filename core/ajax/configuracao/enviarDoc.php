<?
    require_once('../../../functions.php');

    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            //echo json_encode($con->query('select status from tbl_documentos where id = '.$_GET['id'])->fetch_assoc());
            $token = $con->query('select token from tbl_configuracao where id = '.$_GET['id'])->fetch_assoc()['token'];
            $temp = $juno->listDocumentos($token);
            foreach(array_keys($temp) as $item){
                $status = '';
                $sFlag = false;
                
                $consResp = $juno->consultarDocumentos($item,$token);

                if(isset($consResp->approvalStatus)){
                    switch($consResp->approvalStatus){
                        case 'AWAITING':
                            $status = '<i class="fas fa-cloud-upload-alt ml-3 mt-auto mb-auto text-success" style="display:none; font-size:24px;" id="check-'.$item.'"></i>';
                            $sFlag = 1;
                        break;
                        case 'VERIFYING':
                            $status = '<i class="fas fa-check ml-3 mt-auto mb-auto text-warning" style="font-size:24px;"></i>';
                            $sFlag = 1;
                        break;
                        case 'VERIFIED':
                            $status = '<i class="fas fa-check ml-3 mt-auto mb-auto text-success" style="font-size:24px;"></i>';
                            $sFlag = 1;
                        break;
                    }
                }
                else{
                    $status = '<i class="fas fa-cloud-upload-alt ml-3 mt-auto mb-auto text-success" style="display:none; font-size:24px;" id="check-'.$item.'"></i>';
                    $sFlag = 0;
                }
                echo '
                    <div class="row mb-3">
                        <div class="col-2 text-right"><strong>'.$temp[$item].'</strong></div>
                        <div class="col d-flex">
                            <div class="mt-auto mb-auto w-50 d-flex">
                                <div class="btn btn-dark w-25 d-flex" onclick="modalFoto(\''.$item.'\')">
                                    <i class="fas fa-camera mr-auto mt-auto mb-auto"></i>
                                    <strong class="m-auto">Foto</strong>
                                </div>
                                '.$status.'
                            </div>
                        </div>
                        <input type="hidden" value="" id="'.$item.'" name="'.$item.'">
                    </div>
                ';
            }

        break;
        case 'POST':
            $ret = [];
            foreach(array_keys($_POST) as $key){
                $loc = '../../../upload/'.$key.'.jpeg';
                
                $img = fopen( $loc, 'wb' ); 
                $data = explode(',',$_POST[$key]);
                fwrite($img, base64_decode( $data[ 1 ] ) );
                fclose( $img );
                
                $resp = $juno->enviarDocumentos($loc,$key)->approvalStatus;
                $con->query('insert into tbl_documentos(doc,status) values("'.$key.'","'.$resp.'")');
            }
            echo true;
        break;
    }
?>