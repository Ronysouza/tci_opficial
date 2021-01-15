<?php
   
   require 'bancoPDO.php';
    $array = array();    
      
    

    if(isset($_GET['nome']) && !empty($_GET['nome'])){
 $nome = $_GET['nome']; 


    $mysql = "SELECT id,razaoSocial_nome FROM tbl_clientes  WHERE tipoCliente='on'  AND razaoSocial_nome  LIKE '$nome%' order by razaoSocial_nome asc  ";

     
     
     $mysql = $pdo->query($mysql);

     if($mysql->rowCount() > 0){

        $array = $mysql->fetchAll();      
                
              
             echo json_encode($array);       
        


     }

    }  


   if(isset($_GET['id_cliente_resul']) && !empty($_GET['id_cliente_resul'])){
 $id_cliente = $_GET['id_cliente_resul']; 


    $mysql = "SELECT tbl_clientes.id,tbl_clientes.razaoSocial_nome FROM tbl_clientes INNER JOIN tbl_recebimentos ON tbl_recebimentos.id_cliente = tbl_clientes.id WHERE tbl_recebimentos.id_cliente = '$id_cliente' GROUP BY tbl_clientes.id  ";

     
     
     $mysql = $pdo->query($mysql);

     if($mysql->rowCount() > 0){

        $array = $mysql->fetchAll();      
                
              
             echo json_encode($array);     
          
     }

    }  

     if(isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])){
 $id_cliente = $_GET['id_cliente']; 

 

    $mysql = "SELECT tbl_clientes.razaoSocial_nome,tbl_servicos.nome ,tbl_clientes.id,tbl_recebimentos.id_cliente,tbl_recebimentos.valor_real,tbl_recebimentos.id_moeda,tbl_recebimentos.id_recebimento,tbl_recebimentos.data_cotacao_euro FROM tbl_clientes INNER JOIN tbl_recebimentos ON tbl_clientes.id =  tbl_recebimentos.id_cliente INNER JOIN tbl_servicos ON tbl_servicos.id = tbl_recebimentos.id_servico WHERE tbl_recebimentos.id_cliente = '$id_cliente' ORDER BY tbl_recebimentos.id_recebimento DESC ";

     
     
     $mysql = $pdo->query($mysql);

     if($mysql->rowCount() > 0){

        $array = $mysql->fetchAll();      
                
              
           

             echo json_encode($array);       
        


     }

    }  

 

 if(isset($_GET['id_receb_cliente']) && !empty($_GET['id_receb_cliente'])){
     $id_cliente = $_GET['id_receb_cliente'];
     $data_01 = $_GET['data_01'];
     $data_02 = $_GET['data_02'];
       
       $array_receb = array();
      // $array_resultado = array();

        $mysql = "SELECT id_recebimento FROM tbl_recebimentos WHERE id_cliente ='$id_cliente' ";
         $mysql = $pdo->query($mysql);
                  
                  if($mysql->rowCount() > 0){
                          
                        $array_receb = $mysql->fetchAll();
                         
                          foreach($array_receb as $res){
                             $id_re = $res['id_recebimento'];

      $mysql_r = "SELECT * FROM tbl_parcelas_recebimentos WHERE data_parcela BETWEEN '$data_01' AND '$data_02' AND id_recebimento = '$id_re' AND ind_pago = '0'  ";
                                $mysql_r = $pdo->query($mysql_r);
                                  
                                  if($mysql_r->rowCount() > 0){
                                     
                                        $array_resultado[] = $mysql_r->fetchAll();

                                           
                                  }

                                  
                                 
                          }   

                   
                     
                          echo json_encode($array_resultado) ;

     
                  }

    
 }


    

  

  if(isset($_GET['id_receb']) && !empty($_GET['id_receb'])){
 $id_receb = $_GET['id_receb']; 

 $array = array();
    $mysql = "SELECT * FROM tbl_parcelas_recebimentos  WHERE id_recebimento = '$id_receb' AND ind_pago = 0";

     
     
     $mysql = $pdo->query($mysql);

     if($mysql->rowCount() > 0){

        $array = $mysql->fetchAll();      
             

              
             echo json_encode($array);       
     

     }  

    } 



if(isset($_GET['id_recebido']) && !empty($_GET['id_recebido'])){





$id_recebi = $_GET['id_recebido']; 

 $array = array();
    $mysql = "SELECT * FROM tbl_parcelas_recebimentos  WHERE id_recebimento = '$id_recebi' AND ind_pago = 1";

     
     
     $mysql = $pdo->query($mysql);

     if($mysql->rowCount() > 0){

        $array = $mysql->fetchAll();      
             

              
             echo json_encode($array);       
     

     }  









}


    





 

 ?>  