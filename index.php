<?php

@session_start();

include('helpers/helper_general.php');

$webSite = new WebSite('config.json');

$containerView = 'container';
$defaultController = 'site';
$defaultView = 'home';
$obj = $webSite->obj;

$controller = (isset($_GET['controller'])) ? $_GET['controller'] : $defaultController;
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : $defaultView;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$obj['errors'] = (isset($_SESSION['errors'])) ? $_SESSION['errors'] : array();
$_SESSION['errors'] = null;

if (isset($_GET['forceMaintenance']) ||
    ($webSite->services['config']->current['Maintenance'] && $webSite->services['authentication']->Role() != 'Administrator'
    && $controller!='user' && $view != 'login')) {
    $message = $webSite->services['config']->current['MaintenanceMessage'];
    include('maintenance.php');
    die();
}

$controllerInstance = $webSite->controllerFactory->GetController($controller);

if ($action != '') {
    $actionFunction = 'action_'.$action;
    $controllerInstance->$actionFunction($obj, $view);
} else {
    $viewFunction = 'view_'.$view;
    $controllerInstance->$viewFunction($obj, $view);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //Ajax
    header( 'content-type: application/json; charset=utf-8' );
    echo json_encode($obj);
	die();
} else {
    header( 'content-type: text/html; charset=utf-8' );
    render($controllerInstance->container, $view, $obj);
}

?>