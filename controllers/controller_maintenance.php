<?php

class ControllerMaintenance {

	var $articleDal;
	var $textDal;
	var $mediaDal;
	var $userDal;
	var $config;
	var $authentication;

	function ControllerMaintenance($services) {
		$this->config = $services['config'];
		$this->authentication = $services['authentication'];
		$this->articleDal = $services['articleDal'];
		$this->textDal = $services['textDal'];
		$this->mediaDal = $services['mediaDal'];
		$this->userDal = $services['userDal'];
	}
	
	function action_reCreateTables(&$obj) {
		if (!$this->authentication->CheckRole('Administrator'))
			redirectTo(array('controller' => 'site', 'view' => 'home'));
		
		$this->articleDal->DropTable();
		$this->articleDal->CreateTable();
		$this->textDal->DropTable();
		$this->textDal->CreateTable();
		$this->mediaDal->DropTable();
		$this->mediaDal->CreateTable();
		$this->userDal->DropTable();
		$this->userDal->CreateTable();
		
		redirectTo(array('controller' => 'site', 'view' => 'home'));
	}

	function action_deleteConfig(&$obj) {
		$this->config->deleteConfig();
		redirectTo(array('controller' => 'site', 'view' => 'home'));
	}
}
?>