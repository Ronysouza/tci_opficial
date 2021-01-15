<?php
#$con = new mysqli("localhost",'indexerpcom_catavento','HD,[98i3(3oC',"indexerpcom_catavento");
#$con = new mysqli('localhost','root','','indexerpcom_catavento2');

$servername = 'localhost';
$username   = 'indexerpcom_tci';
$password   = ';QT*JRknXgqG';
$database   = 'indexerpcom_tci';
 
/* $servername = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'indexerpcom_tci';*/
 

$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
}
echo "<!-- ". $con->host_info . " -->";

?>
