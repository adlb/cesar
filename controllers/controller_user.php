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

    function action_register(&$obj, &$view) {
        if (!$this->crowd->TryRegister($_POST, $error)) {
            $this->webSite->AddMessage('warning', $error);
            $obj['form']['email'] = $_POST['email'];
            $view = 'register';
            return;
        }
        $this->journal->LogEvent('user', 'register', $this->authentication->currentUser);
        $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'editUser', 'id' => $_POST['id']));
    }

    function action_saveUser(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator') && $_POST['id'] != $this->authentication->currentUser['id']) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if (!$this->crowd->TryUpdateUser($_POST, $errors)) {
            $obj['errors'] = $errors;
            $obj['form'] = $_POST;
            $view = 'editUser';
            return;
        }

        $this->journal->LogEvent('user', 'updateUser', $this->authentication->currentUser);
        redirectTo(array('controller' => 'site'), $obj['errors']);
    }

    function action_login(&$obj, &$view){
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";

        if (!$this->crowd->TryLogin($email, $password, $error)) {
            $obj['email'] = $email;
            $this->webSite->AddMessage('warning', $error);
            $view = 'login';
            return;
        }
        
        $this->journal->LogEvent('user', 'login', $this->authentication->currentUser);
        $this->webSite->RedirectTo(array('controller' => 'site'));
    }

    function action_delete(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        if ($this->authentication->currentUser['id'] == $_POST['id']) {
            $obj['errors'][] = ':CANT_DELETE_YOURSELF';
            $view = 'userList';
            view_userList($obj, $view);
            $output = array('status' => 'error');
        } else {
            $this->crowd->Delete($_POST['id']);
            $output = array('status' => 'ok');
            $this->journal->LogEvent('user', 'delete', $_POST['id']);
        }

        $obj = $output;
        $view = 'ajax';
    }

    function action_logout(&$obj, &$view){
        $this->authentication->logout();
        redirectTo(array('controller' => 'site'), $obj['errors']);
    }

    function view_login(&$obj, &$view) {
        if ($this->authentication->CheckRole('Logged'))
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'profil'));
    }

    function view_register(&$obj, &$view) {
        if ($this->authentication->CheckRole('Logged') && !$this->authentication->CheckRole('Administrator'))
            $this->webSite->RedirectTo(array('controller' => 'user', 'view' => 'profil'));

        $obj['form'] = array();
    }

    function view_profil(&$obj, &$view) {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
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
    }
    
    function view_editUser(&$obj, &$view) {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
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

        if (isset($_GET['id']) && $this->crowd->TryGet($_GET['id'], $user))
            $obj['form'] = $user;

        if (!isset($obj['form']))
            $obj['form'] = array();
    }

    function view_userList(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $obj['users'] = $this->crowd->GetAll();
    }

    function view_userInsert(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $obj['form'] = isset($obj['form']) ? $obj['form'] : '';
    }

    function action_check(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        $users = file_get_contents("php://input");
        $lines = explode("\n", $users);
        if (!$this->crowd->AnalyzeFirstLine($lines, $analyzedColumns, $errors)) {
            $obj['errors'] = array();
            foreach($errors as $error) {
                $obj['errors'][] = $this->translator->GetTranslation($error);
            }
            $view = 'ajax';
            return;
        }
        $analyse = $this->crowd->AnalyzeLines($lines, $analyzedColumns['columns']);
        $obj['usersAnalysed'] = array('analyzedColumns' => $analyzedColumns, 'lines' => $analyse);
        $view = 'ajax';
    }

    function action_update(&$obj, &$view) {
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
            $obj['errors'][] = 'Nothing to upload';
        }
        $view = 'ajax';
    }

    function view_lostPassword(&$obj, &$view) {
        //nothing to do
    }
    
    function action_retreivePassword(&$obj, &$view) {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        if (!$this->crowd->TryGetFromEmail($email, $user)) {
            $this->webSite->AddMessage('warning', ':THIS_USER_DOES_NOT_EXIST');
            $view = 'lostPassword';
            return;
        }
        if ($this->mailer->TrySendSimpleMail($email, 'SiteWebPasswordRecovery', 'HTML COntent', 'Text Content')) {
            $this->webSite->AddMessage('success', array(':AN_EMAIL_HAS_BEEN_SENT_TO_{0}', $email));
            $this->journal->LogEvent('user', 'retreivePassword', $email);
            $view = 'login';
        } else {
            $this->webSite->AddMessage('warning', array(':CANT_SEND_EMAIL', $email));
            $view = 'login';
        }
    }
}

?>