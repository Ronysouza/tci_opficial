<?php


$uploaddir = 'Comprovantes/';
$uploadfile = $uploaddir . basename($_FILES['1_file']['name']);
var_dump($uploadfile);
echo '<pre>';
if (move_uploaded_file($_FILES['1_file']['tmp_name'], $uploadfile)) {
    echo "Arquivo válido e enviado com sucesso.\n";
} else {
    echo "Possível ataque de upload de arquivo!\n";
}

echo 'Aqui está mais informações de debug:';
print_r($_FILES);

print "</pre>";

//echo $_POST['cotacoa_vlr_euro'];

//print_r($_FILES);

?>