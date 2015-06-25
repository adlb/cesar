<?php

class ControllerMaintenance {

    var $webSite;
    var $config;
    var $container;
    
    function ControllerMaintenance($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->config = $services['config'];
        $this->container = 'containerMaintenance';
    }
    
    function view_maintenance(&$obj, $params) {
        $url = $this->config->current['MaintenanceRedirection'];
        if ($url != '') {
            header('Location: '.$url);
            die();
        } 
        
        return 'maintenance';
    }
}
?>