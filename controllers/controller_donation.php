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
    
    function view_donate(&$obj, $param) {
        $obj['currentDonation'] = isset($_SESSION['currentDonation']) ? $_SESSION['currentDonation'] : array('amount' => 10, 'type' => '');
        return 'donate';
    }
    
    function action_donate(&$obj, $param) {
        $donation['amount'] = isset($param['amount']) ? $this->Getfloat($param['amount']) : 0;
        $donation['type'] = isset($param['type']) ? $param['type'] : '';
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
    
    function view_donateCheckname(&$obj, $param) {
        if (!isset($_SESSION['currentDonation']) || $_SESSION['currentDonation']<1 || !in_array($_SESSION['currentDonation']['type'], array('vir', 'cb', 'chq')))
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        if ($this->authentication->currentUser != null) {
            $obj['user'] = $this->authentication->currentUser;
        }
        $obj['currentDonation'] = isset($_SESSION['currentDonation']) ? $_SESSION['currentDonation'] : array('amount' => 10, 'type' => '');
        return 'donateCheckname';
    }
    
    function action_confirm(&$obj, $param) {
        if (!isset($_SESSION['currentDonation']) || $_SESSION['currentDonation']<1 || !in_array($_SESSION['currentDonation']['type'], array('vir', 'cb', 'chq')))
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        $donation = $_SESSION['currentDonation'];
        if ($this->authentication->currentUser != null) {
            $user = $this->authentication->currentUser;
            $donation['userid'] = $user['id'];
            $donation['email'] = $user['email'];
            $donation['firstName'] = $user['firstName'];
            $donation['lastName'] = $user['lastName'];
            $donation['addressLine1'] = $user['addressLine1'];
            $donation['addressLine2'] = $user['addressLine2'];
            $donation['postalCode'] = $user['postalCode'];
            $donation['city'] = $user['city'];
            $donation['country'] = $user['country'];
            $donation['phone'] = $user['phone'];
        } else {
            $donation['userid'] = null;
            $donation['email'] = $param['email'];
            $donation['firstName'] = $param['firstName'];
            $donation['lastName'] = $param['lastName'];
            $donation['addressLine1'] = $param['addressLine1'];
            $donation['addressLine2'] = $param['addressLine2'];
            $donation['postalCode'] = $param['postalCode'];
            $donation['city'] = $param['city'];
            $donation['country'] = $param['country'];
            $donation['phone'] = $param['phone'];
        }
        $donation['externalCheckId'] = '';
        $donation['dateInit'] = date('Y-m-d');
        $donation['dateValidation'] = null;
        $donation['status'] = 'promess';
        
        $_SESSION['currentDonation'] = $donation;
        
        if (!$this->donationDal->TrySave($donation)) {
            // No redirection, we want to finalise the donation in any case
            //$this->webSite->AddMessage('warning', ':CANT_SAVE_DONATION');
            //return $this->view_donate($obj, array());
        }
        
        $_SESSION['currentDonation'] = null;
        unset($_SESSION['currentDonation']);
        
        //Mailer ! fixme.
        
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donateFinalize', 'id' => $donation['id']));
    }
    
    function view_donateFinalize(&$obj, $param) {
        if (!isset($param['id']) || !$this->donationDal->tryGet($param['id'], $donation)) {
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        }
        
        $obj['donation'] = $donation;
        return 'donateFinalize';
    }
    
    function action_donate_old(&$obj, $param) {
        $donation = $param;
        $donation['userid'] = $this->authentication->currentUser['id'];
        $donation['dateInit'] = date('Y-m-d');
        $donation['status'] = 'promess';
        unset($donation['id']);
        unset($donation['dateValidation']);
        unset($donation['rawData']);
        $donation['amount'] = $this->Getfloat($donation['amount']);
        if ($donation['amount'] < 1) {
            $this->webSite->AddMessage('warning', ':DONATION_CANT_BE_LESS_THAN_1_EURO');
            return $this->view_donate($obj, array());
        }
        
        if (in_array($donation['type'], array('vir', 'cb', 'chq')) && $this->donationDal->TrySave($donation)) {
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donation', 'id' => $donation['id']));
        }
        $this->webSite->AddMessage('warning', ':CANT_SAVE_DONATION');
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
    }
    
    
    function action_saveDonation(&$obj, $params) {
        if (!$this->authentication->CheckRole('Administrator')) {
            $this->webSite->AddMessage('warning', ':NOT_ALLOWED');
            $this->webSite->RedirectTo(array('controller' => 'site', 'view' => 'home'));
        }
        
        if (!$this->donationDal->TrySave($params)) {
            $this->webSite->AddMessage('warning', ':CANT_SAVE');
            $obj['form'] = $params;
            return 'editDonation';
        }
        
        $this->webSite->AddMessage('success', ':SAVED');
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donationList'));
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
        
        $obj['form'] = $donation;
        
        return 'editDonation';
    }
    
    function view_donationList(&$obj, $param) {
        $donations = $this->donationDal->GetWhere(array());
        if (isset($param['all']) && $param['all'] == '1') {
            $obj['donations'] = $donations;
        } else {
            $obj['donations'] = array();
            foreach($donations as $donation) {
                if ($donation['status'] != 'archived' && $donation['status'] != 'deleted') {
                    $obj['donations'][] = $donation;
                }
            }
        }
        
        return 'donationList';
    }
    
    
    function view_donation(&$obj, $params) {
        if (!isset($params['id']) || !$this->donationDal->TryGet($params['id'], $donation)) {
            $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION');
            $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
            die();
        }
        $obj['donation'] = $donation;
        if ($donation['type'] == 'vir') {
            return 'virement';
        } elseif ($donation['type'] == 'chq') {
            return 'cheque';
        } elseif  ($donation['type'] == 'cb') {
            $obj['paypalId'] = $this->config->current['PaypalButtonId'];
            return 'paypal';
        }
        $this->webSite->AddMessage('warning', ':CANT_FIND_DONATION_TYPE');
        $this->webSite->RedirectTo(array('controller' => 'donation', 'view' => 'donate'));
        die();
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