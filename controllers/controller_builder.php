<?php
require_once('controllers/controller_site.php');

class ControllerBuilder extends controllerSite{

    var $container = 'container';
    var $articleDal;
    var $textDal;
    var $translator;
    var $config;
    var $formatter;
    var $authentication;
    var $webSite;

    function ControllerBuilder($services) {
        Global $webSite;
        $this->webSite = $webSite;
        $this->articleDal = $webSite->services['articleDal'];
        $this->textDal = $webSite->services['textDal'];
        $this->translator = $webSite->services['translator'];
        $this->config = $webSite->services['config'];
        $this->formatter = $webSite->services['formatter'];
        $this->authentication = $webSite->services['authentication'];
    }

    private function CheckRights($role, $obj) {
        if (!$this->authentication->CheckRole($role)) {
            $this->config->SetError('warning', ':NOT_ALLOWED');
            redirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);
        }
    }

    function action_hideArticle(&$obj) {
        $this->CheckRights('Administrator', $obj);
        if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
            $article['status'] = 'hide';
            $this->articleDal->TrySave($article);
            redirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $_GET['id']), $obj['errors']);
            die();
        }
    }

    function action_deleteArticle(&$obj, &$view) {
        $this->CheckRights('Administrator', $obj);
        if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
            $children = $this->articleDal->GetWhere(array('father' => $article['id']));
            if (count($children) > 0) {
                $this->webSite->AddMessage('warning', ':CANT_DELETE_ARTICLE_WITH_SONS');
                $obj['article'] = $this->enrich_Article($article, true);
                $view = 'article';
                return;
            }
            $this->textDal->DeleteWhere(array('key' => $article['title']));
            $this->textDal->DeleteWhere(array('key' => $article['content']));
            $this->articleDal->DeleteWhere(array('id' => $article['id']));
            $this->webSite->AddMessage('success', ':ARTICLE_DELETED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
            die();
        }
    }

    function action_showArticle(&$obj) {
        $this->CheckRights('Administrator', $obj);
        if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
            $article['status'] = 'show';
            $this->articleDal->TrySave($article);
            redirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $_GET['id']), $obj['errors']);
            die();
        }
    }

    private function GetMenuFathers($id) {
        $menuFathers = array();
        $menuFathers[-1] = ':TOP_LEVEL_MENU';
        $menuItems = $this->articleDal->GetFathersForMenu();
        foreach($menuItems as $v) {
            if ($v['id'] != $id)
                $menuFathers[$v['id']] = $v['title'];
        }
        return $menuFathers;
    }
    
    private function GetNewsFathers($id) {
        $newsFathers = array();
        $menuItems = $this->articleDal->GetFathersForNews();
        foreach($menuItems as $v) {
            if ($v['id'] != $id)
                $newsFathers[$v['id']] = $v['title'];
        }
        return $newsFathers;
    }
    
    function action_saveArticle(&$obj, &$view) {
        $this->CheckRights('Administrator', $obj);
        
        $obj['menuFathers'] = $this->GetMenuFathers($_POST['id']);
        $obj['newsFathers'] = $this->GetNewsFathers($_POST['id']);
        $article = $this->PrepareArticleForEditing($_POST);
        $obj['form'] = $article;
        
        if (!isset($_POST['language']) || $_POST['language'] == '') {
            $this->webSite->AddMessage('warning', ':BAD_LANGUAGE');
            $view = 'editArticle';
            return;
        }
       
       if (!in_array($article['type'], array('menu', 'article', 'news'))) {
            $this->webSite->AddMessage('warning', ':NO_TYPE_PROVIDED');
            $view = 'editArticle';
            return;
        }

        if (trim($article['titleTrad']) == '') {
            $this->webSite->AddMessage('warning', ':BAD_TITLE');
            $view = 'editArticle';
            return;
        }

        if ($article['type'] == 'article' && $article['father'] != -1 && !$this->articleDal->TryGet($article['father'], $fatherEntity)) {
            $this->webSite->AddMessage('warning', ':NO_MENUFATHER');
            $view = 'editArticle';
            return;
        }

        if ($article['type'] == 'news' && !$this->articleDal->TryGet($article['father'], $fatherEntity)) {
            $this->webSite->AddMessage('warning', ':NO_NEWSFATHER');
            $view = 'editArticle';
            return;
        }
        
        if (trim($_POST['id']) == '' || !$this->articleDal->TryGet($_POST['id'], $articleDummy)) {
            $article['rank'] = 1000;
        }
        
        //clean before store
        if ($article['type'] == 'menu') {
            $article['alert'] = 0;
            $article['text'] = '';
            $article['home'] = 0;
            $article['father'] = -1;
        }
        if ($article['type'] == 'article') {
            $article['alert'] = 0;
        }
        if ($article['type'] == 'news') {
            $article['home'] = 0;
        }

        $article['title'] = $this->translator->UpdateTranslation($_POST['language'], $article['titleKey'], $_POST['titleTrad'], 1, 'pureText');
        $article['text'] = $this->translator->UpdateTranslation($_POST['language'], $article['textKey'], $_POST['textTrad'], 0, 'decoratedText');
        $article['status'] = $article['show'] == 1 ? "show" : "hide";

        $isHome = ($article['home'] == 1); 
        if (!$this->articleDal->TrySave($article)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE_Article');
            $view = 'editArticle';
            return;
        }

        if ($isHome)
            $this->config->Set('Home', $article['id']);

        $this->webSite->AddMessage('success', ':ARTICLE_SAVED');
        $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));
    }

    private function PrepareArticleForEditing($article) {
        $a = array();
        $a['id'] = isset($article['id']) ? $article['id'] : '';
        $a['type'] = isset($article['type']) ? $article['type'] : '';
        if (isset($article['father'])) {
            $a['father'] = $article['father'];
        } else {
            if ($a['type'] == 'menu' && isset($article['menuFather'])) {
                $a['father'] = $article['menuFather'];
            } elseif ($a['type'] == 'news' && isset($article['newsFather'])) {
                $a['father'] = $article['newsFather'];
            } elseif ($a['type'] == 'article' && isset($article['menuFather'])) {
                $a['father'] = $article['menuFather'];
            } else {
                $a['father'] = $a['type'] == 'article' ? -1 : 0;
            }
        }
        $a['titleKey'] = isset($article['titleKey']) ? $article['titleKey'] : '';
        $a['textKey'] = isset($article['textKey']) ? $article['textKey'] : '';
        $a['date'] = isset($article['date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $article['date']))) : date('Y-m-d');
        if (!isset($article['show'])) {
            $a['show'] = (isset($article['status']) && $article['status'] == 'show') ? 1 : 0;
        } else {
            $a['show'] = $article['show'];
        }
        
        if (!isset($article['home'])) {
            $a['home'] = (isset($article['id']) && ($this->config->current['Home'] == $article['id'])) ? 1 : 0;
        } else {
            $a['home'] = $article['home'];
        }
        
        $a['alert'] = isset($article['alert']) ? $article['alert'] : 0;
        $a['datealert'] = isset($article['datealert']) ? date('Y-m-d', strtotime(str_replace('/', '-', $article['datealert']))) : date('Y-m-d', strtotime("+2 week"));

        if (!isset($article['textTrad'])) {
            if (isset($article['text'])) {
                $a['textTrad'] = $this->translator->GetTranslation($article['text']);
            } else {
                $a['textTrad'] = '';
            }
        } else {
            $a['textTrad'] = $article['textTrad'];
        }
        
        if (!isset($article['titleTrad'])) {
            if (isset($article['title'])) {
                $a['titleTrad'] = $this->translator->GetTranslation($article['title']);
            } else {
                $a['titleTrad'] = '';
            }
        } else {
            $a['titleTrad'] = $article['titleTrad'];
        }
        
        return $a;
    }
    
    function view_editArticle(&$obj, &$view) {
        $this->CheckRights('Administrator', $obj);
        if (!(isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article))) {
            $article = array();
        }
        
        $obj['form'] = $this->PrepareArticleForEditing($article);
        $obj['menuFathers'] = $this->GetMenuFathers($obj['form']['id']);
        $obj['newsFathers'] = $this->GetNewsFathers($obj['form']['id']);
    }

    function view_config(&$obj, &$view) {
        if (!$this->config->configExists || $this->authentication->CheckRole('Administrator')) {
            $conf = $this->config->current;
            $conf['Languages'] = join(';', $conf['Languages']);
            $conf['ActiveLanguages'] = join(';', $conf['ActiveLanguages']);
            $obj['config'] = $conf;
        } else {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
    }

    function view_help(&$obj, &$view) {
        $article['id'] = -1;
        $article['rank'] = 1000;
        $article['status'] = 'show';
        $article['father'] = 0;
        $article['title'] = 'Aide';
        $article['date'] = '';
        $article['text'] = '';
        $article['status'] = 'hide';
        $article['rawContent'] = $this->translator->GetTranslation($article['text']);
        $article['htmlContent'] = $this->formatter->ToHtml($article['rawContent']);
        $obj['article'] = $article;
        $view = 'article';
    }

    function action_format(&$obj, &$view) {
        $this->CheckRights(array('Administrator', 'Translator'), $obj);
        $rawData = file_get_contents("php://input");
        $obj['formattedText'] = $this->formatter->ToHtml($rawData);
    }

    function action_moveEntry(&$obj) {
        $this->CheckRights('Administrator', $obj);
        $rows = $_POST['row'];

        $i = 0;
        foreach($rows as $key) {
            $article;
            if ($this->articleDal->TryGet($key, $article)) {
                $article['rank'] = $i;
                $this->articleDal->TrySave($article);
                $i++;
            }
        }
        die('Nothing Else To Do');
    }

    function action_saveConfig(&$obj, &$view) {
        if (!$this->config->configExists || $this->authentication->CheckRole('Administrator')) {
            if ($this->config->TrySave($_POST)) {
                $obj['errors'][] = ':CONFIG_SAVED';
                redirectTo(array('controller' => 'site'), $obj['errors']);
            } else {
                $obj['errors'][] = ':CANT_SAVE_Config';
                $view = 'config';
                $this->view_config($obj, $view);
            }
        } else {
            $obj['errors'][] = ':NOT_ALLOWED';
            redirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);
        }
    }
}
?>