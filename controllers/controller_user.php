<?php

class ControllerUser {

    var $container = 'container';
    var $authentication;
    var $crowd;
    var $translator;
    var $mailer;
    var $journal;
    var $webSite;

    function ControllerUser($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->authentication = $services['authentication'];
        $this->crowd = $services['crowd'];
        $this->mailer = $services['mailer'];
        $this->translator = $services['translator'];
        $this->journal = $services['journal'];
    }

    function action_register(&$obj, $params) {
        if (!$this->crowd->TryRegister($params, $error)) {
            $this->webSite->AddMessage('warning', $error);
            $obj['form']['email'] = $params['email'];
            return 'register';
        }
        $this->journal->LogEvent('user', 'register', $this->authentication->currentUser);
        $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'editUser', 'id' => $this->authentication->currentUser['id']));
    }

    function action_saveUser(&$obj, $params) {
        if (isset($params['callback']) && $params['callback'] != '') {
            $redirect = $params['callback'];
        } else {
            $redirect = url(array('controller' => 'site'));
        }
    
        if (!$this->authentication->CheckRole('Administrator') && $params['id'] != $this->authentication->currentUser['id']) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!$this->crowd->TryUpdateUser($params, $error)) {
            $this->webSite->AddMessage('warning', $error);
            $obj['form'] = $params;
            $obj['callback'] = $redirect;
            return 'editUser';
        }

        $this->journal->LogEvent('user', 'updateUser', $this->authentication->currentUser);
        $this->webSite->RedirectTo($redirect);
    }

    function action_login(&$obj, $params){
        if (isset($params['callback'])) {
            $redirect = $params['callback'];
        } else {
            $redirect = url(array('controller' => 'site'));
        }
    
        $email = isset($params['email']) ? $params['email'] : "";
        $password = isset($params['password']) ? $params['password'] : "";

        if (!$this->crowd->TryLogin($email, $password, $error)) {
            $this->webSite->AddMessage('warning', $error);
            $obj['email'] = $email;
            $obj['GetTimesUrl'] = url(array('controller' => 'user', 'view' => 'nbTimes'));
            $obj['callback'] = $redirect;
            return 'login';
        }
        
        $this->journal->LogEvent('user', 'login', $this->authentication->currentUser);
        $this->webSite->RedirectTo($redirect);
    }
    
    function view_nbTimes(&$obj, $params) {
        if (!isset($params['email']))
            $obj['nb'] = $this->crowd->GetTimes(''); 
        else
            $obj['nb'] = $this->crowd->GetTimes($params['email']);
    }

    function action_delete(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if ($this->authentication->currentUser['id'] == $params['id']) {
            $this->webSite->AddMessage('warning', ':CANT_DELETE_YOURSELF');
            $output = array('status' => 'error');
        } else {
            $this->crowd->Delete($params['id']);
            $output = array('status' => 'ok');
            $this->journal->LogEvent('user', 'delete', $_POST['id']);
        }

        $obj = $output;
    }

    function action_logout(&$obj, $params){
        $this->authentication->logout();
        $this->webSite->RedirectTo(array('controller' => 'site'));
    }

    function view_login(&$obj, $params) {
        if (isset($params['callback'])) {
            $redirect = $params['callback'];
        } else {
            $redirect = url(array('controller' => 'user', 'view' => 'profil'));
        }
    
        if ($this->authentication->CheckRole('Logged'))
            $this->webSite->RedirectTo($redirect);
        
        $obj['GetTimesUrl'] = url(array('controller' => 'user', 'view' => 'nbTimes'));
        $obj['callback'] = $redirect;
        return 'login';
    }

    function view_register(&$obj, $params) {
        if ($this->authentication->CheckRole('Logged') && !$this->authentication->CheckRole('Administrator'))
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'profil'));

        $obj['form'] = array();
        return 'register';
    }

    function view_profil(&$obj, $params) {
        $id = isset($params['id']) ? $params['id'] : '';
        if ($id == '') {
            if (isset($this->authentication->currentUser)) {
                $id = $this->authentication->currentUser['id'];
            }
        }
        
        if (!$this->authentication->CheckRole('Administrator') &&
            $id != $this->authentication->currentUser['id']) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'home'));
        }
        
        if (!$this->crowd->TryGet($id, $user)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_USER');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'home'));
        }
        
        $obj['user'] = $user;
        return 'profil';
    }
    
    function view_editUser(&$obj, $params) {
        $id = isset($params['id']) ? $params['id'] : '';
        if ($id == '') {
            if (isset($this->authentication->currentUser)) {
                $id = $this->authentication->currentUser['id'];
            }
        }
        
        if (!$this->authentication->CheckRole('Administrator') &&
            $id != $this->authentication->currentUser['id']) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'home'));
        }
        
        $obj['isAdministrator'] = $this->authentication->CheckRole('Administrator');

        if (isset($params['id']) && $this->crowd->TryGet($params['id'], $user))
            $obj['form'] = $user;

        if (!isset($obj['form']))
            $obj['form'] = array();
        
        $obj['callback'] = isset($params['callback']) ? $params['callback'] : '';
        return 'editUser';
    }

    function view_userList(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $obj['users'] = $this->crowd->GetAll();
        
        return 'userList';
    }

    function view_userInsert(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $obj['form'] = isset($obj['form']) ? $obj['form'] : '';
        
        return 'userInsert';
    }

    function action_check(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $users = $params['rawData'];
        $lines = explode("\n", $users);
        if (!$this->crowd->AnalyzeFirstLine($lines, $analyzedColumns, $errors)) {
            foreach($errors as $error) {
                $this->webSite->AddMessage('warning', $error);
            }
            return;
        }
        $analyse = $this->crowd->AnalyzeLines($lines, $analyzedColumns['columns']);
        $obj['usersAnalysed'] = array('analyzedColumns' => $analyzedColumns, 'lines' => $analyse);
    }

    function action_update(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'home'));
        }
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
            $this->webSite->AddMessage('warning', ':Nothing_to_upload');
        }
    }

    function view_lostPassword(&$obj, $params) {
        //nothing to do
        return 'lostPassword';
    }
    
    function action_retreivePassword(&$obj, $params) {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        if (!$this->crowd->TryGetFromEmail($email, $user)) {
            $this->webSite->AddMessage('warning', ':THIS_USER_DOES_NOT_EXIST');
            return 'lostPassword';
        }
        if ($this->mailer->TrySendForgotPasswordMail($email)) {
            $this->webSite->AddMessage('success', array(':AN_EMAIL_HAS_BEEN_SENT_TO_{0}', $email));
            $this->journal->LogEvent('user', 'retreivePassword', $email);
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'login'));
        } else {
            $this->webSite->AddMessage('warning', array(':CANT_SEND_EMAIL', $email));
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'login'));
        }
    }
    
    function view_resetPassword(&$obj, $params) {
        if (!isset($params['email'])) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!isset($params['key'])) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!$this->crowd->CheckKey($params['email'], $params['key'], $user)) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $obj['email'] = $params['email'];
        $obj['key'] = $params['key'];
        return 'resetPassword';
    }
    
    function action_resetPassword(&$obj, $params) {
        if (!isset($params['email'])) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!isset($params['key'])) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!$this->crowd->CheckKey($params['email'], $params['key'], $user)) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        $obj['email'] = $params['email'];
        $obj['key'] = $params['key'];
        
        if (!isset($params['password1'])) {
            $this->webSite->AddMessage('warning', ':NO_PASSWORD1');
        } elseif (!isset($params['password2'])) {
            $this->webSite->AddMessage('warning', ':NO_PASSWORD2');
        } elseif ($params['password1'] != $params['password2']) {
            $this->webSite->AddMessage('warning', ':PASSWORD_ARE_NOT_THE_SAME');
        } elseif ($this->crowd->TryUpdateUserPassword($user, $params['password1'])) {
            $this->webSite->AddMessage('success', ':PASSWORD_RESETED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        return 'resetPassword';
    }
}

?>