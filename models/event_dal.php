<?php

require_once ('dal.php');

class EventDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'userId' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'controller' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'date' => array('create' => 'date', 'bind' => PDO::PARAM_STR),
        'fact' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'data' => array('create' => 'TEXT', 'bind' => PDO::PARAM_STR)
        // Controller       | Fact
        //======================================
        // user             | register
        // user             | wrong_login
        // user             | wrong_password
        // user             | lost_password
        // user             | user_login
        // donation         | donation
        // translation      | save
        // translation      | submitForValidation
        // translation      | validate
        // builder          | addArticle
        // builder          | saveArticle
        // builder          | changeConfig
        // builder          | deleteArticle
    );
    var $keyName = 'id';
    var $tableSuffix = "events";
}

?>