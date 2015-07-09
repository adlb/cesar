<?php

class Gallery {
    var $mediaDal;

    var $fileType = array('jpg' => 'jpg', 'jpeg' => 'jpg', 'pdf' => 'pdf', 'png' => 'jpg', 'gif' => 'jpg');
    public $standardSize = array(480, 300);
    public $sizes = array(
        array(480, 300),
        array(300, 480),
        array(256, 160),
        array(160, 256)
    );
    var $thWidth = 160;
    var $thHeight = 100;
    var $targetPath = "medias/";

    function Gallery($mediaDal) {
        $this->mediaDal = $mediaDal;
    }

    function GetAll() {
        return $this->mediaDal->GetWhere(array());
    }

    function GetStandardSizedImages() {
        return $this->mediaDal->GetWhere(array('width' => $this->standardSize[0], 'height' => $this->standardSize[1]), array('name'=>true));
    }
    
    function TryGet($nameOrId, &$image) {
        if ($this->mediaDal->TryGet($nameOrId, $image))
            return true;
            
        $results = $this->mediaDal->GetWhere(array('name' => $nameOrId));
        if (count($results) == 0) 
            return false;
        
        $image = $results[0];
        return true;
    }
    
    function TrySaveAsNew($file, $width, $height, &$error, &$media) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $baseName = pathinfo($file['name'], PATHINFO_FILENAME);

        //Check that type is authorized
        if (!in_array($extension, array_keys($this->fileType))) {
            $error = "Bad Type, only support ".join(', ', array_keys($this->fileType)).'.';
            return false;
        }

        if ($file['error'] != UPLOAD_ERR_OK) {
            $error = 'Error while uploading (code:'.$file['error'].')';
            return false;
        }

        if ($file['size'] == 0) {
            $error = 'Size of file '.$file['name'].' is 0';
            return false;
        }

        $newExtension = $this->fileType[$extension];
        $newBaseName = $this->CreateNewFileName($baseName, $newExtension);

        if ($newExtension == 'pdf' && !$this->TrySavePDF($file, $newBaseName, $newExtension, $media, $error))
            return false;

        if ($newExtension != 'pdf' && !$this->TrySaveImage($file, $width, $height, $newBaseName, $newExtension, $media, $error))
            return false;

        if (!$this->mediaDal->TrySave($media)) {
            $error = 'Can\'t save '.$file['name'].' in database.';
            return false;
        }

        return true;
    }

    function Delete($id) {
        if (!$this->mediaDal->TryGet($id, $media)) {
            return;
        }
        if (file_exists($media['file']))
            unlink($media['file']);
        if (file_exists($media['thumb']))
            unlink($media['thumb']);
        $this->mediaDal->DeleteWhere(array('id' => $id));
    }

    private function TrySavePDF($file, $newBaseName, $newExtension, &$media, &$error) {
        $target = $this->targetPath.$newBaseName.'.'.$newExtension;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            $error = 'Error while moving '.$file['name'].' to its destination ('.$target.').';
            return false;
        }
        $media = array(
            'name' => $file['name'],
            'file' => $target,
            'thumb' => 'img/thumb_pdf.png'
        );
        return true;
    }

    private function TrySaveImage($file, $width, $height, $newBaseName, $newExtension, &$media, &$error) {
        $target = $this->targetPath.$newBaseName.'.'.$newExtension;
        $targetTh = $this->targetPath.$newBaseName.'_th.'.$newExtension;

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        switch ($extension) {
            case 'jpg':
                $source = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'png':
                $source = imagecreatefrompng($file['tmp_name']);
                break;
            case 'gif':
                $source = imagecreatefromgif($file['tmp_name']);
                break;
            default:
                $error = "Format not supported";
                return false;
            echo $source;
        }

        $this->ResizeAndCopy($source, $target, $width, $height);
        $this->ResizeAndCopy($source, $targetTh, $this->thWidth, $this->thHeight);

        $media = array(
            'name' => $file['name'],
            'width' => $width,
            'height' => $height,
            'file' => $target,
            'thumb' => $targetTh
        );

        return true;
    }

    private function ResizeAndCopy($source, $filename, $width, $height) {
        $w = imagesx($source);
        $h = imagesy($source);

        $destination = imagecreatetruecolor($width, $height);

        $ratio = min($w / $width, $h / $height);

        imagecopyresized(
            $destination,                              //resource $dst_image
            $source,                                   //resource $src_image
            0,                                         //int $dst_x
            0,                                         //int $dst_y
            ($w - $width * $ratio) / 2,                //int $src_x
            ($h - $height * $ratio) / 2,               //int $src_y
            $width,                                    //int $dst_w
            $height,                                   //int $dst_h
            $width * $ratio,                           //int $src_w
            $height * $ratio                           //int $src_h
        );
        
        imagejpeg($destination, $filename);
        imagedestroy($destination);
    }

    private function CreateNewFileName($baseName, $extension) {
        $alphabet = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
        );
        $newKey = strtr(trim($baseName), $alphabet);
        $newKey = strtolower(preg_replace("/\W+/", "-", $newKey));
        $newKey = substr($newKey, 0, 20);

        $new = $newKey;
        while (file_exists($this->targetPath.$newKey.'.'.$extension)) {
            $newKey = $new.substr(md5(uniqid(mt_rand(), true)), 0, 3);
        }
        return $newKey;
    }
}