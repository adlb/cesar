<?php

require_once ('dal.php');

class TextShortDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'key' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'language' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'prefetch' => array('create' => 'BOOLEAN', 'bind' => PDO::PARAM_BOOL, 'key' => true),
        'usage' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
            //usage can be :
            // * grouped
            // * pureText
            // * decoratedText
        'textStatus' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
            //status can be :
            //  * ready : entered by an admin or validated by an admin means it is the same as textStatus
            //  * notValidated : traduction done but need to be validated by admin
            //  * notTranslated : no traduction done or need an update on traduction
    );
    var $keyName = 'id';
    var $tableSuffix = "texts";
}

?>