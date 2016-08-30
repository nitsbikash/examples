<?php
require_once("class/class.phpmailer.php");
 $mail = new PHPMailer();
 
 
 
 
 
/* =======================Configuration by You======================================= */
// Emial Configuration  Which you have to configure 

$MailTo='nits.anup@gmail.com';     // email id to whome you want to send

$MailToName='abc';

$MailFrom='mfoogateadmin@mitralink-sinergi.com';    //  Your email password

$MailFromName='mfoogateadmin@mitralink-sinergi.com';

$YourEamilPassword="Mitralink03";   //Your email password from which email you send.

$MailSubject='Message are send through smpt';  // Message title

$MailHtmlMessage='Name: '.$_POST['fullname']."<br>";  // Message Body
$MailHtmlMessage.='Phone: '.$_POST['phone']."<br>";
$MailHtmlMessage.='Email: '.$_POST['email']."<br>";
$MailHtmlMessage.='Comment: '.$_POST['comment']."<br>";

   //Message body

//$MailAttachment[]='images/pic1.jpg';    //You can attach multiple attachement. 

//$MailAttachment[]='images/IMG_0003.JPG';   // //You can attach multiple attachement. 

/* ==========================================================================  */





/*

There are 3 tipes of Mails

1.    SMTP. Please define IsMailType='SMTP' to active the SMTP mail function;

2.    PHP's Mail().   Please define IsMailType='mail' to active the PHP mail;

3.    Sendmail.   Please define IsMailType='sendmail' to active the Sendmail;      

4.    Qmail.    Please define IsMailType='qmail' to active the Qmail;

*/

$IsMailType='SMTP';   





// If you use SMTP. Please configure the bellow settings.

  $EmailDomain          = explode("@",$MailFrom);
  $SmtpHost             = "mail.mitralink-sinergi.com"; 
  $SmtpDebug            = 0;                     // enables SMTP debug information (for testing)
  $SmtpAuthentication   = true;                  // enable SMTP authentication
  $SmtpPort             = 25;                    
  $SmtpUsername       = $MailFrom; 
  $SmtpPassword       = $YourEamilPassword;        



//

if ( $IsMailType == "SMTP" ) {
    $mail->IsSMTP();  // telling the class to use SMTP
    $mail->SMTPDebug  = $SmtpDebug;
    $mail->SMTPAuth   =  $SmtpAuthentication;     // enable SMTP authentication
    $mail->Port       = $SmtpPort;             // set the SMTP port
    $mail->Host       = $SmtpHost;           // SMTP server
    $mail->Username   =  $SmtpUsername; // SMTP account username
    $mail->Password   = $SmtpPassword; // SMTP account password
  } elseif ( $IsMailType == "mail" ) {
    $mail->IsMail();      // telling the class to use PHP's Mail()
  } elseif ( $IsMailType == "sendmail" ) {
    $mail->IsSendmail();  // telling the class to use Sendmail
  } elseif ( $IsMailType == "qmail" ) {
    $mail->IsQmail();     // telling the class to use Qmail
  }

  if ( $MailFromName != '' ) {
    $mail->AddReplyTo($MailFrom,$MailFromName);
    $mail->From       = $MailFrom;
    $mail->FromName   = $MailFromName;
  } else {
    $mail->AddReplyTo($MailFrom);
    $mail->From       = $MailFrom;
    $mail->FromName   = $MailFrom;
  }

  if ( $MailToName != '' ) {
    $mail->AddAddress($MailTo,$MailToName);
  } else {
    $mail->AddAddress($MailTo);
  }

 
  $mail->Subject  = $MailSubject;

  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($MailHtmlMessage);
  if(is_array($MailAttachment))
  for($i=0;$i<count($MailAttachment);$i++)
       {
	   if(file_exists($MailAttachment[$i]))
	      {
		    $mail->AddAttachment($MailAttachment[$i]); 
		  
		  }
	   
	   }   
  
  
  
   try {
    if ( !$mail->Send() ) {
      $error = "Unable to send to: " . $to . "<br />";
      throw new phpmailerAppException($error);
    } else {
      echo 'Message has been sent <br /><br />';
    }
  }
  catch (phpmailerAppException $e) {
    $errorMsg[] = $e->errorMessage();
  }

  if ( count($errorMsg) > 0 ) {
    foreach ($errorMsg as $key => $value) {
      $thisError = $key + 1;
      echo $thisError . ': ' . $value;
    }
  }

 




?>
