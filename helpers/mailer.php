<?php
require ('helpers/PHPMailer/class.phpmailer.php');
require ('helpers/PHPMailer/class.pop3.php');
require ('helpers/PHPMailer/class.smtp.php');

class Mailer {

    var $config;

    public function __construct($config) {
        $this->config = $config;
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

        $mail->From = $this->config->current['Contact'];
        $mail->FromName = $this->config->current['Contact'];
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo($this->config->current['Contact']);
        
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $contentHtml;
        $mail->AltBody = $content;

        $mail->SMTPDebug = 1;
        
        return $mail->send();
    }
    
    
}

?>