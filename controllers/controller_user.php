<?php

class ControllerUser {
	
	var $container = 'container';
    var $salt = 'M4LT3-L184N-By-Adlb';
    var $authentication;
    var $crowd;
    
    function ControllerUser($services) {
        $this->authentication = $services['authentication'];
        $this->crowd = $services['crowd'];
    }

	function action_register(&$obj, &$view) {
		if (!$this->crowd->TryRegister($_POST, $errors)) {
			$obj['errors'] = $errors;
			$obj['form']['email'] = $_POST['email'];
			$view = 'register';
			return;
		}
		redirectTo(array('controller' => 'user', 'view' => 'editUser', 'id' => $_POST['id']));
	}
	
    function action_saveUser(&$obj, &$view) {
		if (!$this->crowd->TryUpdateUser($_POST, $errors)) {
			$obj['errors'] = $errors;
			$obj['form'] = $_POST;
            $view = 'editUser';
			return;
		}
	
		redirectTo(array('controller' => 'site'));
    }
    
	function action_login(&$obj, &$view){
		$email = isset($_POST['email']) ? $_POST['email'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
        
        if (!$this->crowd->TryLogin($email, $password, $errors)) {
		    $obj['email'] = $email;
            $obj['errors'] = $errors;
			$view = 'login';
            return;
        }
		
		redirectTo(array('controller' => 'site'));
	}
	
	function action_delete(&$obj, &$view) {
		if ($this->authentication->currentUser['id'] == $_POST['id']) {
			$obj['errors'][] = ':CANT_DELETE_YOURSELF';
			$view = 'userList';
			view_userList($obj, $view);
			$output = array('status' => 'error');
		} else {
			$this->crowd->Delete($_POST['id']);
			$output = array('status' => 'ok');
		}
		
		$obj = $output;
		$view = 'ajax';
	}
	
    function action_logout(&$obj, &$view){
        $this->authentication->logout();
		redirectTo(array('controller' => 'site'));
    }
	
	function view_login(&$obj, &$view) {
		if ($this->authentication->CheckRole('Logged'))
            redirectTo(array('controller' => 'user', 'view' => 'alreadyLogged'));
    }
	
	function view_register(&$obj, &$view) {
	    if ($this->authentication->CheckRole('Logged') && !$this->authentication->CheckRole('Administrator'))
            redirectTo(array('controller' => 'user', 'view' => 'alreadyLogged'));
		
		$obj['form'] = array();
	}
	
	function view_editUser(&$obj, &$view) {
        //if ($this->authentication->CheckRole('Logged') && !$this->authentication->CheckRole('Administrator'))
        //    redirectTo(array('controller' => 'user', 'view' => 'alreadyLogged'));
        
		$obj['isAdministrator'] = $this->authentication->CheckRole('Administrator');
		
		if (isset($_GET['id']) && $this->crowd->TryGet($_GET['id'], $user))
            $obj['form'] = $user;
		
        if (!isset($obj['form']))
            $obj['form'] = array();
    }
	
	function view_userList(&$obj, &$view) {
        $obj['users'] = $this->crowd->GetAll();
    }

	function view_userInsert(&$obj, &$view) {
		$obj['form'] = isset($obj['form']) ? $obj['form'] : '';
		$obj['insertDisabled'] = false;
	}
	
	function action_check(&$obj, &$view) {
		$users = file_get_contents("php://input");
		$lines = explode("\n", $users);
		if (!$this->crowd->AnalyzeFirstLine($lines, $analyzedColumns, $errors)) {
			$obj = array('errors' => array_map('translate', $errors));
			$view = 'ajax';
			return;
		}
		$analyse = $this->crowd->AnalyzeLines($lines, $analyzedColumns['columns']);
		$obj['usersAnalysed'] = array('analyzedColumns' => $analyzedColumns, 'lines' => $analyse);
		$view = 'ajax';
	}
	
	function action_update(&$obj, &$view) {
		if (isset($_POST['lines'])) {
			$lines = $_POST['lines'];
			foreach($lines as &$line) {
				$errors = array();
				if (!$this->crowd->Update($line['object'], $errors))
					$line['status'] = 'ERROR_WHILE_UPLOADING';
				else
					$line['status'] = 'UP_TO_DATE';
				unset($line);
			}
			$obj['lines'] = $lines;
		} else {
			$obj['errors'][] = 'Nothing to upload';
		}
		$view = 'ajax';
	}
	
	private function Mapper($array) {
		return array_map(array($this, 'GetLines'), $array);
	}
	
	private function GetLines($object) {
		return $object['line'];
	}
}

?>