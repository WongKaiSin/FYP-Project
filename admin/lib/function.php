<?php
require_once("lib/mailer/PHPMailer.php");
require_once("lib/mailer/SMTP.php");
require_once("lib/mailer/POP3.php");
require_once("lib/mailer/Exception.php");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Functions
{
    function PassSign($email, $password) 
    { 
        return base64_encode(hex2bin(sha1($email . $password . "Ad2024@123"))); 
    }

    function PassSignCust($email, $password) 
    { 
        return base64_encode(hex2bin(sha1($email . $password . "Mem123@2024"))); 
    }

    function checkPass($pass)
	{
		$result = (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z]{8,20}$/', $pass)) ? false : true;
		return $result;
	}
    
    function displayMsg($type, $msg='')
    {
        global $db_conn;
        
        if(!empty($msg))
        {
            $msg_text = $msg;
        
            if(!empty($msg_text))
                echo "<div class=\"response-msg ".$type."\"><span>".ucwords($type)." Message</span><br>".$msg_text."</div>";
        }
    }

    // Email
    function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message, $cc='', $attachment='')
    {
        // tester using smtp
        $SettingEmailMethod = "SMTP";
        $SettingSmtpHost = "s1364.securessl.net";
        $SettingSmtpUser = "webmaster@fitmate.com.my";
        $SettingSmtpPass = "Web2587!@#$";
        $SettingSmtpPort = "465";

        if($SettingEmailMethod == "mail")
        {
            $headers  = "MIME-Version: 1.0 \n";
            $headers .= "Content-type: text/html; charset=utf8 \n";
            $headers .= "To: $nameto <$to> \n";
            $headers .= "From: $namefrom <$from> \n";
            $headers .= "Cc: $namefrom <$cc> \n";
            
            mail('', $subject, $message, $headers);
        }
        else
        {
            $timeout = "300";
            $localhost = "";

            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;     //Enable verbose debug output
            $mail->IsSMTP();        // set mailer to use SMTP
            $mail->Host = $SettingSmtpHost;  // specify main and backup server
            $mail->Port = $SettingSmtpPort;
            $mail->SMTPAuth = true;     // turn on SMTP authentication
            $mail->SMTPSecure = "ssl";
            $mail->Username = $SettingSmtpUser;  // SMTP username
            $mail->Password = $SettingSmtpPass; // Please enter the SMTP password for the user name
            $mail->CharSet = "UTF-8";
            
            $mail->From = $from; // Sender Address
            $mail->FromName = $namefrom;	// Sender Name
            $mail->addReplyTo($from, $namefrom);
            $mail->AddAddress("$to","$nameto");   // Email address where by this form will be delivered to		
            $mail->AddCC($cc, "");
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            
            if(!empty($cc))
            {
                $CcEmailExp = explode(",", $cc);

                if ($CcEmailExp) //send multiple mail in CC list
                {
                    foreach ($CcEmailExp as $CcEmail)
                    {
                        $CcEmail = trim($CcEmail);
                        
                        if(empty($CcEmail))
                        {
                            $CcEmailName = current(explode("@", $CcEmail));
                            
                            $mail->AddCC($CcEmail, $CcEmailName);
                        }
                    }
                }
            }
            
            if(!empty($attachment))
            {
                $attach = explode(";", $attachment);
                
                foreach($attach as $files)
                {
                    if(!empty($files))
                    {
                        $explode = explode("#", $files);
                        $file = $explode[0];
                        $file_name = $explode[1];
                        
                        $mail->addAttachment($file, $file_name);
                    }
                }
            }
            
            if(!$mail->Send())
            {
                echo "Message could not be sent.<br>Error: " . $mail->ErrorInfo;
                exit;
            }
        }
    }

    function email_template($message, $name)
    {        
        $template = "<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,300italic,300' rel='stylesheet' type='text/css'>
                    <style type='text/css'>
                        body, td, th, table 
                        {
                            font-family:Open Sans !important;
                            font-size:13px;
                        }
                        
                        p
                        {
                            margin:0px;
                            margin-bottom:7px;
                        }
                    </style>
                    <table cellpadding='0' cellspacing='0' width='800px' bgcolor='#CCCCCC' style='padding:15px'>
                        <tr bgcolor='#FFFFFF'>
                            <td style='padding:10px 15px'>
                                <center><img src='http://localhost:80/FYP-Project/user/assets/img/logo.png' style='max-width:200px'></center><br>
                                Dear $name,<br><br>
                                $message
                                <br><br><br>
                                <em>This is a computer generated receipt and no signature is required.<br>
                                <div style='border-bottom:1px dashed #000000; margin:5px 0px'></div>
                                <span style='font-size:11px'>
                                    For enquiry, please contact us at:<br>
                                    Address: 33 Ubi Avenue 3, Tower B #08-09 Vertex, Singapore 408868.<br>
                                    Tel: +65 9036 1829<br>
                                    Email: hello@ikiitravel.com
                                </span>
                            </td>
                        </tr>
                    </table>";
                    
        return $template;
    }

    function send_email($email_id, $name, $email, $custom_msg='', $attachment='')
    {
        $func = new Functions;
        $SiteName = "London Bagel Museum";

        $custom = array();
        if(!empty($custom_msg))
        {
            $msg_exp = explode("######", $custom_msg);
            
            foreach($msg_exp as $msg)
            {
                $custom[] = $msg;
            }
        }

        $_SESSION["email_id"]=$email_id;

        // Set email content
        require_once("lib/email_content.php");

        // email to user
        $EmailUserSender = $SiteName;
        $EmailUserSenderEmail = "wongksin7@gmail.com";
        
        $EmailUserSubject = str_replace("{#SiteName}", $SiteName, $EmailUserSubject);
        

        // Account created
        if($email_id == "1")
        {
            $EmailUserMsg = str_replace("{#MemberEmail}", $custom[0], $EmailUserMsg);
            $EmailUserMsg = str_replace("{#MemberPassword}", $custom[1], $EmailUserMsg);
        }

        // Retrieve Password for forgot password
        else if($email_id == "2")
        {
            $EmailUserMsg = str_replace("{#link}", $custom[0], $EmailUserMsg);
			$EmailUserMsg = str_replace("{#email}", $custom[1], $EmailUserMsg);
        }
        
        // Why red coloured don't ask me QAQ
        $UserMsg = $func->email_template($EmailUserMsg, $name);
        $func->authSendEmail($EmailUserSenderEmail, $EmailUserSender, $email, $name, $EmailUserSubject, $UserMsg, "", $attachment);
    }
    // END Email

}
?>
