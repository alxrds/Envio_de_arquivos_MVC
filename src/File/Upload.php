<?php

namespace App\file;

class Upload
{
    private $name;
    private $extension;
    private $type;
    private $tmpName;
    private $error;
    private $size;
    private $duplicates = 0;

    public function __construct($file)
    {
        $this->type = $file['type'];
        $this->tmpName = $file['tmp_name'];
        $this->error = $file['error'];
        $this->size = $file['size'];

        $info = pathinfo($file['name']);
        $this->name = $info['filename'];
        $this->extension = $info['extension'];
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function generateNewName()
    {
        $this->name = time().''.rand(100000, 999999).'-'.uniqid();
    }

    public function getBasename()
    {
        $extension = strlen($this->extension) ? '.'.$this->extension : '';
        $duplicates = $this->duplicates > 0 ? '-' .$this->duplicates : '';
        return $this->name.$duplicates.$extension;
    }

    private function getPossibleBasename($dir, $overwrite)
    {
        if($overwrite){
            return $this->getBasename();
        } 

        $basename = $this->getBasename();
        if(!file_exists($dir. '/' .$basename)){
            return $basename;
        }
        
        $this->duplicates++;

        return $this->getPossibleBasename($dir, $overwrite);
    }

    public function upload($dir, $overwrite = true)
    {
        if($this->error != 0){
            return false;
        } 
        $path = $dir. "/" .$this->getPossibleBasename($dir, $overwrite);

        return move_uploaded_file($this->tmpName, $path);
    }

    public static function createMultipleUpload($files)
    {
        $uploads = [];
        foreach($files['name'] as $key => $value){
            $file = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];
            $uploads[] = new Upload($file);
        }
        return $uploads;
    }

}