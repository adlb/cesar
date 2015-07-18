<?php

class Crowd {
    var $salt;
    var $userDal;
    var $userShortDal;
    var $authentication;

    function __construct($salt, $userDal, $userShortDal, $authentication) {
        $this->salt = $salt;
        $this->userDal = $userDal;
        $this->userShortDal = $userShortDal;
        $this->authentication = $authentication;
    }

    function GetTimes($email) {
        $res = $this->userDal->GetWhere(array('email' => $email));
        $nb = 0;
        if (count($res) == 0) {
            $nb = 1000;
        } else {
            $nb = $res[0]['times'] - 1;
        }
        return $nb;
    }
    
    function TryGetKey($email, &$key) {
        if (!$this->TryGetFromEmail($email, $user)) {
            return false;
        }
        $key = md5($this->salt.$user['email'].$user['times'].$user['id']);
        return true;
    }
    
    function CheckKey($email, $key, &$user) {
        if (!$this->TryGetFromEmail($email, $user)) {
            return false;
        }
        $keyTrue = (md5($this->salt.$user['email'].$user['times'].$user['id']));
        return ($key == $keyTrue);
    }
    
    function TryUpdateUserPassword($user, $passwordHashed) {
        $user['passwordHashed'] = md5($passwordHashed.$this->salt);
        $user['times'] = 1000;
        return $this->userDal->TrySave($user);
    }
    
    function TryRegister($email, $password, &$error) {
        if (filter_var($email_a, FILTER_VALIDATE_EMAIL))
            return false;

        $users = $this->userDal->GetWhere(array('email' => $email));
        
        if (!isset($password) || trim($password) == '') {
            if (count($users) != 0) {
                if ($users[0]['emailStatus'] == 'OptOut') {
                    $users[0]['emailStatus'] == 'Validated';
                    $this->userDal->TrySave($users[0]);
                    return true;
                }
                $error = ":YOU_HAVE_ALREADY_SUBSCRIBE_TO_NEWSLETTER";
                return false;
            }
            $userToCreate = array(
                'email' => $email,
                'passwordHashed' => '',
                'times' => 1001,
                'role' => 'NewsLetter',
                'origin' => 'NewsLetter'
            );
            if (!$this->userShortDal->TrySave($userToCreate)) {
                $error = ':CANT_CREATE_USER';
                return false;
            }
            return true;
        }

        if (count($users) > 0) {
            $error = ':USER_EXISTS';
            return false;
        }

        if (count($this->userShortDal->GetWhere(array('role' => 'Administrator'))) == 0) {
            $role = 'Administrator';
        } else {
            $role = 'Visitor';
        }

        $userToCreate = array(
            'email' => $email,
            'passwordHashed' => md5($password.$this->salt),
            'times' => 1000,
            'role' => $role
        );

        if (!$this->userShortDal->TrySave($userToCreate)) {
            $error = ':CANT_CREATE_USER';
            return false;
        }

        if (!$this->userDal->TryGet($user['id'], $user)) {
            $error = ':CANT_CREATE_USER';
            return false;
        }
        
        if (!$this->authentication->currentUser)
            $this->authentication->SetUser($user);

        return true;
    }

    function TryUpdateUser($user, &$error) {
        if (!isset($user['id']) || !$this->userDal->TryGet($user['id'], $userOld)) {
            $error = ':USER_DOEAS_NOT_EXISTS';
            return false;
        }

        if ($this->authentication->CheckRole('Administrator')) {
            $role = $user['role'];
        } else {
            $role = $userOld['role'];
        }
        
        if ($userOld['role'] == 'Administrator' && $role != 'Administrator' && $this->authentication->currentUser['id'] == $user['id']) {
            $error = ':CANT_REMOVE_ADMIN_RIGHTS_TO_YOURSELF';
            return false;
        }
        
        
        $userToUpdate = array(
            'id' => $user['id'],
            'email' => $user['email'],
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'passwordHashed' => $userOld['passwordHashed'],
            'addressLine1' => $user['addressLine1'],
            'addressLine2' => $user['addressLine2'],
            'postalCode' => $user['postalCode'],
            'city' => $user['city'],
            'country' => $user['country'],
            'phone' => $user['phone'],
            'role' => $role,
            'emailStatus' => $user['email'] == $userOld['email'] ? $userOld['emailStatus'] : 'NotValidYet',
            'origin' => $userOld['origin']
        );

        if (!$this->userDal->TrySave($userToUpdate)) {
            $error = ':CANT SAVE USER';
            return false;
        }

        if ($this->authentication->currentUser['id'] == $userToUpdate['id'])
            $this->authentication->SetUser($userToUpdate);
        
        return true;
    }

