<?php

require_once ('dal.php');

class ArticleDal extends Dal {
    var $fields = array(
        'id' => array('create' => 'int(11) NOT NULL AUTO_INCREMENT', 'bind' => PDO::PARAM_INT, 'primaryKey' => true),
        'type' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'father' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT, 'key' => true),
        'titleKey' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR, 'key' => true),
        'date' => array('create' => 'date', 'bind' => PDO::PARAM_STR),
        'textKey' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR),
        'imageId' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT),
        'rank' => array('create' => 'int(11)', 'bind' => PDO::PARAM_INT),
        'alert' => array('create' => 'boolean', 'bind' => PDO::PARAM_BOOL),
        'needLogin' => array('create' => 'boolean', 'bind' => PDO::PARAM_BOOL),
        'datealert' => array('create' => 'date', 'bind' => PDO::PARAM_STR),
        'status' => array('create' => 'varchar(255)', 'bind' => PDO::PARAM_STR)
        //Can be
        // * show
        // * hide
    );
    var $keyName = 'id';
    var $tableSuffix = "articles";

    function GetFathersForMenu() {
        return $this->GetWhere(array('type' => 'menu'));
    }

    function GetFathersForNews() {
        return $this->GetWhere(array('type' => 'article'));
    }
    
    function GetFixedArticle($titleKey) {
        $articles = $this->GetWhere(array('titleKey' => $titleKey));
        if (count($articles) > 0)
            return $articles[0];
        
        $fixedMenu = $this->GetWhere(array('titleKey' => 'FixedArticleMenu'));
        if (count($fixedMenu) == 0) {
            $menu = array(
                'type' => 'menu',
                'father' => '-1',
                'titleKey' => 'FixedArticleMenu',
                'date' => '0000-00-00',
                'textKey' => '',
                'imageId' => '',
                'rank' => 100000,
                'alert' => false,
                'needLogin' => false,
                'datealert' => '',
                'status' => 'show'
            );
            $this->TrySave($menu);
        } else {
            $menu = $fixedMenu[0];
        }
        $article = array(
            'type' => 'article',
            'father' => $menu['id'],
            'titleKey' => $titleKey,
            'date' => '0000-00-00',
            'textKey' => $titleKey.'_content',
            'imageId' => '',
            'rank' => 1,
            'alert' => false,
            'needLogin' => false,
            'datealert' => '',
            'status' => 'hide'
        );
        
        if (substr($titleKey,0,5) != 'file:')
            $this->TrySave($article);
        else
            $article['id'] = null;
        return $article;
    }
}

?>