<?php
session_start();
include("lib/db.php");
include("lib/function.php");

$function = new Functions;

// Check if the login form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loginbtn"])) 
{
    $adUser = $_POST['adUser'];
    $adPass = $function->PassSign($adUser, $_POST['adPass']);

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
	{
        $secret = "6Lf1TrQpAAAAAKE_eR_ZSisp9cNZCXQl7VcfmLjy"; //v3
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if($responseData->success)
        {
            // Prepare the SQL statement with prepared statements to prevent SQL injection
            $sql = "SELECT * FROM admin WHERE adUser = ? AND adPass = ? AND adStatus='1'";
            
            // Prepare the statement
            $stmt = $db_conn->prepare($sql);

            // Bind parameters
            $stmt->bind_param("ss", $adUser, $adPass);

            // Execute the statement
            $stmt->execute();

            // Store the result
            $login_query = $stmt->get_result();

            // Check if the user exists
            if($login_query->num_rows == 1)
            {
                $row = $login_query->fetch_assoc();

                // Set session variables
                $_SESSION['AdminLogged'] = true;
                $_SESSION["adUser"] = $row["adUser"];
                $_SESSION["adName"] = $row["adName"];
                $_SESSION["adType"] = $row["adType"];
                $_SESSION["adMenu"] = $row["adMenu"];
                $_SESSION["adLogo"] = $row["adLogo"];

                // Redirect to the home page or desired location
                header("Location: index.php");
                exit;
            } 
            else 
            {   
                header("Location: login.php?msg=1");
                exit;
            }

            // Close the statement
            $stmt->close();
        }
    }
}

?>