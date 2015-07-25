<?php

class TextManager {
    var $textsDico;
    var $languages;
    var $translator;
    
    function __construct($textShortDal, $languages, $translator) {
        $this->languages = $languages;
        $texts = $textShortDal->GetWhere(array());
        foreach($texts as $text) {
            if (!isset($this->textsDico[$text['key']]))
                $this->textsDico[$text['key']] = array();
            $this->textsDico[$text['key']][$text['language']] = $text['textStatus'];
        }
        $this->translator = $translator;
    }
    
    private function GetKeyStatus($key, $language) {
        if ($key == '')
            return 'ready';
        if (!isset($this->textsDico[$key]))
            return 'toBeTranslated';
        if (!isset($this->textsDico[$key][$language]))
            return 'toBeTranslated';
        return $this->textsDico[$key][$language];
    }
    
    //      Status 1 \ Status 2 | ready | t-tb-V | t-tb-U | ft-tb-V | tb-T |
    //------------------------------------------------------------------------------------------
    //                    ready | ready | t-tb-V | t-tb-U | ft-tb-V | tb-T |
    // translationToBeValidated |       | t-tb-V | t-tb-U | ft-tb-V | tb-T |
    //   translationToBeUpdated |       |        | t-tb-U | ft-tb-V | tb-T |
    // firstTranslationToBeVali |       |        |        | ft-tb-V | tb-T |
    //           toBeTranslated |       |        |        |         | tb-T |
    private function GetStatus($key1, $key2, $language) {
        $status = array('unkown', 'ready', 'translationToBeValidated', 'translationToBeUpdated', 'firstTranslationToBeValidated', 'toBeTranslated');
        
        $status1 = array_search($this->GetKeyStatus($key1, $language), $status);
        $status2 = array_search($this->GetKeyStatus($key2, $language), $status);
        return $status[max($status1, $status2)];
    }
    
    function GetStatusPerLanguage($key1, $key2) {
        $result = array();
        foreach($this->languages as $language) {
            $result[$language['name']] = $this->translator->GetTranslation(':TRANSLATION_'.strtoupper($this->GetStatus($key1, $key2, $language['name'])));
        }
        return $result;
    }
}

class ControllerTranslationManager {

    var $container = 'container';
    var $textDal;
    var $textShortDal;
    var $articleDal;
    var $translator;
    var $authentication;
    var $config;
    var $webSite;
    
    function ControllerTranslationManager($services) {
        Global $webSite;
        $this->webSite = $webSite;
        
        $this->config = $services['config'];
        $this->textDal = $services['textDal'];
        $this->translator = $services['translator'];
        $this->authentication = $services['authentication'];
        $this->textShortDal = $services['textShortDal'];
        $this->articleDal = $services['articleDal'];
    }

    function action_saveText(&$obj, $params) {
        $redirect = isset($params['callback']) ? $params['callback'] : url(array('controller' => 'translationManager', 'view' => 'translationList'));
        if (!isset($params['actionPushed']) || $params['actionPushed'] == 'cancel') {
            $this->webSite->RedirectTo($redirect);
        }
    
        $nextTitle = isset($params['nextTitle']) ? trim($params['nextTitle']) : '';
        $nextText = isset($params['nextText']) ? trim($params['nextText']) : '';

        $titleKey = isset($params['titleKey']) ? $params['titleKey'] : die('ERREUR, what are you trying to do ?');
        $textKey = isset($params['textKey']) ? $params['textKey'] : die('ERREUR, what are you trying to do ?');
        
        if ($titleKey != '' && $nextTitle == '') {
            $this->webSite->AddMessage('warning', ':TITLE_CANT_BE_EMPTY');
            $this->webSite->RedirectTo($redirect);
        }
        
        $lg = isset($params['lg']) ? $params['lg'] : die('ERREUR, what are you trying to do ?');

        $action = isset($params['actionPushed']) ? $params['actionPushed'] : die('ERREUR, what are you trying to do ?');
        
        $this->translator->UpdateTranslation($action, $lg, $titleKey, $nextTitle, true, 'pureText');
        $this->translator->UpdateTranslation($action, $lg, $textKey, $nextText, false, 'decoratedText');
        
        $this->webSite->RedirectTo($redirect);
    }

