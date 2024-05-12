<?php

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

    function CalcReviewRate($ProID, $type='')
    {
        $ExtraSql = $type == "host" ? "HostID='".$ProID."'" : "ProID='".$ProID."'";
        $rate_sql = "SELECT ReviewsRate FROM js_store_reviews WHERE ".$ExtraSql." AND ReviewsStatus='1' AND isUp='1'";
        $rate_query = $this->db->query($rate_sql);
        $rate_row = $rate_query->getResultArray();
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
    
    // Email
    function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message, $cc='', $attachment='')
    {
        global $db_conn;
        
        // $setting_query = mysqli_query($db_conn, "SELECT SettingEmailMethod, SettingSmtpHost, SettingSmtpUser, SettingSmtpPass, SettingSmtpPort FROM js_setting_site");
        // $setting_row = mysqli_fetch_array($setting_query);
        
        // $SettingEmailMethod = $setting_row["SettingEmailMethod"];
        // $SettingSmtpHost = $setting_row["SettingSmtpHost"];
        // $SettingSmtpUser = $setting_row["SettingSmtpUser"];
        // $SettingSmtpPass = $setting_row["SettingSmtpPass"];
        // $SettingSmtpPort = $setting_row["SettingSmtpPort"];

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
    // END Email


}
?>
