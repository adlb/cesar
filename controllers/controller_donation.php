<?php

define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('SSL_SAND_URL','https://www.sandbox.paypal.com/cgi-bin/webscr');

class ControllerDonation {

    var $container = 'container';
    var $authentication;
    var $translator;
    var $mailer;
    var $journal;
    var $webSite;
    var $config;
    var $donationDal;
    var $crowd;
    
    function __construct($services) {
        global $webSite;
        $this->webSite = $webSite;
        $this->authentication = $services['authentication'];
        $this->mailer = $services['mailer'];
        $this->translator = $services['translator'];
        $this->journal = $services['journal'];
        $this->config = $services['config'];
        $this->translator->fetchGroup('DONATION');
        $this->donationDal = $services['donationDal'];
        $this->crowd = $services['crowd'];
    }
    
    function view_donate(&$obj, $params) {
        $obj['currentDonation'] = isset($_SESSION['currentDonation']) ? $_SESSION['currentDonation'] : array();
        if (!isset($obj['currentDonation']['amount']))
            $obj['currentDonation']['amount'] = 10;
        $obj['embeddedArticle'] = 'donate';
        $params['titleKey'] = 'Donate';
        $params['renderType'] = 'withoutColumn';
        return $this->webSite->controllerFactory->GetController('site')->view_fixedArticle($obj, $params);
    }
    
    function action_unsaved(&$obj, $params) {
        $donation = isset($_SESSION['currentDonation']) ? $_SESSION['currentDonation'] : array();
        if ($this->authentication->currentUser == null) {
            $donation['saved'] = false;
        }
        $_SESSION['currentDonation'] = $donation;
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donateCheckname'));
    }
    
    function action_donate(&$obj, $params) {
        $donation['amount'] = isset($params['amount']) ? $this->Getfloat($params['amount']) : 0;
        $donation['type'] = isset($params['type']) ? $params['type'] : '';
        $donation['externalCheckId'] = '';
        $donation['dateValidation'] = null;
        $donation['status'] = 'promess';

        if ($this->authentication->currentUser != null) {
            $user = $this->authentication->currentUser;
            $donation['userId'] = $user['id'];
            $donation['email'] = $user['email'];
            $donation['firstName'] = $user['firstName'];
            $donation['lastName'] = $user['lastName'];
            $donation['addressLine1'] = $user['addressLine1'];
            $donation['addressLine2'] = $user['addressLine2'];
            $donation['postalCode'] = $user['postalCode'];
            $donation['city'] = $user['city'];
            $donation['country'] = $user['country'];
            $donation['phone'] = $user['phone'];
            $donation['saved'] = true;
        }
        
        $_SESSION['currentDonation'] = $donation;
        if ($donation['amount'] < 1) {
            $this->webSite->AddMessage('warning', ':DONATION_CANT_BE_LESS_THAN_1_EURO');
            return $this->view_donate($obj, array());
        }
        if (!in_array($donation['type'], array('vir', 'cb', 'chq'))) {
            $this->webSite->AddMessage('warning', ':DONATION_TYPE_IS_UNKNOWN');
            return $this->view_donate($obj, array());
        }
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donateCheckname'));
    }
    
    function view_donateCheckname(&$obj, $params) {
        if (!isset($_SESSION['currentDonation']) || $_SESSION['currentDonation']<1 || !in_array($_SESSION['currentDonation']['type'], array('vir', 'cb', 'chq')))
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        
        $obj['currentDonation'] = $_SESSION['currentDonation'];
        $obj['currentDonation']['isLogged'] = $this->authentication->currentUser != null;
        $obj['embeddedArticle'] = 'donateCheckname';
        $params['titleKey'] = 'DonateCheckName';
        $params['renderType'] = 'withoutColumn';
        
        return $this->webSite->controllerFactory->GetController('site')->view_fixedArticle($obj, $params);
    }

