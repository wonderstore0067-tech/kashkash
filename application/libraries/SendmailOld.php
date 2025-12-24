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
       var $Hostnameval =  '216.69.141.113'; //'smtpout.secureserver.net'; //216.69.141.113
       var $Portval     = '465';
       var $Usernameval = 'support@kashkash.net';
       var $Passwordval = '+tCq#F6P$$';


       /*var $Usernameval = 'redmine@consagous.com';
       var $Passwordval = '@consagous123@';*/
       var $isBcc      = false;
        
        function sendmailto($emailId,$messageSubject,$messageBody,$attachment='')
        {
            // To send HTML mail, the Content-type header must be set
            /*$headers = "From: noreply@cheapeatz.app\r\n" . "X-Mailer: php"; 
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";*/
        
  
           /* if(mail($emailId,$messageSubject,$messageBody, $headers, '-fcheapeatz.app')){
                return true;
            } else {
                return false;
            }*/


            require_once("class.phpmailer.php");
            $mail = new PHPMailer();
           
            //------set value for smtp-----------
            $mail->From     = $this->Fromval;
            $mail->FromName = $this->FromNameval;
            $mail->Hostname = $this->Hostnameval;
            $mail->Host     = $this->Hostnameval;
            $mail->Port     = $this->Portval;
            $mail->Username = $this->Usernameval;
            $mail->Password = $this->Passwordval;
            
            $mail->IsSMTP();                    // set mailer to use SMTP
            $mail->SMTPAuth = false;             // turn on SMTP authentication
            $mail->SMTPSecure = "TLS"; 
            $mail->CharSet="windows-1251";
            $mail->CharSet="utf-8";
            $mail->WordWrap = 50;               // set word wrap to 50 characters
            $mail->IsHTML(true);  
            
            //create and send email
            $mail->AddAddress($emailId);    //sender user email id
            if($this->isBcc){
                $mail->AddBCC($this->bccEmailId);   
            }
            
            $mail->Subject = $messageSubject;
            $mail->Body    = $messageBody;
            if($attachment != "")
            {
                $mail->addAttachment($attachment);
            }
            if($mail->send()){
                return true;
            }else{
                return false;
            }
        }
}   
?>
