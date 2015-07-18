<?php

class ControllerSite {

    var $container = 'container';
    var $autentication;
    var $articleDal;
    var $config;
    var $translator;
    var $formatter;
    var $gallery;
    var $webSite;

    function ControllerSite($services) {
        Global $webSite;
        $this->webSite = $webSite;
        $this->authentication = $services['authentication'];
        $this->articleDal = $services['articleDal'];
        $this->config = $services['config'];
        $this->gallery = $services['gallery'];
        $this->translator = $services['translator'];
        $this->formatter = $services['formatter'];
    }

    function view_menu(&$obj, $params) {
        $obj['user'] = $this->authentication->currentUser;
        $obj['language'] = $this->translator->language;
        $obj['languages'] = $this->translator->languages;
        $obj['menu'] = array();
        if ($this->authentication->CheckRole('Administrator'))
            $articlesArray = $this->articleDal->GetWhere(array('type' => array('menu', 'article')), array('rank' => true));
        else
            $articlesArray = $this->articleDal->GetWhere(array('type' => array('menu', 'article'), 'status' => 'show'), array('rank' => true));

        $articles = array();
        foreach($articlesArray as &$article) {
            $articles[$article['id']] = $article;
        }
        foreach($articles as &$article) {
            if ($article['father'] == -1)
                $obj['menu'][] = &$article;
            elseif (isset($articles[$article['father']])) {
                $articles[$article['father']]['sons'][] = &$article;
            }
        }
        return 'menu';
    }

    function view_alerts(&$obj, $params) {
        $alerts = $this->articleDal->GetWhere(array('alert' => true, 'status' => 'show'));
        $alertsActives = array();
        foreach($alerts as $alert) {
            if (strtotime($alert['datealert']) < strtotime(date('Y-m-d'))) {
                $alert['alert'] = 0;
                $this->articleDal->TrySave($alert);
            } else {
                $alertsActives[] = $this->enrich_Article($alert, $this->authentication->CheckRole('Administrator'), true);
            }
        }
        $obj['alerts'] = $alertsActives;
        return 'alerts';
    }

    protected function enrich_Article($article, $isAdmin, $html) {
        $article['rawTitle'] = $this->translator->GetTranslation($article['titleKey']);
        if ($html) {
            $article['htmlTitle'] = htmlentities($article['rawTitle']);

            //small hack to have a way to avoid empty content 
            if ($article['htmlTitle'] == ' ')
                $article['htmlTitle'] = '&nbsp;';
        } else {
            $article['textTitle'] = $article['rawTitle'];
        }
        
        $article['rawContent'] = $this->translator->GetTranslation($article['textKey']);
        if ($html)
            $article['htmlContent'] = $this->formatter->ToHtml($article['rawContent']);
        else
            $article['textContent'] = $this->formatter->ToText($article['rawContent']);
        
        $conditionForSubArticles = array('father' => $article['id']);
        if (!$isAdmin) {
            $conditionForSubArticles['status'] = 'show';
        }
        $article['links'] = array();
        if ($isAdmin) {
            $article['links'][] = array('type' => 'edit', 'name' => $this->translator->GetTranslation(':EDIT'), 'url' => url(array('controller' => 'builder', 'view' => 'editArticle', 'id' => $article['id'])));
        }
        $subArticles = array_slice($this->articleDal->GetWhere($conditionForSubArticles, array('date' => false)), 0, 7);
        $article['subArticles'] = array();
        
        if ($this->gallery->TryGet($article['imageId'], $image)) {
            $article['image'] = $image['file'];
        } else {
            $article['image'] = '';
        }
        $article['url'] = url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']));
        $article['permalink'] = url(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']), true);
        foreach($subArticles as $subArticle)
            $article['subArticles'][] = $this->enrich_Article($subArticle, $isAdmin, false);
        return $article;
    }
    
    public function view_latestNews(&$obj, $params) {
        $news = $this->articleDal->GetWhere(array('type' => array('article', 'news'), 'status' => 'show'), array('date' => false));
        $obj['latestNews'] = array();
        for ($i = 0; $i< count($news); $i++) {
            if (count($obj['latestNews']) == 7)
                break;
            if ($news[$i]['textKey'] != "" && $news[$i]['imageId'] != 0)
                array_push($obj['latestNews'], $this->enrich_Article($news[$i], false, false));
        }
        
        return 'latestNews';
    }
    
    private function TryViewArticleInternal(&$obj, $id) {
        if (!$this->articleDal->TryGet($id, $article))
            return false;
        
        if ($article['needLogin'] && !$this->authentication->CheckRole(array('Administrator', 'Translator', 'Visitor'))) {
            $this->webSite->AddMessage('info', 'You have to login or create an account to access this page.');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'login', 'callback' =>
                url(array('controller' => 'site', 'view' => 'article', 'id' => $id))
            ));
        }
        
