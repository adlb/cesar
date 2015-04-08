<?php

require('views/viewer.php');
require('helpers/config.php');
require('helpers/gallery.php');
require('helpers/formatter.php');
require('helpers/translator.php');
require('helpers/authentication.php');
require('helpers/controllerFactory.php');
require('helpers/crowd.php');
require('models/user_dal.php');
require('models/userShort_dal.php');
require('models/article_dal.php');
require('models/text_dal.php');
require('models/textShort_dal.php');
require('models/media_dal.php');

$services['config'] = new Config('config.json');

$language = (isset($_GET['language'])) ? $_GET['language'] : substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if (!in_array($language, $services['config']->current['Languages'])) {
    $language = $services['config']->current['Languages'][0];
}

$services['authentication'] = new Authentication();
$services['userDal'] = 		new UserDal		($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['userShortDal'] = new UserShortDal($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['articleDal'] = 	new ArticleDal	($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['textDal'] = 		new TextDal		($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['textShortDal'] = new TextShortDal($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['mediaDal'] = 	new MediaDal	($services['config']->dbh, $services['config']->current['DBPrefix']);
$services['formatter'] = 	new Transformer ();
$services['gallery'] = 		new Gallery		($services['mediaDal']);
$services['translator'] = 	new Translator	($services['textDal'], $language, $services['config']->current['Languages']);
$services['crowd'] =        new Crowd		($services['userDal'], $services['userShortDal'], $services['authentication']);
$controllerFactory = 		new ControllerFactory($services);

$obj['language'] = $services['translator']->language;
$obj['title'] = $services['config']->current['Title'];

function translate($key) {
    global $services;
	if (!is_array($key)) {
		return $services['translator']->GetTranslation($key);
	} else {
		$k = array_shift($key);
		$trad = $services['translator']->GetTranslation($k);
		for($i = 0; $i < count($key); $i++) {
			$trad = str_replace('{'.$i.'}', $key[$i], $trad);
		}
		return $trad;
	}
}

//shortcut for translation
function t($key) {
	echo translate($key);
}

function disp($obj, $key) {
    if (isset($obj[$key]))
        echo htmlspecialchars($obj[$key]);
    else 
        return;
}

function url($param) {
	foreach($param as $k => $v){
        $queryString[] = $k.'='.urlencode($v);
    }
    return '?'.join('&', $queryString);
}

function redirectTo($param) {
	header('Location: '.url($param));
	die();
}

function Render($container, $view, &$obj) {
	$obj['innerView'] = $view;
	renderPartial($container, $obj);
}

function displayPartial($controller, $view, &$obj) {
    global $controllerFactory;
	
	$controllerInstance = $controllerFactory->GetController($controller);
	$viewFunction = 'view_'.$view;
	$controllerInstance->$viewFunction($obj, $view);
	renderPartial($view, $obj);
}

function renderPartial($view, &$obj) {
	include('views/view_'.$view.'.php');
}

?>