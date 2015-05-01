<?php

class ControllerMaintenance {

    var $articleDal;
    var $textDal;
    var $mediaDal;
    var $userDal;
    var $config;
    var $authentication;
    var $webSite;

    function ControllerMaintenance($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->config = $services['config'];
        $this->authentication = $services['authentication'];
        $this->articleDal = $services['articleDal'];
        $this->textDal = $services['textDal'];
        $this->mediaDal = $services['mediaDal'];
        $this->userDal = $services['userDal'];
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
        $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);
    }
}
?>