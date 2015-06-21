<?php

require_once ('dal.php');

class UserDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),

        'email' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),

        'passwordHashed' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'times' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT),
        
        'firstName' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'lastName' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'addressLine1' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'addressLine2' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'postalCode' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'city' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'country' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),

        'phone' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),

        'role' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        // can be
        // * Administrator
        // * Translator
        // * Visitor
        // * NewsLetter

        'emailStatus' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        // can be
        //  * Valid
        //  * NotValidYet

        'origin' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
        // origin
        //  * Register
        //  * Payment
        //  * External entry
    );
    var $keyName = 'id';
    var $tableSuffix = 'users';
}

?>