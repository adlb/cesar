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
        
        if ($this->validate_ipn()) {
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
        } else {
            $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-NOK.txt';
            while (file_exists($filename)) {
                $filename = 'ipn-'.substr(md5(uniqid(mt_rand(), true)), 0, 5).'-NOK.txt';
            }
            file_put_contents($filename, serialize($_POST));
        }
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
    
    private function validate_ipn() {
        
        /*$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
        if (! preg_match ( '/paypal\.com$/', $hostname )) {
            //$this->ipn_status = 'Validation post isn\'t from PayPal';
            //$this->log_ipn_results ( false );
            return false;
        }*/
        
        /*if (isset($this->paypal_mail) && strtolower ( $_POST['receiver_email'] ) != strtolower(trim( $this->paypal_mail ))) {
            $this->ipn_status = "Receiver Email Not Match";
            $this->log_ipn_results ( false );
            return false;
        }*/
        
        /*if (isset($this->txn_id)&& in_array($_POST['txn_id'],$this->txn_id)) {
            $this->ipn_status = "txn_id have a duplicate";
            $this->log_ipn_results ( false );
            return false;
        }*/

        // parse the paypal URL
        $paypal_url = (isset($_POST['test_ipn']) && $_POST['test_ipn'] == 1) ? SSL_SAND_URL : SSL_P_URL;
        $url_parsed = parse_url($paypal_url);        
        
        // generate the post string from the _POST vars aswell as load the
        // _POST vars into an arry so we can play with them from the calling
        // script.
        $post_string = '';    
        foreach ($_POST as $field=>$value) { 
            $post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
        }
        $post_string.="cmd=_notify-validate"; // append ipn command
        
        // open the connection to paypal
        if (isset($_POST['test_ipn']) )
            $fp = fsockopen ( 'ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60 );
        else
            $fp = fsockopen ( 'ssl://www.paypal.com', "443", $err_num, $err_str, 60 );
 
        if(!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            //$this->ipn_status = "fsockopen error no. $err_num: $err_str";
            //$this->log_ipn_results(false);       
            return false;
        } else { 
            // Post the data back to paypal
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
            fputs($fp, "Host: $url_parsed[host]\r\n"); 
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
            fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
            fputs($fp, "Connection: close\r\n\r\n"); 
            fputs($fp, $post_string . "\r\n\r\n"); 
        
            // loop through the response from the server and append to variable
            $rep = "";
            while($rep != 'VERIFIED' && $rep != 'INVALID' && !feof($fp)) { 
               $rep = fgets($fp, 1024);
            } 
            fclose($fp); // close connection
        }
        
        // Invalid IPN transaction.  Check the $ipn_status and log for details.
        if ("VERIFIED" == $rep) {
            return true;
        } else {
            return false;
        }
    } 
}