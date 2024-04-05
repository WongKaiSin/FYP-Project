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

}
?>
