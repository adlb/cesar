<?php

require_once('controllers/controller_site.php');

class ControllerBuilder extends controllerSite{

    var $container = 'container';
    var $articleDal;
    var $gallery;
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
        $this->gallery = $webSite->services['gallery'];
        $this->authentication = $webSite->services['authentication'];
    }

    private function CheckRights($role) {
        if (!$this->authentication->CheckRole($role)) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
    }

    function action_deleteArticle(&$obj, $params) {
        $this->CheckRights('Administrator');
        if (isset($params['id']) && $this->articleDal->TryGet($params['id'], $article)) {
            $children = $this->articleDal->GetWhere(array('father' => $article['id']));
            if (count($children) > 0) {
                $this->webSite->AddMessage('warning', ':CANT_DELETE_ARTICLE_WITH_SONS');
                return;
            }
            $this->textDal->DeleteWhere(array('key' => $article['titleKey']));
            $this->textDal->DeleteWhere(array('key' => $article['textKey']));
            $this->articleDal->DeleteWhere(array('id' => $article['id']));
            $obj['status'] = 'ok';
            $this->webSite->AddMessage('success', ':ARTICLE_DELETED');
        } else {
            $this->webSite->AddMessage('warning', array(':ARTICLE_NOT_FOUND_WITH_ID_{0}', $params['id']));
        }
    }

    private function GetMenuFathers($id) {
        $menuFathers = array();
        $menuFathers[-1] = ':TOP_LEVEL_MENU';
        $menuItems = $this->articleDal->GetFathersForMenu();
        foreach($menuItems as $v) {
            if ($v['id'] != $id)
                $menuFathers[$v['id']] = $v['titleKey'];
        }
        return $menuFathers;
    }
    
    private function GetNewsFathers($id) {
        $newsFathers = array();
        $menuItems = $this->articleDal->GetFathersForNews();
        foreach($menuItems as $v) {
            if ($v['id'] != $id)
                $newsFathers[$v['id']] = $v['titleKey'];
        }
        return $newsFathers;
    }
    
    function action_saveArticle(&$obj, $params) {
        $this->CheckRights('Administrator');
        
        if (isset($params['callback'])) {
            $redirect = $params['callback'];
        } else {
            $redirect = "";
        }
        
        $obj['menuFathers'] = $this->GetMenuFathers($params['id']);
        $obj['newsFathers'] = $this->GetNewsFathers($params['id']);
        $obj['images'] = $this->gallery->GetStandardSizedImages();
        $article = $this->PrepareArticleForEditing($params);
        $obj['form'] = $article;
        $obj['callback'] = $redirect;
        
        if (!isset($params['language']) || $params['language'] == '') {
            $this->webSite->AddMessage('warning', ':BAD_LANGUAGE');
            return 'editArticle';
        }
       
       if (!in_array($article['type'], array('menu', 'article', 'news'))) {
            $this->webSite->AddMessage('warning', ':NO_TYPE_PROVIDED');
            return 'editArticle';
        }

        if (trim($article['titleTrad']) == '') {
            $this->webSite->AddMessage('warning', ':BAD_TITLE');
            return 'editArticle';
        }

        if ($article['type'] == 'article' && $article['father'] != -1 && !$this->articleDal->TryGet($article['father'], $fatherEntity)) {
            $this->webSite->AddMessage('warning', ':NO_MENUFATHER');
            return 'editArticle';
        }

        if ($article['type'] == 'news' && !$this->articleDal->TryGet($article['father'], $fatherEntity)) {
            $this->webSite->AddMessage('warning', ':NO_NEWSFATHER');
            return 'editArticle';
        }
        
        if (trim($params['id']) == '' || !$this->articleDal->TryGet($params['id'], $articleSaved)) {
            $article['rank'] = 1000;
        }
        
        //clean before store
        if ($article['type'] == 'menu') {
            $article['alert'] = 0;
            $article['needLogin'] = 0;
            $article['text'] = '';
            $article['home'] = 0;
            $article['father'] = -1;
        }
        if ($article['type'] == 'article') {
            $article['alert'] = 0;
        }
        if ($article['type'] == 'news') {
            $article['needLogin'] = 0;
            $article['home'] = 0;
        }
        
        $article['status'] = $article['show'] == 1 ? "show" : "hide";

        $isHome = ($article['home'] == 1); 
        
        if (!$this->articleDal->TrySave($article)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE_ARTICLE');
            return 'editArticle';
        }
        
        $article['titleKey'] = $params['titleKey'] != '' ? $params['titleKey'] : 'Article('.$article['id'].')';
        $article['textKey'] = $params['textKey'] != '' ? $params['textKey'] : 'Article-Content('.$article['id'].')';
        $this->translator->DirectUpdate($params['language'], $article['titleKey'], $params['titleTrad'], 1, 'pureText');
        $this->translator->DirectUpdate($params['language'], $article['textKey'], $params['textTrad'], 0, 'decoratedText');
        
        if (!$this->articleDal->TrySave($article)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE_ARTICLE');
            return 'editArticle';
        }
        
        if ($isHome)
            $this->config->Set('Home', $article['id']);

        $this->webSite->AddMessage('success', ':ARTICLE_SAVED');
        
        if ($redirect == "")
            $redirect = url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));
        
        $this->webSite->RedirectTo($redirect);
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
        $a['date'] = $a['date'] == "-0001-11-30" ? date('Y-m-d') : $a['date'];
        $a['imageId'] = isset($article['imageId']) && $this->gallery->TryGet(intval($article['imageId']), $image) ? $article['imageId'] : "";
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
        
        $a['needLogin'] = isset($article['needLogin']) ? $article['needLogin'] : 0;
        $a['alert'] = isset($article['alert']) ? $article['alert'] : 0;
        $a['datealert'] = isset($article['datealert']) ? date('Y-m-d', strtotime(str_replace('/', '-', $article['datealert']))) : date('Y-m-d', strtotime("+2 week"));
        $a['datealert'] = $a['datealert'] == "-0001-11-30" ? date('Y-m-d') : $a['datealert'];
        
        if (!isset($article['textTrad'])) {
            if (isset($article['textKey'])) {
                $a['textTrad'] = $this->translator->GetTranslation($article['textKey']);
            } else {
                $a['textTrad'] = '';
            }
        } else {
            $a['textTrad'] = $article['textTrad'];
        }
        
        if (!isset($article['titleTrad'])) {
            if (isset($article['titleKey'])) {
                $a['titleTrad'] = $this->translator->GetTranslation($article['titleKey']);
            } else {
                $a['titleTrad'] = '';
            }
        } else {
            $a['titleTrad'] = $article['titleTrad'];
        }
        
        return $a;
    }
    
    function view_editArticle(&$obj, &$params) {
        $this->CheckRights('Administrator');
        if (!(isset($params['id']) && $this->articleDal->TryGet($params['id'], $article))) {
            $article = array('id' => '');
        }
        
        $obj['form'] = $this->PrepareArticleForEditing($article);
        $obj['menuFathers'] = $this->GetMenuFathers($obj['form']['id']);
        $obj['newsFathers'] = $this->GetNewsFathers($obj['form']['id']);
        $obj['images'] = $this->gallery->GetStandardSizedImages();
        array_unshift($obj['images'], array('id' => 0, 'name' => $this->translator->GetTranslation(':NO_PICTURE')));
        
        $obj['callback'] = isset($params['callback']) ? $params['callback'] : url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));

        return 'editArticle';
    }

    function view_config(&$obj, $params) {
        if (!$this->config->configExists || $this->authentication->CheckRole('Administrator')) {
            $conf = $this->config->current;
            $conf['Languages'] = join(';', $conf['Languages']);
            $conf['ActiveLanguages'] = join(';', $conf['ActiveLanguages']);
            $obj['config'] = $conf;
        } else {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        $obj['IsForceNonMaintenance'] = $this->config->IsForceNonMaintenance();
        return 'config';
    }

    function action_format(&$obj, &$params) {
        $this->CheckRights(array('Administrator', 'Translator'));
        $rawData = file_get_contents("php://input");
        $obj['formattedText'] = $this->formatter->ToHtml($rawData);
    }

    function action_moveEntry(&$obj) {
        $this->CheckRights('Administrator');
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

    function action_saveConfig(&$obj, $params) {
        if ($params['todo'] != 'update') {
            $this->webSite->RedirectTo(array('controller' => 'site'));    
        }
        if (!$this->config->configExists || $this->authentication->CheckRole('Administrator')) {
            if ($this->config->TrySave($params)) {
                $this->webSite->AddMessage('success', ':CONFIG_SAVED');
                $this->webSite->RedirectTo(array('controller' => 'site'));
            } else {
                $this->webSite->AddMessage('warning', ':CANT_SAVE_CONFIG');
                $view = 'config';
                $this->view_config($obj, array());
                return $view;
            }
        } else {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
    }
    
    function action_reCreateTables(&$obj) {
        if (!$this->authentication->CheckRole('Administrator'))
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));

        $this->articleDal->DropTable();
        $this->articleDal->CreateTable();
        $this->textDal->DropTable();
        $this->textDal->CreateTable();
        $this->mediaDal->DropTable();
        $this->mediaDal->CreateTable();
        $this->userDal->DropTable();
        $this->userDal->CreateTable();

        $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
    }

    function action_deleteConfig(&$obj) {
        if (!$this->authentication->CheckRole('Administrator'))
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        
        $this->config->deleteConfig();
        $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
    }
    
    function action_activateFnm(&$obj, &$params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $obj['status'] = 'nok';
            return;
        }
        $this->config->SetForceNonMaintenance();
        $obj['status'] = 'ok';
        return;
    }
    
    function action_unactivateFnm(&$obj, &$params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $obj['status'] = 'nok';
            return;
        }
        $this->config->RemoveForceNonMaintenance();
        $obj['status'] = 'ok';
        return;
    }
    
}
?>