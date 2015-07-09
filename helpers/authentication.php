<?php

class Authentication {

    var $currentUser;
    var $roles;

    function __construct() {
        if(session_id() == "")
            session_start();
        if (isset($_SESSION['user'])) {
            $this->SetUser($_SESSION['user']);
        } else {
            $this->Logout();
        }
    }
    
    function SetUser($user) {
        $_SESSION['user'] = $user;
        $user['passwordHashed'] = null;
        unset($user['passwordHashed']);
        $this->currentUser = $user;
        $this->roles = explode(';', $user['role']);
        array_push($this->roles, 'Logged');
    }

    function Logout() {
        unset($_SESSION['user']);
        $this->currentUser = null;
        $this->roles = array();
    }

    //Check if $role is in current roles
    //or check if one of $role is in current roles.
    function CheckRole($role) {
        if (is_array($role)) {
            foreach($role as $r) {
                if (in_array($r, $this->roles))
                    return true;
            }
            return false;
        }
        return in_array($role, $this->roles);
    }
}

?>