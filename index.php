<?php
date_default_timezone_set('Europe/London');

@session_start();

//error_reporting(E_ERROR | E_PARSE);
include('helpers/helper_general.php');
include('helpers/excel.php');

mb_internal_encoding("UTF-8");

$webSite = new WebSite('config.json');

$defaultController = 'site';
$defaultView = 'home';
$obj =& $webSite->obj;

$controller = (isset($_GET['controller'])) ? $_GET['controller'] : $defaultController;
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : $defaultView;
$action = (isset($_GET['action'])) ? $_GET['action'] : '';

//small check to avoid automatic creation of article from outside.
$view = ($view == 'fixedArticle') ? $defaultView : $view;

if ($webSite->services['config']->current['Maintenance'] && 
    !$webSite->services['authentication']->CheckRole('Administrator') && 
    $controller != 'user') {
    $controller = 'maintenance';
    $view = 'maintenance';
}

$controllerInstance = $webSite->controllerFactory->GetController($controller);

if ($action != '') {
    $actionFunction = 'action_'.$action;
    //Fixme
    $params = array_merge($_POST, $_GET);
    $params['rawData'] = file_get_contents("php://input");
    $view = $controllerInstance->$actionFunction($webSite->obj, $params);
} else {
    $viewFunction = 'view_'.$view;
    $view = $controllerInstance->$viewFunction($webSite->obj, $_GET);
}

function cleanData(&$str)
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    $str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
  }


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //Ajax
    header( 'content-type: application/json; charset=utf-8' );
    echo json_encode($webSite->obj);
} elseif (isset($_GET['respType']) && $_GET['respType'] == 'xls') {
    ExportToExcel($webSite->obj['fileName'], $webSite->obj['fileContent']);
} elseif (isset($_GET['respType']) && $_GET['respType'] == 'json') {
    header( 'content-type: application/json; charset=utf-8' );
    echo json_encode($webSite->obj);
} elseif (isset($_GET['respType']) && $_GET['respType'] == 'debug') {
    header( 'content-type: text/html; charset=utf-8' );
    var_dump($webSite->obj);
} else {
    header( 'content-type: text/html; charset=utf-8' );
    render($controllerInstance->container, $view, $obj);
}
?>