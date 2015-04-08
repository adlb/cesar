<?php
@session_start();

include('helpers/helper_general.php');

$containerView = 'container';
$defaultController = 'site';
$defaultView = 'home';

$controller = (isset($_GET['controller'])) ? $_GET['controller'] : $defaultController;
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : $defaultView;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if (isset($_GET['forceMaintenance']) ||
	($services['config']->current['Maintenance'] && $services['authentication']->Role() != 'Administrator'
	&& $controller!='user' && $view != 'login')) {
	$message = $services['config']->current['MaintenanceMessage'];
	include('maintenance.php');
	die();
}

$controllerInstance = $controllerFactory->GetController($controller);

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
	renderPartial($view, $obj);
} else {
	header( 'content-type: text/html; charset=utf-8' );
	render($controllerInstance->container, $view, $obj);
}

?>