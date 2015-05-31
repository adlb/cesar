<?php

class ControllerDonation {

    var $container = 'container';
    var $authentication;
    var $translator;
    var $mailer;
    var $journal;
    var $webSite;
    var $config;
    var $donationDal;

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
    }
    
    function view_donate(&$obj, $param) {
        if ($this->authentication->currentUser == null) {
            return 'needLogin';
        } else {
            $obj['user'] = $this->authentication->currentUser;
            return 'donate';
        }
    }
    
    function action_donate(&$obj, $param) {
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
    
    public function action_notify(&$obj, $params) {
        // Send an empty HTTP 200 OK response to acknowledge receipt of the notification 
        header('HTTP/1.1 200 OK'); 
        
        // Build the required acknowledgement message out of the notification just received
        $req = 'cmd=_notify-validate';               // Add 'cmd=_notify-validate' to beginning of the acknowledgement

        foreach ($_POST as $key => $value) {         // Loop through the notification NV pairs
            $value = urlencode(stripslashes($value));  // Encode these values
            $req  .= "&$key=$value";                   // Add the NV pairs to the acknowledgement
        }
        
        // Set up the acknowledgement request headers
        $header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";                    // HTTP POST request
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        // Open a socket for the acknowledgement request
        $fp = fsockopen('tls://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

        // Send the HTTP POST request back to PayPal for validation
        fputs($fp, $header . $req);
        
        while (!feof($fp)) {                     // While not EOF
            $res = fgets($fp, 1024);               // Get the acknowledgement response
        
            if (strcmp ($res, "VERIFIED") == 0) {  // Response contains VERIFIED - process notification
                // Possible processing steps for a payment include the following:

                // Check that the payment_status is Completed
                // Check that txn_id has not been previously processed
                // Check that receiver_email is your Primary PayPal email
                // Check that payment_amount/payment_currency are correct
                // Process payment
                $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-OK.txt';
                while (file_exists($filename)) {
                    $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-OK.txt';
                }
                file_put_contents($filename, serialize($_POST));
            } else if (strcmp ($res, "INVALID") == 0) {
                $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-NOK.txt';
                while (file_exists($filename)) {
                    $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-NOK.txt';
                }
                file_put_contents($filename, serialize($_POST));
            }
        }

        fclose($fp);  // Close the file
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