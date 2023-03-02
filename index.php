<?php

require __DIR__ . "/vendor/autoload.php";

use App\File\Upload;


if(isset($_FILES['arquivo'])){

    $obUpload = new Upload($_FILES['arquivo']);
    $obUpload->generateNewName();
    $sucesso = $obUpload->upload(__DIR__ . '/files', false);
    if($sucesso){
        echo "Arquivo ".$obUpload->getBasename()." enviado com sucesso";
        exit;
    }
    die("O arquivo não foi enviado");

}


require __DIR__ . "/templates/upload/formulario.phtml";