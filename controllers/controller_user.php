<?php

class ControllerUser {

    var $container = 'container';
    var $salt = 'M4LT3-L184N-By-Adlb';
    var $authentication;
    var $crowd;
    var $translator;
    var $mailer;
    var $webSite;

    function ControllerUser($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->authentication = $services['authentication'];
        $this->crowd = $services['crowd'];
        $this->mailer = $services['mailer'];
        $this->translator = $services['translator'];
    }

    function action_register(&$obj, &$view) {
        if (!$this->crowd->TryRegister($_POST, $errors)) {
            $obj['errors'] = $errors;
            $obj['form']['email'] = $_POST['email'];
            $view = 'register';
            return;
        }
        redirectTo(array('controller' => 'user', 'view' => 'editUser', 'id' => $_POST['id']), $obj['errors']);
    }

    function action_saveUser(&$obj, &$view) {
        if (!$this->crowd->TryUpdateUser($_POST, $errors)) {
            $obj['errors'] = $errors;
            $obj['form'] = $_POST;
            $view = 'editUser';
            return;
        }

        redirectTo(array('controller' => 'site'), $obj['errors']);
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

        redirectTo(array('controller' => 'site'), $obj['errors']);
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
        redirectTo(array('controller' => 'site'), $obj['errors']);
    }

    function view_login(&$obj, &$view) {
        if ($this->authentication->CheckRole('Logged'))
            redirectTo(array('controller' => 'user', 'view' => 'alreadyLogged'), $obj['errors']);
    }

    function view_register(&$obj, &$view) {
        if ($this->authentication->CheckRole('Logged') && !$this->authentication->CheckRole('Administrator'))
            redirectTo(array('controller' => 'user', 'view' => 'alreadyLogged'), $obj['errors']);

        $obj['form'] = array();
    }

    function view_editUser(&$obj, &$view) {
        if (!$this->authentication->CheckRole('Administrator') &&
            ($this->authentication->currentUser == null ||
            $_GET['id'] != $this->authentication->currentUser['id']))
            redirectTo(array('controller' => 'site', 'view' => 'home'), $obj['errors']);

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
            $view = 'login';
        } else {
            $this->webSite->AddMessage('warning', array(':CANT_SEND_EMAIL', $email));
            $view = 'login';
        }
    }
}

?>