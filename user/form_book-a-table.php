<?php
// Include your database connection file
include("lib/db.php");

// Start the session
session_start();

// Check if MemberID is set in session
if (isset($_SESSION['MemberID'])) {
    // Retrieve MemberID from session
    $MemberID = $_SESSION['MemberID'];

    // Check if the submit button is clicked
    if (isset($_POST["BtnBooking"])) {
        // Retrieve form data and sanitize inputs
        $Date = $db_conn->real_escape_string($_POST["Date"]);
        $Time = $db_conn->real_escape_string($_POST["Time"]);
        $People = $db_conn->real_escape_string($_POST["People"]);

        // Calculate the number of tables needed based on the number of people
        $tablesNeeded = ceil($People / 4);

        // Check if the total number of people or tables exceeds the limit
        $sql = "SELECT SUM(People) AS totalPeople, COUNT(*) AS totalTables FROM booking WHERE Date = '$Date' AND Time = '$Time'";
        $result = $db_conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $totalPeople = $row['totalPeople'];
            $totalTables = $row['totalTables'];

            // Calculate the new total number of people and tables after adding the requested booking
            $newTotalPeople = $totalPeople + $People;
            $newTotalTables = $totalTables + $tablesNeeded;

            // Check if the total number of people or tables exceeds the limit
            if ($newTotalPeople > 20 || $newTotalTables > 5) {
                // Set alert message
                echo '<script>alert("Sorry, reservations for this time slot are full. Please choose another time slot.")</script>';
            } else {
                // Retrieve MemberName, MemberEmail, MemberPhone from the member table based on MemberID
                $sql = "SELECT MemberName, MemberEmail, MemberPhone FROM member WHERE MemberID = $MemberID";
                $result = $db_conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $MemberName = $row['MemberName'];
                    $MemberEmail = $row['MemberEmail'];
                    $MemberPhone = $row['MemberPhone'];

                    // Get the current date and time
                    $BookAddDate = date("Y-m-d H:i:s");

                    // Construct the SQL query to insert booking data
                    $sql = "INSERT INTO booking (Date, Time, People, MemberID, Name, Phone, Email, BookAddDate) VALUES ('$Date', '$Time', '$People', '$MemberID', '$MemberName', '$MemberPhone', '$MemberEmail', '$BookAddDate')";

                    // Execute the query
                    if ($db_conn->query($sql) === TRUE) {
                        echo '<script>alert("Your booking request was sent. We will confirm your reservation later. Thank you!")</script>';
                    } else {
                        echo '<script>alert("Error:")</script>';
                    }
                } else {
                    echo '<script>alert("Please fill in all fields.")</script>';
                }
            }
        } else {
            // Error handling if the query fails
            echo '<script>alert("Error: Unable to check the total number of people or tables booked.")</script>';
        }

        // Redirect after setting the alert
        echo '<script>window.location.href="booking.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Error: MemberID not set in session.")</script>';
    // Redirect to booking page
    echo '<script>window.location.href="booking.php";</script>';
    exit();
}

// Close the database connection
$db_conn->close();
?>
