<?php

require_once ('dal.php');

class ArticleDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'type' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'father' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT, 'key' => true),
        'title' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'date' => array('create' => 'date', 'bind' => PDO::PARAM_STR),
        'text' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'rank' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT),
        'alert' => array('create' => 'boolean', 'bind' => PDO::PARAM_BOOL),
        'status' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
        //Can be
        // * show
        // * hide
    );
    var $keyName = 'id';
    var $tableSuffix = "articles";

    function GetFathersForMenu() {
        return $this->GetWhere(array('type' => 'menu'));
    }

    function GetFathersForNews() {
        return $this->GetWhere(array('type' => 'article'));
    }
}

?>