    function TryLoginOrCreate($email, $password, &$error) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = 'EMAIL_INVALID';
            return 'false';
        }
        
        $users = $this->userShortDal->GetWhere(array('email' => $email));
        
        if (count($users) == 0) {
            return $this->TryRegister($email, $password, $error);
        }
        
        $user = $users[0];

        if (strlen($password) < 5) {
            $error = ':WRONG_PASSWORD1';
            return false;
        }

        $pw = $password;
        for($i = $user['times']-1; $i<1000; $i++)
            $pw = md5($pw);
        
        $pw = md5($pw.$this->salt);
        
        if ($user['role'] == 'NewsLetter') {
            $user['passwordHashed'] = $pw;
        }
        
        if ($user['passwordHashed'] != $pw) {
            $error = ':WRONG_PASSWORD2';
            return false;
        }

        $user['times'] = $user['times']-1;
        if (!$this->userDal->TrySave($user)) {
            $error = ':INTERNAL_ERROR';
            return false;
        }

        if (!$this->userDal->TryGet($user['id'], $user)) {
            $error = ':CANT_LOAD_USER';
            return false;
        }
        
        $this->authentication->SetUser($user);
        return true;
    }

    function Delete($id) {
        $this->userDal->DeleteWhere(array('id' => $id));
    }

    function GetAll() {
        return $this->userDal->GetWhere(array(), array('email' => true));
    }

    function TryGet($id, &$user) {
        return $this->userDal->TryGet($id, $user);
    }

    function TryGetFromEmail($email, &$user) {
        $users = $this->userDal->GetWhere(array('email' => $email));
        if (count($users) == 0)
            return false;
        $user = $users[0];
        return true;
    }
    
    function Update($object) {
        return $this->userDal->TrySave($object);
    }

    function AnalyzeFirstLine($lines, &$analyzedColumns, &$errors) {
        if (count($lines) < 2) {
            $errors[] = ':TOO_FEW_LINES';
            return false;
        }
        $ready = true;
        $headLine = array_shift($lines);
        $columnTitles = explode(';', $headLine);
        $authorisedTitles = array('email', 'firstName', 'lastName', 'addressLine1', 'addressLine2', 'postalCode', 'city', 'country', 'phone');
        $columnsSet = array();
        $columns = array();
        foreach($columnTitles as $title) {
            $title = trim($title);
            if (in_array($title, $columnsSet)) {
                $errors[] = array(':COLUMN_DEFINED_TWICE_{0}', $title);
                $ready = false;
            }
            if (!in_array($title, $authorisedTitles)) {
                $errors[] = array(':NOT_AUTHORIZED_TITLE_{0}', $title);
                $columns[] = array('title' => $title, 'keep' => false);
                $ready = false;
            } else {
                $columns[] = array('title' => $title, 'keep' => true);
                $columnsSet[] = $title;
            }
        }

        if (!in_array('email', $columnsSet)) {
            $errors[] = ':NEED_AT_LEAST_EMAIL_FIELD';
            $ready = false;
        }
        
        $analyzedColumns = array('rawLine' => $headLine, 'columns' => $columns);
        return $ready;
    }

    function AnalyzeLines($lines, $columns) {
        //remove titles
        array_shift($lines);

        $results = array();
        $emailSet = array();
        $i = 0;
        while ($line = array_shift($lines)) {
            if (trim($line) == '')
                continue;
            $status = $this->GetValidationStatus($columns, $line, $emailSet, $object);
            $newResult = array('index' => $i, 'rawLine' => $line, 'object' => $object, 'status' => $status);
            if (isset($object['email']))
                $newResult['email'] = $object['email'];
            if (isset($object['lastName']))
                $newResult['lastName'] = $object['lastName'];

            if (isset($object['email']))
                $emailSet[$object['email']] = &$newResult;

            $results[] = &$newResult;
            unset($newResult);
            $i++;
        }

        return $results;
    }

    private function GetValidationStatus($columns, &$line, &$emailSet, &$potentialResult) {
        $potentialResult = array();
        $fields = explode(';', $line);
        if (count($fields) < count($columns)) {
            return 'ERROR_FIELDS_ARE_MISSING';
        }

        $potentialResult = array();
        for($i = 0; $i < count($fields); $i++) {
            if ($columns[$i]['keep']) {
                $potentialResult[$columns[$i]['title']] = trim($fields[$i]);
            }
        }
        if (!filter_var($potentialResult['email'], FILTER_VALIDATE_EMAIL)) {
            return 'ERROR_INVALID_EMAIL';
        }

        if (in_array($potentialResult['email'], array_keys($emailSet))) {
            $temp = &$emailSet[$potentialResult['email']];
            $temp['status'] = 'ERROR_EMAIL_TWICE';
            return 'ERROR_EMAIL_TWICE';
        }

        $twins = $this->userDal->GetWhere(array('email' => $potentialResult['email']));
        if ((count($twins) != 0) && ($twin = $twins[0])) {
            $potentialResult['id'] = $twin['id'];
            foreach($potentialResult as $title => $value) {
                if ($twin[$title] != $value) {
                    return 'TO_UPDATE';
                }
            }
            return 'UP_TO_DATE';
        }

        $potentialResult['times'] = 1001;
        $potentialResult['role'] = 'newsLetter';
        $potentialResult['origin'] = 'ExternalEntry';
        
        return 'NEW';
    }
    
    
}

?>