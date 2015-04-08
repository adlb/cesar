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
		$alerts = $this->articleDal->GetWhere(array('alert' => true, 'status' => 'show'));
		$obj['alerts'] = [];
		foreach($alerts as $alert) {
			if ($alert['date'] < date('Y-m-d')) {
				$alert['rawContent'] = $this->translator->GetTranslation($alert['text']);
				$alert['htmlContent'] = $this->formatter->ToHtml($alert['rawContent']);
				$obj['alerts'][] = $alert;
			} else {
				$alert['alert']  = false;
				//$this->articleDal->TrySave($alert);
			}
		}
	}
	
	private function view_articleInternal(&$obj, &$view, $id) {
		if (!$this->articleDal->TryGet($id, $article)) {
			$view = 'noArticle';
			return;
		}
		
		if ($article['status'] == 'hide' && !$this->authentication->CheckRole('Administrator')) {
			$view = 'noArticle';
			return;
		}
		
		$article['rawContent'] = $this->translator->GetTranslation($article['text']);
		$article['htmlContent'] = $this->formatter->ToHtml($article['rawContent']);
		$obj['article'] = $article;
		$view = 'article';
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