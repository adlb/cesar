<?php

class Crowd {
    var $salt = 'M4LT3-L184N-By-Adlb';
    var $userDal;
    var $userShortDal;
    var $authentication;

    function Crowd($userDal, $userShortDal, $authentication) {
        $this->userDal = $userDal;
        $this->userShortDal = $userShortDal;
        $this->authentication = $authentication;
    }

    function TryRegister(&$user, &$errors) {
        if (!isset($user['password1']) || !isset($user['password2']) ||
            trim($user['password1']) == '' || trim($user['password2']) == '') {
            $errors[] = ':PASSWORD_EMPTY';
            return false;
        }

        if ($user['password2'] != $user['password1']) {
            $errors[] = ':PASSWORDS_DONT_MATCH';
            return false;
        }

        if (strlen($user['password2']) <= 5) {
            $errors[] = ':PASSWORDS_TOO_SHORT';
            return false;
        }

        if (count($this->userDal->GetWhere(array('email' => $_POST['email']))) > 0) {
            $errors[] = ':USER_EXISTS';
            return false;
        }

        if (count($this->userShortDal->GetWhere(array('role' => 'Administrator'))) == 0) {
            $role = 'Administrator';
        } else {
            $role = 'Simple';
        }

        $userToCreate = array(
            'email' => $user['email'],
            'passwordHashed' => md5($user['password1'].$this->salt),
            'passwordClear' => $user['password1'],
            'role' => $role
        );

        if (!$this->userShortDal->TrySave($userToCreate)) {
            $errors[] = ':CANT_CREATE_USER';
            return false;
        }

        $user['id'] = $userToCreate['id'];
        if (!$this->authentication->currentUser)
            $this->authentication->SetUser($userToCreate);
        return true;
    }

    function TryUpdateUser($user, &$errors) {
        if (!$this->userDal->TryGet($user['id'], $userOld)) {
            $errors[] = ':USER_DOEAS_NOT_EXISTS';
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
            'role' => $userOld['role'],
            'emailStatus' => $user['email'] == $userOld['email'] ? $userOld['emailStatus'] : 'NotValidYet',
            'origin' => $userOld['origin']
        );

        if (!$this->userDal->TrySave($userToUpdate)) {
            $errors[] = ':CANT SAVE USER';
            return false;
        }


        return true;
    }

    function TryLogin($email, $password, &$errors) {

        $users = $this->userShortDal->GetWhere(array('email' => $email));

        if (count($users) == 0) {
            $errors[] = ':UNKOWN_LOGIN';
            return false;
        }

        if (strlen($password) < 5) {
            $errors[] = ':WRONG_PASSWORD';
            return false;
        }

        if ($users[0]['passwordHashed'] != md5($password.$this->salt)) {
            $errors[] = ':WRONG_PASSWORD';
            return false;
        }

        if (!$this->userDal->TryGet($users[0]['id'], $user)) {
            $errors[] = ':INTERNAL_ERROR';
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

        return 'NEW';
    }
}

?>