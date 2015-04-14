<?php
class ControllerBuilder {

	var $container = 'container';
	var $articleDal;
	var $textDal;
	var $translator;
	var $config;
	var $formatter;
	var $authentication;
	
	function ControllerBuilder($services) {
		$this->articleDal = $services['articleDal'];
		$this->textDal = $services['textDal'];
		$this->translator = $services['translator'];
		$this->config = $services['config'];
		$this->formatter = $services['formatter'];
		$this->authentication = $services['authentication'];
	}

	function action_hideArticle(&$obj) {
		if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
			$article['status'] = 'hide';
			$this->articleDal->TrySave($article);
			redirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $_GET['id']), $obj['errors']);
			die();
		}
	}
	
	function action_deleteArticle(&$obj, &$view) {
		if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
			$children = $this->articleDal->GetWhere(array('father' => $article['id']));
			if (count($children) > 0) {
				$obj['errors'][] = ':CANT_DELETE_ARTICLE_WITH_SONS';
				$article['rawContent'] = $this->translator->GetTranslation($article['text']);
				$article['htmlContent'] = $this->formatter->ToHtml($article['rawContent']);
				$obj['article'] = $article;
				$view = 'article';
				return;
			}
			$this->textDal->DeleteWhere(array('key' => $article['title']));
			$this->textDal->DeleteWhere(array('key' => $article['content']));
			$this->articleDal->DeleteWhere(array('id' => $article['id']));
			$obj['errors'][] = ':ARTICLE_DELETED';
			redirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);
			die();
		}
	}
	
	function action_showArticle(&$obj) {
		if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
			$article['status'] = 'show';
			$this->articleDal->TrySave($article);
			redirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $_GET['id']), $obj['errors']);
			die();
		}
	}
	
	function action_saveArticle(&$obj, &$view) {
		if (!isset($_POST['language']) || $_POST['language'] == '') {
			$obj['form'] = $_POST;
			$obj['errors'][] = ':BAD_LANGUAGE';
			$view = 'editArticle';
			return;
		}

		if (!isset($_POST['type']) || !in_array($_POST['type'], array('menu', 'article', 'news'))) {
			$obj['form'] = $_POST;
			$obj['errors'][] = ':NO_TYPE_PROVIDED';
			$view = 'editArticle';
			return;
		}
		
		if (!isset($_POST['title']) || trim($_POST['title']) == '') {
			$obj['form'] = $_POST;
			$obj['errors'][] = ':BAD_TITLE';
			$view = 'editArticle';
			return;
		}
		
		if ($_POST['type'] == 'article') {
			if (!isset($_POST['menuFather']) || ($_POST['menuFather'] != -1 && !$this->articleDal->TryGet($_POST['menuFather'], $fatherEntity))) {
				$obj['form'] = $_POST;
				$obj['errors'][] = ':NO_MENUFATHER';
				$view = 'editArticle';
				return;
			} else {
				$father = $_POST['menuFather'];
			}
		}
		
		if ($_POST['type'] == 'news') {
			if (!isset($_POST['newsFather']) || !$this->articleDal->TryGet($_POST['newsFather'], $fatherEntity)) {
				$obj['form'] = $_POST;
				$obj['errors'][] = ':NO_NEWSFATHER';
				$view = 'editArticle';
				return;
			} else {
				$father = $_POST['newsFather'];
			}
		}		
		
		if ($_POST['type'] == 'menu')
			$father = -1;

		$obj['form']['father'] = $father;
		
		if (!isset($_POST['id']) ||
				trim($_POST['id']) == '' ||
				!$this->articleDal->TryGet($_POST['id'], $article)) {
			$article = array();
			$article['rank'] = 1000;
			$article['status'] = 'show';
			$article['title'] = '';
			$article['text'] = '';
		}
		
		//clean before store
		if ($_POST['type'] == 'menu') {
			$_POST['alert'] = false;
			$_POST['text'] = '';
			$_POST['home'] = false;
		}
		if ($_POST['type'] == 'article') {
			$_POST['alert'] = false;
		}
		if ($_POST['type'] == 'news') {
			$_POST['home'] = false;
		}
		
		$article['father'] = $father;
		$article['type'] = $_POST['type'];
		$article['title'] = $this->translator->UpdateTranslation($_POST['language'], $article['title'], $_POST['title'], 1, 'pureText');
		$article['date'] = $_POST['date'];
		$article['text'] = $this->translator->UpdateTranslation($_POST['language'], $article['text'], $_POST['text'], 0, 'decoratedText');
		$article['alert'] = $_POST['alert'] == 1;
		$article['status'] = isset($_POST['show']) && $_POST['show'] == 1 ? "show" : "hide";
		
		if (!$this->articleDal->TrySave($article)) {
			$obj['form'] = $_POST;
			$obj['errors'][] = ':CANT_SAVE_Article';
			$view = 'editArticle';
			return;
		}
		
		if (isset($_POST['home']) && $_POST['home'])
			$this->config->Set('Home', $article['id']);
		
		$obj['errors'][] = ':ARTICLE_SAVED';
		redirectTo(array('controller' => 'site', 'view' => 'article', 'id' => $article['id']), $obj['errors']);
	}

	function view_editArticle(&$obj, &$view) {
        if (isset($_GET['id']) && $this->articleDal->TryGet($_GET['id'], $article)) {
            $obj['form'] = $article;
            $obj['form']['show'] = $article['status'] == 'show' || $article['status'] == 'home' ? 1 : 0;
			$obj['form']['home'] = $this->config->current['Home'] == $article['id'] ? 1 : 0;
        } else {
            $obj['form'] = array(
                'id' => '',
				'type' => '',
                'father' => 0,
                'title' => '',
                'titleKey' => '',
                'text' => '',
                'textKey' => '',
                'date' => date('Y-m-d'),
                'show' => 1,
				'home' => 0,
				'alert' => 0
            );
        }
		$obj['menuFathers'] = array();
        $obj['menuFathers'][-1] = ':TOP_LEVEL_MENU';
        $menuItems = $this->articleDal->GetFathersForMenu();
        foreach($menuItems as $v) {
            if ($v['id'] != $obj['form']['id'])
				$obj['menuFathers'][$v['id']] = $v['title'];
        }
		$obj['newsFathers'] = array();
        $menuItems = $this->articleDal->GetFathersForNews();
        foreach($menuItems as $v) {
            if ($v['id'] != $obj['form']['id'])
				$obj['newsFathers'][$v['id']] = $v['title'];
        }
    }
	
	function view_config(&$obj, &$view) {
		if (!$this->config->configExists || $this->authentication->CheckRole('Administrator')) {
			$conf = $this->config->current;
			$conf['Languages'] = join(';', $conf['Languages']);
			$conf['ActiveLanguages'] = join(';', $conf['ActiveLanguages']);
			$obj['config'] = $conf;
		} else {
			$obj['errors'][] = ':NOT_ALLOWED';
			redirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);
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
        $rawData = file_get_contents("php://input");
        $html = $this->formatter->ToHtml($rawData);
        echo $html;
    }
	
	function action_moveEntry(&$obj) {
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
		if ($this->config->TrySave($_POST)) {
			$obj['errors'][] = ':CONFIG_SAVED';
			redirectTo(array('controller' => 'site'), $obj['errors']);
		} else {
			$obj['errors'][] = ':CANT_SAVE_Config';
			$view = 'config';
			$this->view_config($obj, $view);
		}
	}
}
?>