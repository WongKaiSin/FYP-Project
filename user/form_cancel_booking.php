<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['MemberID'])) {
    // Redirect to login page if not logged in
    header("Location: registration.php");
    exit();
}

// Check if BookingID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to booking details page if BookingID is not provided
    header("Location: member_bookinglist.php");
    exit();
}

// Include database connection
include("lib/db.php");

// Get the BookingID from the URL
$bookingId = $_GET['id'];

// Get the MemberID from the session
$memberId = $_SESSION['MemberID'];

// Check if the booking belongs to the logged-in member
$sql = "SELECT * FROM booking WHERE BookID = $bookingId AND MemberID = $memberId";
$result = mysqli_query($db_conn, $sql);


if (mysqli_num_rows($result) == 1) {
    // Booking belongs to the logged-in member, proceed with cancellation
    $cancelSql = "DELETE FROM booking WHERE BookID = $bookingId";
    if (mysqli_query($db_conn, $cancelSql)) {
        // Booking successfully cancelled
        header("Location: member_bookinglist.php");
        exit();
    } else {
        // Error in cancelling booking
        echo "Error: " . mysqli_error($db_conn);
    }
} else {
    // Redirect to booking details page if booking doesn't belong to the logged-in member
    header("Location: member_bookinglist.php");
    exit();
}

// Close the database connection
mysqli_close($db_conn);
?>
