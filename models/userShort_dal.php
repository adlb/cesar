<?php

require_once ('dal.php');

class UserShortDal extends Dal {
    var $fields = array(
		'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'email' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'passwordHashed' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'role' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
    );

    var $keyName = 'id';
    var $tableSuffix = 'users';
}

?>