<?php

class ControllerSite {

    var $container = 'container';
    var $autentication;
    var $articleDal;
    var $config;
    var $translator;
    var $formatter;
    var $webSite;

    function ControllerSite($services) {
        Global $webSite;
        $this->webSite = $webSite;
        $this->authentication = $services['authentication'];
        $this->articleDal = $services['articleDal'];
        $this->config = $services['config'];
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
        $alerts = $this->articleDal->GetWhere(array('alert' => 1, 'status' => 'show'));
		$alertsActives = array();
		foreach($alerts as $alert) {
			if (strtotime($alert['datealert']) < strtotime(date('Y-m-d'))) {
				$alert['alert'] = 0;
				$this->articleDal->TrySave($alert);
			} else {
				$alertsActives[] = $this->enrich_Article($alert, $this->authentication->CheckRole('Administrator'));
			}
		}
		$obj['alerts'] = $alertsActives;
        return 'alerts';
    }

    protected function enrich_Article($article, $isAdmin) {
        $article['rawContent'] = $this->translator->GetTranslation($article['textKey']);
        $article['htmlContent'] = $this->formatter->ToHtml($article['rawContent']);
        $conditionForSubArticles = array('father' => $article['id']);
        if (!$isAdmin) {
            $conditionForSubArticles['status'] = 'show';
        }
        $article['links'] = array();
        if ($isAdmin) {
            $article['links'][] = array('type' => 'edit', 'url' => url(array('controller' => 'builder', 'view' => 'editArticle', 'id' => $article['id'])));
            $article['links'][] = array('type' => 'delete', 'url' => url(array('controller' => 'builder', 'action' => 'deleteArticle', 'id' => $article['id'])));
        }
        $subArticles = array_slice($this->articleDal->GetWhere($conditionForSubArticles, array('date' => false)), 0, 6);
        $article['subArticles'] = array();
        foreach($subArticles as $subArticle)
            $article['subArticles'][] = $this->enrich_Article($subArticle, $isAdmin);
        return $article;
    }
    
    private function TryViewArticleInternal(&$obj, $id) {
        if (!$this->articleDal->TryGet($id, $article))
            return false;
        
        if ($article['needLogin'] && !$this->authentication->CheckRole(array('Administrator', 'Translator', 'Visitor'))) {
            $this->webSite->AddMessage('info', 'You have to login or create an account to access this page.');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'login'));
        }
        
        if ($this->config->current['Home'] != $id &&
            $article['status'] == 'hide' && 
            !$this->authentication->CheckRole('Administrator')) {
            return false;
        }
        
        $article = $this->enrich_Article($article, $this->authentication->CheckRole('Administrator'));
        
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
        $legal = $this->articleDal->GetFixedArticle('Legal');
        $obj['links'][] = array(
            'display' => $this->translator->GetTranslation($legal['titleKey']),
            'link' => url(array('controller' => 'site', 'view' => 'article', 'id' => $legal['id']))
        );
        return 'footer';
    }
    
    function view_fixedArticle(&$obj, $params) {
        $titleKey = $params['titleKey'];
        $article = $this->articleDal->GetFixedArticle($titleKey);
        $article = $this->enrich_Article($article, $this->authentication->CheckRole('Administrator'));
        $obj['article'] = $article;
        return 'article';
    }
}

?>