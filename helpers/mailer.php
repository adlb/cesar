<?php
require ('helpers/PHPMailer/class.phpmailer.php');
require ('helpers/PHPMailer/class.pop3.php');
require ('helpers/PHPMailer/class.smtp.php');

class Mailer {

    var $config;
    var $crowd;
    var $translator;
    var $journal;
    var $webSite;
    
    public function __construct($config, $crowd, $translator, $journal, $webSite) {
        $this->config = $config;
        $this->crowd = $crowd;
        $this->translator = $translator;
        $this->journal = $journal;
        $this->webSite = $webSite;
    }

    public function TrySendSimpleMail($to, $subject, $contentHtml, $content) {
        $mail = new PHPMailer();
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host = $this->config->current['SMTPHosts'];          // Specify main and backup SMTP servers
        $mail->SMTPAuth = $this->config->current['SMTPAuth'];       // Enable SMTP authentication
        $mail->Username = $this->config->current['SMTPUser'];       // SMTP username
        $mail->Password = $this->config->current['SMTPPassword'];   // SMTP password
        
        $mail->Port = 25;                                           // TCP port to connect to
        if ($this->config->current['SMTPAuth']) {
            $mail->SMTPSecure = 'tls';                                   // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
        }
        $mail->CharSet = "UTF-8";

        $mail->From = $this->config->current['Contact'];
        $mail->FromName = $this->config->current['Contact'];
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo($this->config->current['Contact']);
        
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $contentHtml;
        $mail->AltBody = $content;

        if (!$mail->send()) {
            $this->journal->Log('severe', 'Cant send Mail'.PHP_EOL.$mail->ErrorInfo);
            return false;
        }
        
        return true;
    }
    
    private function TrySendTemplatedEmail($email, $articleKey, $macros) {
        $obj = array();
        $macros['useremail'] = $email;
        
        $view = $this->webSite->controllerFactory->GetController('site')->view_fixedArticle($obj,  array('titleKey' => $articleKey, 'renderType' => 'raw', 'macros' => $macros));
        
        ob_start();
        Render('mailContainer', $view, $obj);
        $mailhtml=ob_get_contents();
        ob_end_clean();
        
        $obj = array();
        $view = $this->webSite->controllerFactory->GetController('site')->view_fixedArticleText($obj,  array('titleKey' => $articleKey, 'macros' => $macros));
        ob_start();
        Render('mailContainerText', $view, $obj);
        $mailtext=ob_get_contents();
        ob_end_clean();
        
        $object = $this->translator->GetTranslation($articleKey, $macros);
        
        return $this->TrySendSimpleMail($email, $object, $mailhtml, $mailtext);
    }
    
    public function TrySendForgotPasswordMail($email) {
        if (!$this->crowd->TryGetKey($email, $key))
            return false;

        $macros = array(
            'initpasswordlink' => $this->webSite->urlPrefix.url(array('controller' => 'user', 'view' => 'resetPassword', 'email' => $email, 'key' => $key))
        );
        
        return $this->TrySendTemplatedEmail($email, 'Mail_ForgotPassword', $macros);
    }
    
    public function TrySendNewsLetterSubscriptionMail($email) {
        if (!$this->crowd->TryGetKey($email, $key))
            return false;

        $macros = array(
            'newsletterunsubscribelink' => $this->webSite->urlPrefix.url(array('controller' => 'user', 'action' => 'unsubscribe', 'email' => $email, 'key' => $key))
        );
        
        return $this->TrySendTemplatedEmail($email, 'Mail_NewsLetterSubscription', $macros);
    }
    
    public function TrySendDonationConfirmationMail($donation) {
        $macros = $donation;
        $macros['amount'] = number_format($macros['amount'], 2);
        
        switch($donation['type']) {
            case 'cb' :
                return $this->TrySendTemplatedEmail($donation['email'], 'Mail_DonationConfirmation_CB', $macros);
            case 'vir' :
                return $this->TrySendTemplatedEmail($donation['email'], 'Mail_DonationConfirmation_VIR', $macros);
            case 'chq' :
                return $this->TrySendTemplatedEmail($donation['email'], 'Mail_DonationConfirmation_CHQ', $macros);
        }
        
        return false;
    }
}

?>