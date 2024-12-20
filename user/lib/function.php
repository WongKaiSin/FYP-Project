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

    // For display message
    function displayMsg($type='success', $msg='')
    {
        if(!empty($msg))
        {
            if(is_array($msg))
            {
                $msg_list = "";
                foreach($msg as $msg_text)
                {
                    if(sizeof($msg) > 1)
                        $msg_list .= "- ";
                    
                    $msg_list .= $msg_text."<br>";
                }
                
                $msg = $msg_list;
            }
            
            $list = "<div class=\"response-msg ".$type."\">
                        <strong>".ucwords($type)." Message</strong><br>".
                        $msg."
                    </div>";
            
            return $list;
        }
    }

    // Check the input
    function checkInput($variable)
    {
        return str_replace('"', "&quot;", $variable);
    }

    // Check a and b are equal
    function displayChecked($val_a, $val_b)
    {
        if($val_a == $val_b)
            return " checked";
    }

    // Check number decimal
    function formatNumber($value, $decimal='2')
    {
        return number_format($value, $decimal);
    }

    // Forgot password
    function codeLock($var)
    {
        return str_rot13(base64_encode($var));
    }
    
    // For iPay88
    function iPay88_signature($source) 
	{ 
		return hash('sha256', $source); 
	} 

    // Email
    function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message, $cc='', $attachment='')
    {
        // tester using smtp
        $SettingEmailMethod = "SMTP";
        $SettingSmtpHost = "smtp.gmail.com";
        $SettingSmtpUser = "wongksin7@gmail.com";
        $SettingSmtpPass = "vyoocfspfbaubsku";
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
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;     //Enable verbose debug output
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
                                    Tel: +60 12 123 1234<br>
                                    Email: wongksin7@gmail.com
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
        
        $UserMsg = $func->email_template($EmailUserMsg, $name);
        $func->authSendEmail($EmailUserSenderEmail, $EmailUserSender, $email, $name, $EmailUserSubject, $UserMsg, "", $attachment);
    }
    // END Email

    // Get product picture
    function productPic($ProID)
    {
        global $db_conn, $SiteUrl;
        
        $stmt = $db_conn->prepare("SELECT ImageName, ImageExt FROM product_image WHERE ProID = ?");
        $stmt->bind_param("i", $ProID);
        $stmt->execute();
        $result = $stmt->get_result();

        $img = "";
        if($img_row = $result->fetch_assoc())
        {
            $ImageName = $img_row["ImageName"];
            $ImageExt = $img_row["ImageExt"];
            
            $img = $SiteUrl."/upload/product/".$ProID."/".$ImageName.".".$ImageExt;
        }
        
        else
        {
            $img = "$SiteUrl/images/no-image.jpg";
        }
        $stmt->close();

        return $img;
    }

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

    function checkoutStep($active)
    {
        $btn1 = $active >= 1 ? " active" : "";
        $btn2 = $active >= 2 ? " active" : "";
        $btn3 = $active >= 3 ? " active" : "";
        $btn4 = $active >= 4 ? " active" : "";
        $btn5 = $active >= 5 ? " active" : "";

        $check1 = $active >= 1 ? '<div><span class="fa fa-check"></span></div>' : '';
        $check2 = $active >= 2 ? '<div><span class="fa fa-check"></span></div>' : '';
        $check3 = $active >= 3 ? '<div><span class="fa fa-check"></span></div>' : '';
        $check4 = $active >= 4 ? '<div><span class="fa fa-check"></span></div>' : '';
        $check5 = $active >= 5 ? '<div><span class="fa fa-check"></span></div>' : '';

        $step = '<div class="navigation">
                    <div class="navi' . $btn1 . '">Cart' . $check1 . '</div>
                    <div class="navi' . $btn2 . '">Checkout' . $check2 . '</div>
                    <div class="navi' . $btn3 . '">Review' . $check3 . '</div>
                    <div class="navi' . $btn4 . '">Payment' . $check4 . '</div>
                    <div class="navi' . $btn5 . '">Complete' . $check5 . '</div>
                </div>';

        return $step;
    }


    function CheckStock($CartID)
    {
        global $db_conn;
        
        $item_query = mysqli_query($db_conn, "SELECT ProID, ProQty FROM cart_product WHERE CartID='$CartID'");
        
        $CheckStock = 1;
        while ($item_row = mysqli_fetch_array($item_query)) 
        {	
            $ProID = $item_row["ProID"];
            $ProQty = $item_row["ProQty"];
 
            $stock_query = mysqli_query($db_conn, "SELECT ProStock FROM product WHERE ProID='".$ProID."'");
            $stock_row = mysqli_fetch_array($stock_query);
            
            $ProStock = $stock_row["ProStock"];

            if($ProStock < $ProQty)
                $CheckStock = 0;
        }
        
        return $CheckStock;
    }

    function checkCart($shipping='0')
    {
        $func = new Functions;
        global $db_conn, $MemberID, $CurrCart;
        
        $CartSql = "";
        if($MemberID > 0)
            $CartSql = " OR MemberID='$MemberID'";
        
        $cart_query = mysqli_query($db_conn, "SELECT CartID, CartSubtotal FROM cart WHERE (CartSession='".$CurrCart."'".$CartSql.") ORDER BY CartID DESC");
        $cart_num = mysqli_num_rows($cart_query);
        
        if($cart_num > 0)
        {
            $cart_row = mysqli_fetch_array($cart_query);
            
            $CartID = $cart_row["CartID"];
            $CartSubtotal = $cart_row["CartSubtotal"];
            
            $item_query = mysqli_query($db_conn, "SELECT CartProID, ProID, ProPrice, ProQty, ProTotal FROM cart_product WHERE CartID='$CartID'");
            $item_num = mysqli_num_rows($item_query);
            
            if($item_num > 0)
            {
                while ($item_row = mysqli_fetch_array($item_query)) 
                {	
                    $CartProID = $item_row["CartProID"];
                    $ProID = $item_row["ProID"];
                    $ProPrice = $item_row["ProPrice"];
                    $ProQty = $item_row["ProQty"];
                    $ProTotal = $item_row["ProTotal"];
                    
                    $pro_query = mysqli_query($db_conn, "SELECT ProPrice FROM product WHERE ProID='$ProID'");
                    $pro_row = mysqli_fetch_array($pro_query);

                    $CheckPrice = $pro_row["ProPrice"];
                                            
                    if($CheckPrice != $ProPrice)
                    {
                        $NewTotal = $CheckPrice * $ProQty;
                        
                        mysqli_query($db_conn, "UPDATE cart_product SET ProPrice='$CheckPrice', ProTotal='$NewTotal' WHERE CartProID='$CartProID'");
                    }
                }
                
                $func->updateCartTotal($CartID, $shipping);
            }
        }
    }
    // END Cart

    // Rating
    function displayStars($rating)
    {
        $output = '';
        if ($rating !== null && $rating >= 0) {
            $fullStars = intval($rating); // Full stars
            $halfStar = $rating - $fullStars; // Half star

            // Full stars
            for ($i = 0; $i < $fullStars; $i++)
            {
                $output .= '<span class="fa fa-star checked"></span>';
            }

            // Half star
            if ($halfStar >= 0.5)
            {
                $output .= '<span class="fa fa-star-half-o checked"></span>';
            }

            // Empty stars
            $emptyStars = 5 - ceil($rating);
            if ($emptyStars > 0)
            {
                for ($i = 0; $i < $emptyStars; $i++)
                {
                    $output .= '<span class="fa fa-star"></span>';
                }
            }
        } 
        else
        {
            $output .= '<span>No rating available</span>';
        }

        return $output;
    }

    // For review
    function CalcReviewRate($ProID, $type='')
    {
        global $db_conn;

        $ExtraSql = "`ProID`=?";
        $rate_sql = "SELECT `RevRate` FROM `review_rate` WHERE ".$ExtraSql." AND `Approval`=1";
        $rate_stmt = $db_conn->prepare($rate_sql);
        $rate_stmt->bind_param("i", $ProID);
        $rate_stmt->execute();
        $rate_result = $rate_stmt->get_result();
        $rate_num = $rate_result->num_rows;

        $rate = [];
        $RateAvg = 0;
        $RateAvgWidth = 0;
        $TotalReview = $rate_num;
                
        if($rate_num > 0)
        {
            while($rate_row = $rate_result->fetch_assoc())
            {
                $rate_rows[] = $rate_row;
            }
            
            foreach($rate_rows as $each)
            {
                $ReviewsRate = $each['RevRate'];
                if (!isset($rate[$ReviewsRate])) {
                    $rate[$ReviewsRate] = 0;
                }
                $rate[$ReviewsRate] += 1;
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
