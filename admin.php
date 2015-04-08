<?php
    include('config.php');
    
    $db = new PDO($DBconnectionString, $DBuser, $DBpassword);
    
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    switch ($action) {
        case 'cleanDB':
            echo $action;
            $db->prepare("DROP TABLE IF EXISTS `parameters`")->execute();
        
            break;
        
        case 'createDB':
            echo $action;
            $db->prepare("CREATE TABLE IF NOT EXISTS `parameters` (
              `key` varchar(50) NOT NULL,
              `value` text NOT NULL,
              PRIMARY KEY (`key`)
            )")->execute();
            break;
        default:
            echo 'can\'t do '.$action.'.';
    }
?>