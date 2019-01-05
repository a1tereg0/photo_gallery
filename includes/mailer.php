<?php 
// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once (__DIR__."/PHPMailer/src/Exception.php");
require_once (__DIR__."/PHPMailer/src/PHPMailer.php");
require_once (__DIR__."/PHPMailer/src/SMTP.php");// optional

if (!class_exists("Mailer")) {
	class Mailer {
		public $mail;

		public function __construct($exceptions = false, $smtp = array() ){
			// New PHPMailer instance
			$this->mail = new PHPMailer($exceptions);

			// SMTP setup, if necessary
			if(!empty($smtp)){
				$this->mail->SMTPDebug = $smtp["debug"];    // Enable verbose debug output
				$this->mail->isSMTP();						// Set mailer to use SMTP
				$this->mail->Host = $smtp["host"];			// Specify main and backup SMTP servers
				$this->mail->SMTPAuth = $smtp["auth"];		// Enable SMTP authentication
				$this->mail->Username = $smtp["username"];	// SMTP username
				$this->mail->Password = $smtp["password"];	// SMTP password
				$this->mail->SMTPSecure = $smtp["secure"];  // Enable TLS encryption, 'ssl' also accepted
				$this->mail->Port = $smtp["port"];			// TCP port to connect to

			}

		}

		public function mail($to = array(), $subject, $html, $from = array(),$plaintext = false, $cc = array(), $bcc = array(), $attachments = array() ){
			// Required parameters are $to, $from, $subject and $html
			if (empty($to) || empty($subject) || empty($html) || empty($from)) {
				die("Missing a parameter");
			}

			// Sender
			$this->mail->setFrom($from['email'], $from['name']);
			$this->mail->addReplyTo($from['email'], $from['name']);

			// Recipients
			if(!empty($to)){
				foreach($to as $recipient){
					$this->mail->addAddress($recipient['email'], $recipient['name']);
				}
			}

			// CC
			if (!empty($cc)) {
				foreach($cc as $recipient){
					$this->mail->addCC($recipient);
				}
			}

			// BCC
			if (!empty($bcc)) {
				foreach($bcc as $recipient){
					$this->mail->addBCC($recipient);
				}
			}

			// Attachments
			if (!empty($attachments)) {
				foreach($attachments as $attachment){
					$this->mail->addAttachment($attachment);
				}
			}

			// HTML email
			$this->mail->isHTML(true);
			$this->mail->Subject = $subject;
			$this->mail->Body = $html;

			// Plain text version
			if (false !== $plaintext) {
				$this->mail->AltBody = $plaintext;
			}

			// Send the mail
			try{
				$this->mail->send();
				return true;
			} catch(Exception $e){
				echo "Message couldn't be sent. Mailer Error: ", $this->mail->ErrorInfo;
			}


		}

	
}


}


 ?>