        if ($this->config->current['Home'] != $id &&
            $article['status'] == 'hide' && 
            !$this->authentication->CheckRole('Administrator')) {
            return false;
        }
        
        $article = $this->enrich_Article($article, $this->authentication->CheckRole('Administrator'), true);
        
        $obj['article'] = $article;
        return true;
    }

    function view_home(&$obj, $params) {
        $home = $this->config->current['Home'];
        if (!$this->TryViewArticleInternal($obj, $home))
            return 'noArticle';
        else
            return 'home';
    }

    function view_article(&$obj, $params) {
        $id = isset($params['id']) ? $params['id'] : -1;
        if ($id == $this->config->current['Home']) {
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!$this->TryViewArticleInternal($obj, $id))
            return 'noArticle';
        else
            return 'article';
    }
    
    function view_footer(&$obj, $params) {
        $obj['links'] = array();
        $obj['contact'] = $this->config->current['Contact'];
        $legal = $this->articleDal->GetFixedArticle('Legal');
        $contact = $this->articleDal->GetFixedArticle('Contact');
        $obj['links'][] = array(
            'display' => $this->translator->GetTranslation($legal['titleKey']),
            'link' => url(array('controller' => 'site', 'view' => 'article', 'id' => $legal['id']))
        );
        $obj['links'][] = array(
            'display' => $this->translator->GetTranslation($contact['titleKey']),
            'link' => url(array('controller' => 'site', 'view' => 'article', 'id' => $contact['id']))
        );

        $obj['url'] = $this->webSite->currentLink;
        $obj['title'] = $this->config->current['Title'];
        return 'footer';
    }
    
    function view_fixedArticle(&$obj, $params) {
        $isAdmin = $this->authentication->CheckRole('Administrator');
        $titleKey = $params['titleKey'];
        $article = $this->articleDal->GetFixedArticle($titleKey);
        $article = $this->enrich_Article($article, $isAdmin, true);
        $obj['article'] = $article;
        
        $renderType = isset($params['renderType']) ? $params['renderType'] : 'normal';
        if ($renderType == 'raw')
            return 'articleRaw';
        else
            return 'article';
    }
    
    function view_fixedArticleText(&$obj, $params) {
        $isAdmin = $this->authentication->CheckRole('Administrator');
        $titleKey = $params['titleKey'];
        $article = $this->articleDal->GetFixedArticle($titleKey);
        $article = $this->enrich_Article($article, $isAdmin, false);
        $obj['article'] = $article;
        return 'articleText';
    }
    
    function view_help(&$obj, &$params) {
        if (isset($params['titleKey']) && in_array($params['titleKey'], 
            array(  'globalSetup', 
                    'homePageSetup',
                    'userManagement', 
                    'donationManagement', 
                    'articleManagement', 
                    'mediaManagement',
                    'articleWriting'))) {
            $params['titleKey'] = 'file:Help_'.$params['titleKey'];
            return $this->view_fixedArticle($obj, $params);
        } else {
            $this->WebSite->AddMEssage('warning', ':THIS_TOPIC_DOES_NOT_EXIST');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
    }
    
    function view_articleRightColumn(&$obj, &$params) {
        $linksSource = $this->articleDal->GetWhere(array('type'=>array('article', 'news'), 'status' => 'show'), array('date'=>false));
        $links = array();
        foreach($linksSource as $item) {
            $links[] = array(
                'link' => url(array('controller' => 'site', 'view' => 'article', 'id' => $item['id'])),
                'display' => $this->translator->GetTranslation($item['titleKey'])
            );
        }
        $obj['links'] = array_slice($links, 0, 8);
        return 'articleRightColumn';
    }
}

?>