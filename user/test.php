<?php
// Include your database connection file
include("lib/db.php");

// Start the session
session_start();

// Check if MemberID is set in session
if(isset($_SESSION['MemberID'])) {
    // Retrieve MemberID from session
    $MemberID = $_SESSION['MemberID'];

    // Check if the submit button is clicked
    if (isset($_POST["Booking"])) {
        // Retrieve MemberName from the member table based on MemberID
        $sql = "SELECT MemberName, MemberEmail, MemberPhone FROM member WHERE MemberID = $MemberID";
        $result = mysqli_query($db_conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $MemberName = $row['MemberName'];
            $MemberEmail = $row['MemberEmail'];
            $MemberPhone = $row['MemberPhone'];

            // Retrieve form data and sanitize inputs
            $Date = mysqli_real_escape_string($db_conn, $_POST["Date"]);
            $Time = mysqli_real_escape_string($db_conn, $_POST["Time"]);
            $People = mysqli_real_escape_string($db_conn, $_POST["People"]);
            
            // Get the current date and time
            $BookAddDate = date("Y-m-d H:i:s");

            // Construct the SQL query with prepared statement
            $stmt = mysqli_prepare($db_conn, "INSERT INTO booking (Date, Time, People, MemberID, Name, Phone, Email, BookAddDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // Check if the prepared statement is valid
            if ($stmt) {
                // Bind parameters and execute
                mysqli_stmt_bind_param($stmt, "ssisssss", $Date, $Time, $People, $MemberID, $MemberName, $MemberPhone, $MemberEmail, $BookAddDate);

                if (mysqli_stmt_execute($stmt)) {
                    // If the query is successful, redirect to a success page or do something else
                    header("Location: test.php");
                    exit();
                } else {
                    // If there's an error, handle it appropriately
                    die("Error executing query: " . mysqli_stmt_error($stmt));
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                // If there's an error in preparing the statement, handle it appropriately
                die("Error preparing statement: " . mysqli_error($db_conn));
            }
        } else {
            // Unable to retrieve MemberName, handle the case appropriately
            die("Error: Unable to retrieve Member data from the database.");
        }
    }
} else {
    // MemberID not set in session, handle the case appropriately
    die("Error: MemberID not set in session.");
}

// Close the database connection
mysqli_close($db_conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Form</title>
</head>
<body>
    <h2>Booking Form</h2>
    <form action="" method="post">
        <label for="Date">Date:</label>
        <input type="date" name="Date" required><br><br>
        
        <label for="Time">Time:</label>
        <input type="time" name="Time" required><br><br>
        
        <label for="People">Number of People:</label>
        <input type="number" name="People" required><br><br>
        
        <input type="submit" name="Booking" value="Book">
    </form>
</body>
</html>