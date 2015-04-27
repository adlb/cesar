<?php

class ControllerSite {

    var $container = 'container';
    var $autentication;
    var $articleDal;
    var $config;
    var $translator;
    var $formatter;

    function ControllerSite($services) {
        $this->authentication = $services['authentication'];
        $this->articleDal = $services['articleDal'];
        $this->config = $services['config'];
        $this->translator = $services['translator'];
        $this->formatter = $services['formatter'];
    }

    function view_menu(&$obj, &$view) {
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
    }

    function view_subMenu(&$obj, &$view) {
        //nothing to do here. $obj is already filled by view_menu.
    }

    function view_alerts(&$obj, &$view) {
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
    
    private function view_articleInternal(&$obj, &$view, $id) {
        if (!$this->articleDal->TryGet($id, $article)) {
            $view = 'noArticle';
            return;
        }
        
        if (
            $this->config->current['Home'] != $id &&
            $article['status'] == 'hide' && 
            !$this->authentication->CheckRole('Administrator')) {
            $view = 'noArticle';
            return;
        }
        
        $article = $this->enrich_Article($article, $this->authentication->CheckRole('Administrator'));
        
        $obj['article'] = $article;
    }

    function view_home(&$obj, &$view) {
        $home = $this->config->current['Home'];
        $this->view_articleInternal($obj, $view, $home);
    }

    function view_article(&$obj, &$view) {
        $id = isset($_GET['id']) ? $_GET['id'] : -1;
        $this->view_articleInternal($obj, $view, $id);
    }
}

?>