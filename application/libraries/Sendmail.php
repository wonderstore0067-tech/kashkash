<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    /** Fetch the email template as per the post purpose and language
     * Return the email template data from zon_email_template table.
     * **/
     
    class Sendmail {

       // var $Fromval     = 'no-reply@consagous.co.in';
       // var $FromNameval = 'eTIPPERS';
       // var $Hostnameval = 'smtp.zoho.com';
       // var $Portval     = '465';
       // var $Usernameval = 'no-reply@consagous.co.in';
       // var $Passwordval = '@Consagous@123@';

       var $Fromval     = 'support@kashkash.net';
       var $FromNameval = 'KashKash';
       var $Hostnameval =  'localhost'; //'smtpout.secureserver.net'; //216.69.141.113
       var $Portval     = '25';
       var $Usernameval = 'support@kashkash.net';
       var $Passwordval = '+tCq#F6P$$';


       /*var $Usernameval = 'redmine@consagous.com';
       var $Passwordval = '@consagous123@';*/
       var $isBcc      = false;
       protected $ci;
        public function __construct()
        {
            $this->ci = &get_instance();
            $this->ci->load->library('email');
            $this->ci->load->library('PHPMailer');
        }

        
        // function sendmailto($to,$messageSubject,$messageBody,$attachment='')
        // {
        //     // To send HTML mail, the Content-type header must be set
        //     /*$headers = "From: noreply@cheapeatz.app\r\n" . "X-Mailer: php"; 
        //     $headers  = 'MIME-Version: 1.0' . "\r\n";
        //     $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";*/
        
  
        //    /* if(mail($emailId,$messageSubject,$messageBody, $headers, '-fcheapeatz.app')){
        //         return true;
        //     } else {
        //         return false;
        //     }*/


        //     //require_once("class.phpmailer.php");
           
        //     $mail = new PHPMailer(true);
        //     $mail->isSMTP();
        //     $mail->isHTML(true);
    
        //     $mail->Host = 'imap.secureserver.net';
        //     $mail->SMTPAuth = false;
        //     $mail->SMTPAutoTLS = false; 
            
    
        //     $mail->Username = 'support@kashkash.net';
        //     $mail->Password = '+tCq#F6P$$';
        //     $mail->Port = 25;
        //     $mail->setFrom($from ? $from : 'support@kashkash.net', $messageSubject);
        //     $mail->addAddress($to ? $to : 'ateam.dev99@gmail.com');
        //     if($this->isBcc){
        //         $mail->AddBCC($this->bccEmailId);   
        //     }
            
        //     $mail->Subject = $messageSubject;
        //     $mail->Body    = $messageBody;
        //     if($attachment != "")
        //     {
        //         $mail->addAttachment($attachment);
        //     }
        //     if($mail->send()){
        //         return true;
        //     }else{
        //         echo 'failed message '. $mail->ErrorInfo;
        //         return false;
        //     }
        // }   



        function sendmailto($to, $messageSubject, $messageBody, $attachment = '')
        {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtpout.secureserver.net';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'support@kashkash.net';
                $mail->Password   = '+tCq#F6P$$';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->isHTML(true);
                $mail->setFrom('support@kashkash.net', 'KashKash');
                $mail->addAddress($to);

                if (!empty($this->isBcc)) {
                    $mail->addBCC($this->bccEmailId);
                }

                if (!empty($attachment)) {
                    $mail->addAttachment($attachment);
                }

                $mail->Subject = $messageSubject;
                $mail->Body    = $messageBody;

                return $mail->send();

            } catch (Exception $e) {
                log_message('error', 'Mailer Error: ' . $mail->ErrorInfo);
                return false;
            }
        }



        public function send_email_phpMailer($to, $subject, $message, $from = null, $cc = null, $bcc = null)
	{
	    echo "INSIDE";
		// Set up PHPMailer
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		//$mail->isHTML(true);

        $mail->Host = 'localhost';
        $mail->SMTPAuth = false;
        $mail->SMTPAutoTLS = false; 
        

		$mail->Username = 'support@kashkash.net';
		$mail->Password = '+tCq#F6P$$';
		$mail->Port = 25;
		$mail->setFrom($from ? $from : 'support@kashkash.net', $subject);
		$mail->addAddress($to ? $to : 'ateam.dev99@gmail.com');
		if (isset($cc) && !empty($cc)) {
			foreach ($cc as $copy) {
				$mail->addCC($copy);
			}
		}
		if (isset($bcc) && !empty($bcc)) {
			$mail->addBCC($bcc);
		}
      //$mail->addAddress($bcc, null, true); 
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

?>