<?php
session_start();
require("lib/db.php");
require("lib/function.php");

$function = new Functions;

// Check if the login form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loginbtn"])) 
{
    $adUser = $_POST['adUser'];
    $adPass = $_POST['adPass'];

    // Prepare the SQL statement with prepared statements to prevent SQL injection
    // $sql = "SELECT * FROM admin WHERE adUser = '".$adUser."' AND adPass = '".$function->PassSign($adUser, $adPass)."' AND adStatus='1'";
    $sql = "SELECT * FROM admin WHERE adUser = ? AND adPass = ? AND adStatus='1'";

    // Prepare and bind parameters
    // $login_query = $db_conn->query($sql);
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("ss", $adUser, $function->PassSign($adUser, $adPass));

    // Calculate the number of rows of SQL
    // $login_num = mysqli_num_rows($login_query);
    // $stmt->bind_param("ss", $adUser, $adPass);
    // $stmt->execute();

    // Execute the statement
    $stmt->execute();

    // Store result
    $result = $stmt->get_result();
    
    // Check if the user exists
    // if ($result->login_num == 1) {
    if($result->num_rows == 1)
    {
        // $row = $result->fetch_assoc();
        $row = $result->fetch_array();
        
        // Set session variables
        $_SESSION["adUser"] = $row["adUser"];
        $_SESSION["adName"] = $row["adName"];
        // Add more session variables as needed
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