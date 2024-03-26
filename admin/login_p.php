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

    // Prepare the SQL statement with prepared statements to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE adUser = '".$adUser."' AND adPass = '".$adPass."' AND adStatus='1'";

    // Get and count the data
    $login_query = $db_conn->query($sql);
    $login_num = mysqli_num_rows($login_query);
    
    // Check if the user exists
    if($login_num == 1)
    {
        $row = $login_query->fetch_assoc();

        // Set session variables
        $_SESSION['AdminLogged'] = true;
        $_SESSION["adUser"] = $row["adUser"];
        $_SESSION["adName"] = $row["adName"];
        $_SESSION["adType"] = $row["adType"];
        $_SESSION["adMenu"] = $row["adMenu"];
        $_SESSION["adLogo"] = $row["adLogo"];
        ?> 
            <script type="text/javascript">
                alert("<?php echo ' LOGIN SUCCESSFUL' ?>");
            </script>
        <?php
        // Redirect to the home page or desired location
        header("Location: index.php");
        exit();
    } 
    else 
    {
        $login_error = "Invalid user ID or password.";
        ?>  	
            <script type="text/javascript">
                alert("<?php echo ' WRONG PASSWORD OR USERNAME' ?>");
            </script>
        <?php
    }
}

?>