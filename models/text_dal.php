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
}

?>