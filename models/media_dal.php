<?php

require_once ('dal.php');

class MediaDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'name' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'file' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'thumb' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
    );
    var $keyName = 'id';
    var $tableSuffix = "medias";
}

?>