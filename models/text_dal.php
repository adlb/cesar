<?php

require_once ('dal.php');

class TextDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'key' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'language' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'prefetch' => array('create' => 'BOOLEAN', 'bind' => PDO::PARAM_BOOL, 'key' => true),
        'text' => array('create' => 'TEXT', 'bind' => PDO::PARAM_STR),
        'nextText' => array('create' => 'TEXT', 'bind' => PDO::PARAM_STR),
        'usage' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
            //usage can be :
            // * grouped
            // * pureText
            // * decoratedText
        'textStatus' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
            //status can be :
            //  * ready : entered by an admin or validated by an admin means it is the same as textStatus
            //  * translationToBeValidated : first translation done but need to be validated by admin
            //  * translationToBeUpdated : need an update on translation
            //  * firstTranslationToBeValidated : first translation done but need to be validated by admin
            //  * toBeTranslated : no translation done
    );
    var $keyName = 'id';
    var $tableSuffix = "texts";
    
    function TryGetFromFile($key, $language, &$data) {
        $keyClean;
        
        if (substr($key, -8) == '_content') {
            $keyClean = substr($key, 0, -8);
        } else {
            $keyClean = $key;
        }
        $fileName = 'fixedArticles/'.$keyClean.'.'.$language.'.txt';
                
        if (!file_exists($fileName))
        {
            $fileName = 'fixedArticles/'.$keyClean.'.txt';
            if (!file_exists($fileName))
                return false;
        }
        
        if ($keyClean == $key) {
            $f = @fopen($fileName, 'r');
            if ($f == null)
                return false;
            $data = fgets($f);
            fclose($f);
            return $data != '';
        }
        
        $f = @fopen($fileName, 'r');
        if ($f == null)
            return false;
        $head = fgets($f);
        $result = '';
        while (($line = fgets($f)) !== false) {
            $result .= $line;
        }
        fclose($f);
        
        $data = $result;
        $data = str_replace("\r\n", "\n", $data);
        $data = str_replace("\r", "\n", $data);
        return true;
    }
}

?>