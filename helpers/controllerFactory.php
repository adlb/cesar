<?php

class ControllerFactory {

    var $services;
    var $cache;

    function ControllerFactory($services) {
        $this->services = $services;
    }

    function GetController($controllerName) {
        if (isset($this->cache[$controllerName]))
            return $this->cache[$controllerName];

        require('controllers/controller_'.$controllerName.'.php');
        $controllerClassName = 'Controller'.$controllerName;
        $controller = new $controllerClassName($this->services);
        $this->cache[$controllerName] = $controller;
        return $controller;
    }
}