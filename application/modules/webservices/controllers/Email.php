<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email
{
	protected $ci;
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->library('email');
		$this->ci->load->library('PHPMailer');
	}
	public function send_email_phpMailer($to, $subject, $message, $from = null, $cc, $bcc)
	{
	    echo "INSIDE";
		// Set up PHPMailer
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->isHTML(true);
		$mail->Host = 'smtpout.secureserver.net';
		$mail->SMTPAuth = true;
		$mail->Username = 'support@kashkash.net';
		$mail->Password = '+tCq#F6P$$';
		$mail->Port = 587;
		$mail->setFrom($from ? $from : 'support@kashkash.net', $subject);
		$mail->addAddress($to ? $to : 'shaniYahanApniTestEmailDalna');
		if (isset($cc) && !empty($cc)) {
			foreach ($cc as $copy) {
				$mail->addCC($copy);
			}
		}
		if (isset($bcc) && !empty($bcc)) {
			$mail->addBCC($bcc);
		}
      $mail->addAddress($bcc, null, true); 
		$mail->Subject = $subject;
		$mail->Body = $message;

		// Send email
		try {
			$mail->send();
		} catch (Exception $e) {
			echo 'Email sending failed. Error: ' . $mail->ErrorInfo;
		}
	}
	
}
