<?php

class ControllerMaintenance {

    var $webSite;
    var $config;
    var $container;
    var $translator;
    
    function ControllerMaintenance($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->config = $services['config'];
        $this->container = 'containerMaintenance';
        $this->translator = $services['translator'];
    }
    
    function view_maintenance(&$obj, $params) {
        $url = $this->config->current['MaintenanceRedirection'];
        if ($url != '') {
            header('Location: '.$url);
            die();
        } 
        
        return 'maintenance';
    }
    
    function view_menu(&$obj, $params) {
        $obj['user'] = null;
        $obj['language'] = $this->translator->language;
        $obj['languages'] = $this->translator->languages;
        $obj['menu'] = array();

        $articles = array();

        return 'menu';
    }
}
?>