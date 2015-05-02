<?php

require('views/viewer.php');
require('helpers/config.php');
require('helpers/gallery.php');
require('helpers/formatter.php');
require('helpers/translator.php');
require('helpers/authentication.php');
require('helpers/controllerFactory.php');
require('helpers/crowd.php');
require('helpers/mailer.php');
require('helpers/journal.php');
require('models/user_dal.php');
require('models/userShort_dal.php');
require('models/article_dal.php');
require('models/text_dal.php');
require('models/textShort_dal.php');
require('models/media_dal.php');
require('models/event_dal.php');

class WebSite {
    var $obj;
    var $services;
    var $controllerFactory;
    
    function __construct($configFile) {
        $this->services['config'] = new Config($configFile);
        $this->services['authentication'] = new Authentication();
        $language = (isset($_GET['language'])) ? $_GET['language'] : substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                
        $this->services['userDal'] =        new UserDal         ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['userShortDal'] =   new UserShortDal    ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['articleDal'] =     new ArticleDal      ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['textDal'] =        new TextDal         ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['textShortDal'] =   new TextShortDal    ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['mediaDal'] =       new MediaDal        ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['eventDal'] =       new EventDal        ($this->services['config']->dbh, $this->services['config']->current['DBPrefix']);
        $this->services['translator'] =     new Translator      ($this->services['config'], $this->services['textDal'], $language, $this->services['authentication']->CheckRole(array('Administrator', 'Translator')));
        $this->services['gallery'] =        new Gallery         ($this->services['mediaDal']);
        $this->services['formatter'] =      new Transformer     ($this->services['gallery'], $this->services['articleDal'], $this->services['translator']);
        $this->services['crowd'] =          new Crowd           ($this->services['config']->current['SecretLine'], $this->services['userDal'], $this->services['userShortDal'], $this->services['authentication']);
        $this->services['mailer'] =         new Mailer          ($this->services['config']);
        $this->services['journal'] =        new Journal         ($this->services['eventDal'], $this->services['authentication']);
        $this->controllerFactory =          new ControllerFactory($this->services);

        if (!isset($_GET['language'])) {
            $this->language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            header('Location: '.$this->services['translator']->language.'/');
            die();
        }
        
        $this->obj['title'] = $this->services['config']->current['Title'];
        $this->obj['analytics'] = $this->services['config']->current['Analytics'];
        $this->obj['language'] = $this->services['translator']->language;
        $this->obj['messages'] = (isset($_SESSION['messages'])) ? $_SESSION['messages'] : array();
        $_SESSION['messages'] = null;
    }

    function AddMessage($level, $text) {
        $levels = array('success' => ':SUCCESS', 'info' => ':INFORMATION', 'warning' => ':WARNING', 'danger' => ':DANGER');
        if (!in_array($level, array_keys($levels)))
            $level = 'info';
        
        $this->obj['messages'][] = array(
            'level' => $level,
            'strongText' => $levels[$level],
            'text' => $text
        );
    }
    
    function RedirectTo($param) {
        $_SESSION['messages'] = $this->obj['messages'];
        header('Location: '.url($param));
        die();
    }
}

//shortcut for translation
function t($key) {
    global $webSite;
    echo $webSite->services['translator']->GetTranslation($key);
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

function redirectTo($param, $messages) {
    $_SESSION['messages'] = $messages;

    header('Location: '.url($param));
    die();
}

function Render($container, $view, &$obj) {
    $obj['innerView'] = $view;
    renderPartial($container, $obj);
}

function displayPartial($controller, $view, $params) {
    global $webSite;
    $controllerInstance = $webSite->controllerFactory->GetController($controller);
    $viewFunction = 'view_'.$view;
    $view = $controllerInstance->$viewFunction($obj, $params);
    renderPartial($view, $obj);
}

function renderPartial($view, $obj) {
    include('views/view_'.$view.'.php');
}

?>