    function action_saveDonationForm(&$obj, $params) {
        if (!isset($_SESSION['currentDonation']) || $_SESSION['currentDonation']<1 || !in_array($_SESSION['currentDonation']['type'], array('vir', 'cb', 'chq')))
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        
        $donation = $_SESSION['currentDonation'];
        $donation['email'] = $params['email'];
        $donation['firstName'] = $params['firstName'];
        $donation['lastName'] = $params['lastName'];
        $donation['addressLine1'] = $params['addressLine1'];
        $donation['addressLine2'] = $params['addressLine2'];
        $donation['postalCode'] = $params['postalCode'];
        $donation['city'] = $params['city'];
        $donation['country'] = $params['country'];
        $donation['phone'] = $params['phone'];
        $donation['externalCheckId'] = '';
        $donation['dateInit'] = date('Y-m-d H:i:s');
        $donation['dateValidation'] = null;
        $donation['status'] = 'promess';
        $donation['saved'] = true;
        $_SESSION['currentDonation'] = $donation;
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donateCheckName'));
    }

    function action_confirm(&$obj, $params) {
        if (!isset($_SESSION['currentDonation']) || $_SESSION['currentDonation']<1 || !in_array($_SESSION['currentDonation']['type'], array('vir', 'cb', 'chq')))
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        $donation = $_SESSION['currentDonation'];
        
        if (!$this->crowd->TryGetFromEmail($donation['email'], $previousUser)) {
            if ($this->crowd->TryRegister($donation['email'], '', $error) &&
                $this->crowd->TryGetFromEmail($donation['email'], $user)) {
                $user['firstName'] = $donation['firstName'];
                $user['lastName'] = $donation['lastName'];
                $user['addressLine1'] = $donation['addressLine1'];
                $user['addressLine2'] = $donation['addressLine2'];
                $user['postalCode'] = $donation['postalCode'];
                $user['city'] = $donation['city'];
                $user['country'] = $donation['country'];
                $user['phone'] = $donation['phone'];
                $this->crowd->TryUpdateUser($user, $error);
            }
        }
        
        if (!$this->donationDal->TrySave($donation)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE_DONATION');
            return $this->view_donate($obj, array());
        }
        
        $_SESSION['currentDonation'] = null;
        unset($_SESSION['currentDonation']);
        
        $key = $this->donationDal->GetDonationKey($donation['id'], $this->config->current['SecretLine']);
        $donation['linkForDonation'] = url(array(
            'controller' => 'donation', 
            'view' => 'donateFinalize', 
            'id' => $donation['id'], 
            'key' => $key),$this->webSite->urlPrefix);
        if (!$this->mailer->TrySendDonationConfirmationMail($donation)) {
            $this->webSite->AddMessage('warning', ':IT_WAS_IMPOSSIBLE_TO_SEND_YOU_A_CONFIRMATION_EMAIL');
        }
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donateFinalize', 'id' => $donation['id'], 'key' => $key));
    }
    
    function view_donateFinalize(&$obj, $params) {
        if (!isset($params['id']) || !$this->donationDal->tryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':DONATION_DOES_NOT_EXIST');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        }
        
