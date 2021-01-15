<?php

 

try{

  	$dsn = "mysql:dbname=indexerpcom_tci;host=localhost";
  	$dbUser = "indexerpcom_tci";
  	$dbPass = ';QT*JRknXgqG';


       /* $dsn = "mysql:dbname=indexerpcom_tci;host=localhost";
	  	$dbUser = "root";
	  	$dbPass = '';*/

   
   $pdo = new PDO($dsn,$dbUser,$dbPass);
    
     
global $pdo;


  }catch(PDOException $e){

  	echo "ERROR".$e->getMessage();

  } 


 
 
   







 
