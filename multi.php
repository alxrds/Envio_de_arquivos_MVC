<?php

require __DIR__ . "/vendor/autoload.php";

use App\File\Upload;


if(isset($_FILES['arquivo'])){

    $uploads = Upload::createMultipleUpload($_FILES['arquivo']);

    foreach($uploads as $obUpload){
        $obUpload->generateNewName();
        $sucesso = $obUpload->upload(__DIR__ . '/files', false);
        if($sucesso){
            echo "Arquivo <strong>".$obUpload->getBasename()."</strong> enviado com sucesso <br>";
            continue;
        }
        echo "O arquivo não foi enviado <br>";
    }
    exit;

}

require __DIR__ . "/templates/upload/formulario-multi.phtml";