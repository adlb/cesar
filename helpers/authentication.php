<?php

class Authentication {
    
	var $currentUser;
	var $roles;
	
	function SetUser($user) {
        $_SESSION['user'] = $user;
		$this->currentUser = $user;
		$this->roles = explode(';', $user['role']);
		array_push($this->roles, 'Logged');
    }
    
    function Logout() {
        unset($_SESSION['user']);
		$this->currentUser = null;
		$this->roles = array();
    }
    
    function CheckRole($role) {
        return in_array($role, $this->roles);
    }
    
    function Authentication () {
        if(session_id() == "")
            session_start();
		if (isset($_SESSION['user'])) {
			$this->SetUser($_SESSION['user']);
		} else {
			$this->Logout();
		}
		
   }
}

?>