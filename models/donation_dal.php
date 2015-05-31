<?php

require_once ('dal.php');

class DonationDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        
        'userid' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT),
        'amount' => array('create' => 'float', 'bind' => PDO::PARAM_STR),
        'type' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        
        'email' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'firstName' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'lastName' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'addressLine1' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'addressLine2' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'postalCode' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'city' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'country' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'phone' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'externalCheckId' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        
        'dateInit' => array('create' => 'datetime', 'bind' => PDO::PARAM_STR),
        'dateValidation' => array('create' => 'datetime', 'bind' => PDO::PARAM_STR),
        
        'status' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
            //* promess
            //* cancelled
            //* received
            //* validated
            //* archived
            //* deleted
    );
    
    var $keyName = 'id';
    var $tableSuffix = "donations";
}

?>