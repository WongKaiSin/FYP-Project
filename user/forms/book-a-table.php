<?php
// Include your database connection file
include("../lib/db.php");

// Start the session
session_start();

// Check if MemberID is set in session
if(isset($_SESSION['MemberID'])) {
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

        // Check if the selected time slot already has 5 tables booked or if adding the requested tables would exceed the limit
        $sql = "SELECT COUNT(*) AS bookedTables FROM booking WHERE Date = '$Date' AND Time = '$Time'";
        $result = mysqli_query($db_conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $bookedTables = $row['bookedTables'];

            // Calculate the total number of tables after adding the requested tables
            $totalTables = $bookedTables + $tablesNeeded;

            // Check if the total number of tables exceeds the limit (5 tables)
            if ($totalTables > 5) {
                // Redirect back to booking page with an error message
                $_SESSION['alert'] = 'Sorry, reservations for this time slot are full. Please choose another time slot.';
                header("Location: ../booking.php");
                exit();
            } else {
                // Retrieve MemberName, MemberEmail, MemberPhone from the member table based on MemberID
                $sql = "SELECT MemberName, MemberEmail, MemberPhone FROM member WHERE MemberID = $MemberID";
                $result = mysqli_query($db_conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $MemberName = $row['MemberName'];
                    $MemberEmail = $row['MemberEmail'];
                    $MemberPhone = $row['MemberPhone'];

                    // Get the current date and time
                    $BookAddDate = date("Y-m-d H:i:s");

                    // Construct the SQL query to insert booking data
                    $sql = "INSERT INTO booking (Date, Time, People, MemberID, Name, Phone, Email, BookAddDate) VALUES ('$Date', '$Time', '$People', '$MemberID', '$MemberName', '$MemberPhone', '$MemberEmail', '$BookAddDate')";

                    // Execute the query
                    if (mysqli_query($db_conn, $sql)) {
                        $_SESSION['alert'] = 'Your booking request was sent. We will call back or send an Email to confirm your reservation. Thank you!';
                        header("Location: ../booking.php");
                        exit();
                    } else {
                        $_SESSION['alert'] = 'Error: ' . mysqli_error($db_conn);
                        header("Location: ../booking.php");
                        exit();
                    }
                } else {
                    $_SESSION['alert'] = 'Please fill in all fields.';
                    header("Location: ../booking.php");
                    exit();
                }
            }
        } else {
            // Error handling if the query fails
            $_SESSION['alert'] = 'Error: Unable to check the number of booked tables.';
            header("Location: ../booking.php");
            exit();
        }
    }
}

// Close the database connection
mysqli_close($db_conn);
?>