    function view_translationList(&$obj, $params) {
        $languages = $this->translator->languages;
        $textManager = new TextManager($this->textShortDal, $languages, $this->translator);
        
        $articlesDB = $this->articleDal->GetWhere(array(), array('id' => true));
        $articles = array();
        foreach($articlesDB as &$article) {
            if (substr($article['titleKey'], 0, 5) == 'file:')
                continue;
            $articles[] = $article;
        }
        
        foreach($articles as &$article) {
            $article['titleTrad'] = $this->translator->GetTranslation($article['titleKey']);
            $article['StatusPerLanguage'] = $textManager->GetStatusPerLanguage($article['titleKey'], $article['textKey']);
            $article['link'] = true;
            $article['home'] = $this->config->current['Home'] == $article['id'] ? true : false;
            $article['mail'] = substr($article['titleKey'], 0, 5) == 'Mail_' ? true : false;
        }
        
        foreach($this->textDal->SelectDistinct('key', array('usage' => 'grouped')) as $groupedKey) {
            $fakeArticle = array(
                'id' => 0,
                'status' => 'show',
                'type' => 'groupedTrad',
                'titleTrad' => $this->translator->GetTranslation(':GROUPED_KEY_'.$groupedKey),
                'StatusPerLanguage' => $textManager->GetStatusPerLanguage('', $groupedKey),
                'titleKey' => '',
                'textKey' => $groupedKey,
                'link' => false,
                'home' => false
            );
            
            array_unshift($articles, $fakeArticle);
        }
        
        $map = array(
            'menu' => $this->translator->GetTranslation(':ARTICLE_MENU'), 
            'news' => $this->translator->GetTranslation(':ARTICLE_NEWS'), 
            'article' => $this->translator->GetTranslation(':ARTICLE_ARTICLE'), 
            'groupedTrad' => $this->translator->GetTranslation(':ARTICLE_GROUPEDTRAD'));
        foreach($articles as &$article) {
            $article['translatedType'] = $map[$article['type']]; 
        }
        
        $obj['articles'] = $articles;
        $obj['languages'] = $languages;
        
        return 'translationList';
    }

    private function GetTextForEditing($key) {
        $texts = array();
        foreach($this->textDal->GetWhere(array('key' => $key)) as $text) {
            $texts[$text['language']] = $text;
        }
        foreach($this->translator->languages as $lg) {
            if (!isset($texts[$lg['name']])) {
                if (!$this->textDal->TryGetFromFile($key, $lg['name'], $data)) {
                    $data = $key;
                }
                $texts[$lg['name']] = array('nextText' => $data, 'language' => $lg['name']);
            }
        }
        return $texts;
    }
    
    function view_editArticleTrad(&$obj, $params) {
        $lg = (isset($params['lg']) && in_array($params['lg'], $this->config->current['Languages'])) ? $params['lg'] : $this->config->current['Languages'][0];

        $obj['titleKey'] = isset($params['titleKey']) ? $params['titleKey'] : '';
        $obj['textKey'] = isset($params['textKey']) ? $params['textKey'] : '';
        
        $obj['titles'] = $this->GetTextForEditing($obj['titleKey']);
        $obj['texts'] = $this->GetTextForEditing($obj['textKey']);
        
        $obj['languageFrom'] = $this->translator->language;
        $obj['languageTo'] = $lg;
        $obj['languages'] = $this->translator->languages;
        
        $obj['actions'] = array();
        $obj['actions'][] = array('name' => ':SAVE', 'type' => 'primary', 'value' => 'save');
        $obj['actions'][] = array('name' => ':SAVE_AND_SUBMIT', 'type' => 'primary', 'value' => 'submitForValidation');
        if ($this->authentication->CheckRole('Administrator'))
            $obj['actions'][] = array('name' => ':VALIDATE', 'type' => 'primary', 'value' => 'validate');
            
        return 'editArticleTrad';
    }
}
?>