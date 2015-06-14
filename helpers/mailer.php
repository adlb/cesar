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
        
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host = $this->config->current['SMTPHosts'];          // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                     // Enable SMTP authentication
        $mail->Username = $this->config->current['SMTPUser'];       // SMTP username
        $mail->Password = $this->config->current['SMTPPassword'];   // SMTP password
        $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                          // TCP port to connect to
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
    
    public function TrySendForgotPasswordMail($email) {
        if (!$this->crowd->TryGetFromEmail($email, $user))
            return false;
        
        $obj = array();
        $view = $this->webSite->controllerFactory->GetController('site')->view_fixedArticle($obj,  array('titleKey' => 'MailForgotPassword'));
        
        ob_start();
        Render('mailContainer', $view, $obj);
        $mailhtml=ob_get_contents();
        ob_end_clean();
        
        $obj = array();
        $view = $this->webSite->controllerFactory->GetController('site')->view_fixedArticleText($obj,  array('titleKey' => 'MailForgotPassword'));
        ob_start();
        Render('mailContainerText', $view, $obj);
        $mailtext=ob_get_contents();
        ob_end_clean();
        
        $mailhtml = str_replace('@@useremail@@', $email, $mailhtml);
        $mailtext = str_replace('@@useremail@@', $email, $mailtext);
        
        if (!$this->crowd->TryGetKey($email, $key))
            return false;

        $link = $this->webSite->urlPrefix.url(array('controller' => 'user', 'view' => 'resetPassword', 'email' => $email, 'key' => $key));
        $mailhtml = str_replace('@@initpasswordlink@@', $link, $mailhtml);
        $mailtext = str_replace('@@initpasswordlink@@', $link, $mailtext);
        
        return $this->TrySendSimpleMail($email, $this->translator->GetTranslation('MailForgotPassword'), $mailhtml, $mailtext);
    }
}

?>