        if (!$this->authentication->CheckRole('Administrator') &&
            $donation['userid'] != $this->authentication->currentUser['id'] &&
            !(isset($params['key']) && $this->donationDal->CheckDonationKey($params['id'], $this->config->current['SecretLine'], $params['key']))
            ) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        }
        
        $obj['donation'] = $donation;
        $obj['embeddedArticle'] = 'donateFinalize';
        $params['titleKey'] = 'DonateThanksForDonation';
        $params['renderType'] = 'withoutColumn';
        return $this->webSite->controllerFactory->GetController('site')->view_fixedArticle($obj, $params);
    }
    
    function action_saveDonation(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        $redirect = isset($param['callback']) ? $param['callback'] : url(array('controller' => 'donation', 'view' => 'donationList'));
        
        if (!$this->donationDal->TrySave($params)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE');
            $obj['form'] = $params;
            $obj['callback'] = $redirect;
            return 'editDonation';
        }
        
        
        $this->webSite->AddMessage('success', ':SAVED');
        $this->webSite->RedirectTo($redirect);
    }
    
    function view_edit(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donationList'));
        }
        
        $obj['callback'] = isset($params['callback']) ? $params['callback'] : url(array('controller' => 'donation', 'view' => 'donationList'));
        $obj['form'] = $donation;
        
        return 'editDonation';
    }
    
    function view_donationList(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        $isFiltered = isset($params['isFiltered']) ? $params['isFiltered'] : true;
        
        if ($isFiltered) {
            $obj['isFiltered'] = true;
            $donations = $this->donationDal->GetWhere(array('status' => array('promess', 'received', 'validated')));
        } else {
            $obj['isFiltered'] = false;
            $donations = $this->donationDal->GetWhere(array());
        }
        $obj['donations'] = $donations;
        $obj['callback'] = url(array('controller' => 'donation', 'view' => 'donationList', 'isFiltered' => $isFiltered));
        
        return 'donationList';
    }
    
    public function action_cancel(&$obj, $params) {
        $donation = GetDonationForChange($params);
        
        $donation['status'] = 'cancelled';
        
        if (!$this->donationDal->TrySave($donation)) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
        } else {
            $this->webSite->AddMessage('info', ':DONATION_CANCELLED');
        }
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        die();
    }
    
    public function action_receive(&$obj, $params) {
        $donation = GetDonationForChange($params);
        
        $donation['status'] = 'received';
        
        if (!$this->donationDal->TrySave($donation)) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
        } else {
            $this->webSite->AddMessage('info', ':DONATION_RECEIVED');
        }
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        die();
    }
    
    function action_delete(&$obj, $params) {
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
            die();
        }
        if ($this->authentication->currentUser == null || 
            ($donation['userid'] != $this->authentication->currentUser['id'] &&
            !$this->authentication->CheckRole('Administrator'))) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
            die();
        }
        
        $donation['status'] = 'deleted';
        if ($this->donationDal->TrySave($donation)) {
            $obj['status'] = 'ok';
        } else {
            $obj['status'] = 'nok';
        }
        return;
    }
    
        
    function action_validate(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $obj['status'] = 'nok';
            return;
        }
        
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $obj['status'] = 'nok';
            return;
        }
        
        $donation['status'] = 'validated';
        if ($this->donationDal->TrySave($donation)) {
            $obj['status'] = 'ok';
        } else {
            $obj['status'] = 'nok';
        }
        return;
    }
    
    function action_archive(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $obj['status'] = 'nok';
            return;
        }
        
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $obj['status'] = 'nok';
            return;
        }
        
        if ($donation['status'] != 'validated') {
            $this->webSite->AddMessage('warning', ':YOU_CAN_ONLY_ARCHIVE_VALIDATED_DONATION');
            $obj['status'] = 'nok';
            return;
        }
        
        $donation['status'] = 'archived';
        if ($this->donationDal->TrySave($donation)) {
            $obj['status'] = 'ok';
        } else {
            $obj['status'] = 'nok';
        }
        return;
    }
    
    function view_export(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        $isFiltered = isset($params['isFiltered']) ? $params['isFiltered'] : true;
        
        if ($isFiltered) {
            $obj['isFiltered'] = true;
            $donations = $this->donationDal->GetWhere(array('status' => array('promess', 'received', 'validated')));
        } else {
            $obj['isFiltered'] = false;
            $donations = $this->donationDal->GetWhere(array());
        }
        $obj['fileName'] = "donations";
        $obj['fileContent'] = $donations;
        return null;
    }
    
    private function GetDonationForChange($params) {
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
            die();
        }
        if ($this->authentication->currentUser == null || 
            ($donation['userId'] != $authentication->currentUser['id'] &&
            !$this->authentication->CheckRole('Administrator'))) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
            die();
        }
        return $donation;
    }
    
    private function Getfloat($str) {
        if(strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }

        if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
            return floatval($match[0]);
        } else {
            return floatval($str); // take some last chances with floatval
        }
    }
}