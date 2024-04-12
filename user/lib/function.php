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
    

}
?>
