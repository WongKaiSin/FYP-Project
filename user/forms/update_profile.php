<?php
session_start();
include("../lib/db.php");

// Check if user is logged in
if (!isset($_SESSION['MemberID'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../registration.php");
    exit();
}

// Check if form is submitted
if (isset($_POST['BtnUpdateProfile'])) {
    // Prepare data for insertion
    $MemberName = $_POST["MemberName"];
    $MemberPhone = $_POST["MemberPhone"];
    $AddAddress = $_POST["AddAddress"];
    $AddPostcode = $_POST["AddPostcode"];
    $AddCity = $_POST["AddCity"];
    $AddState = $_POST["AddState"];
    $AddCountry = $_POST["AddCountry"];
    $MemberEmail = $_SESSION["MemberEmail"];
    $MemberID = $_SESSION['MemberID'];

    // Update user's profile in the database
    $sql = "UPDATE member SET MemberName = '$MemberName', MemberPhone = '$MemberPhone' WHERE MemberID = '$MemberID'";
    $query = $db_conn->query($sql);

    // Get current timestamp for AddressModifyDate
    $AddressModifyDate = date('Y-m-d H:i:s');

    // Check if the member address already exists
    $checkSql = "SELECT * FROM member_address WHERE MemberID = '$MemberID'";
    $checkResult = $db_conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Update user's address in the database
        $sqlAddress = "UPDATE member_address 
                       SET AddName = '$MemberName',
                           AddPhone = '$MemberPhone',
                           AddAddress = '$AddAddress',
                           AddPostcode = '$AddPostcode',
                           AddCity = '$AddCity',
                           AddState = '$AddState',
                           AddCountry = '$AddCountry',
                           AddressModifyDate = '$AddressModifyDate'
                       WHERE MemberID = '$MemberID'";
    } else {
        // Get current timestamp for AddressAddDate
        $AddressAddDate = $AddressModifyDate;

        // Insert new address for the member
        $sqlAddress = "INSERT INTO member_address (MemberID, AddName, AddPhone, AddAddress, AddPostcode, AddCity, AddState, AddCountry, AddressAddDate, AddressModifyDate)
                       VALUES ('$MemberID', '$MemberName', '$MemberPhone', '$AddAddress', '$AddPostcode', '$AddCity', '$AddState', '$AddCountry', '$AddressAddDate', '$AddressModifyDate')";
    }

    $queryAddress = $db_conn->query($sqlAddress);

    if ($query && $queryAddress) {
        // Get the current timestamp
        $MemberReset = date('Y-m-d H:i:s');
        $MemberName = $AddName;
        $MemberPhone = $AddPhone;

        // Update the MemberReset in the database
        $updateSql = "UPDATE member SET MemberReset = '$MemberReset' WHERE MemberID = '$MemberID'";
        $updateQuery = $db_conn->query($updateSql);

        if ($updateQuery) {
            $_SESSION['alert'] = 'Profile updated successfully.';
            header('Location: ../member_profile.php');
            exit(); // Exit after redirection
        } else {
            $_SESSION['alert'] = 'Error updating profile';
            header("Location: ../member_profile.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'Error updating profile';
        header("Location: ../member_profile.php");
        exit();
    }
}
?>
