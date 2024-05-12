<?php

class Functions
{
    // Password for admin
    function PassSign($email, $password) 
    { 
        return base64_encode(hex2bin(sha1($email . $password . "Ad2024@123"))); 
    }

    // Password for customer
    function PassSignCust($email, $password) 
    { 
        return base64_encode(hex2bin(sha1($email . $password . "Mem123@2024"))); 
    }

    // Check the password (length, character size, symbol, number)
    function checkPass($pass)
	{
		$result = (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z]{8,20}$/', $pass)) ? false : true;
		return $result;
	}

    // Email
    function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message, $cc='', $attachment='')
    {
        // tester using smtp
        $SettingEmailMethod = "SMTP";
        $SettingSmtpHost = "mail.ikiitravel.com";
        $SettingSmtpUser = "smtp@ikiitravel.com";
        $SettingSmtpPass = "a_KH\$MIrQ6SN";
        $SettingSmtpPort = "587";

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
            
            require_once 'lib/mailer/class.phpmailer.php';
            
            $mail = new PHPMailer();
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

        // Set email content
        require_once("email_content.php?id=$email_id");

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
        $func->authSendEmail($EmailUserSenderEmail, $EmailUserSender, $email, $name, $EmailUserSubject, $UserMsg, $attachment);
    }
    // END Email

    // Cart
    function updateCartTotal($CartID, $shipping='0')
    {
        global $db_conn;
        
        $total_query = mysqli_query($db_conn, "SELECT sum(ProTotal) as CartSubtotal FROM cart_product WHERE `CartID`='$CartID'");
        $total_row = mysqli_fetch_array($total_query);
        
        $CartSubtotal = $total_row["CartSubtotal"];
        $CartTotal = $CartSubtotal + $shipping;
        
        mysqli_query($db_conn, "UPDATE cart SET `CartSubtotal`='$CartSubtotal', CartTotal='$CartTotal' WHERE CartID='$CartID'");
    }
    // END Cart

    function CalcReviewRate($ProID, $type='')
    {
        global $db_conn;

        $ExtraSql = $type == "host" ? "HostID='".$ProID."'" : "ProID='".$ProID."'";
        $rate_sql = "SELECT ReviewsRate FROM js_store_reviews WHERE ".$ExtraSql." AND ReviewsStatus='1' AND isUp='1'";
        $rate_query = mysqli_query($db_conn, $rate_sql);
        $rate_row = mysqli_fetch_assoc($rate_query);
        $rate_num = count($rate_row);

        $RateAvg = 0;
        $RateAvgWidth = 0;
        $TotalReview = $rate_num;
        if($rate_num > 0)
        {
            $rate = [];
            foreach($rate_row as $index=>$each)
            {
                extract($each);

                $rate[$ReviewsRate] = $rate[$ReviewsRate]+1;
            }

            if(!empty($rate))
            {
                $TotalRate = 0;
                foreach($rate as $rating => $num)
                {
                    $TotalRate += ($rating*$num);
                }

                $RateAvg = round($TotalRate / $TotalReview, 1);
                $RateAvgWidth = round((($TotalRate / $TotalReview) * pow(100,2))/500, 2);
            }
        }
        
        return ["rate" => $rate, "TotalReview" => $TotalReview, "RateAvg" => $RateAvg, "RateAvgWidth" => $RateAvgWidth];
    }
}
?>
