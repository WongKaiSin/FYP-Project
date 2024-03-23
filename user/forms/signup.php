<?php
session_start();

// initializing variables
$MemberEmail = "";
$errors = array(); 

// connect to the database
include("../lib/db.php");

// REGISTER USER
if (isset($_POST['signupbtn'])) {
  // receive all input values from the form
  $MemberEmail = mysqli_real_escape_string($db_conn, $_POST['MemberEmail']);
  $MemberPass = mysqli_real_escape_string($db_conn, $_POST['MemberPass']);
  $MemberName = mysqli_real_escape_string($db_conn, $_POST['MemberName']);
  $MemberPhone = mysqli_real_escape_string($db_conn, $_POST['MemberPhone']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($MemberEmail)) { array_push($errors, "Email is required"); }
  if (empty($MemberPass)) { array_push($errors, "Password is required"); }
  if (empty($MemberName)) { array_push($errors, "Name is required"); }
  if (empty($MemberPhone)) { array_push($errors, "Phone Number is required"); }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM member WHERE MemberEmail='$MemberEmail';
  $result = mysqli_query($db_conn, $user_check_query);
  $member = mysqli_fetch_assoc($result);
  
  if ($member) { // if user exists
    if ($member['MemberEmail'] === $MemberEmail) {
      array_push($errors, "This account already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
        $MemberPass = md5($MemberPass); // encrypt the password before saving in the database

        $query = "INSERT INTO member (MemberEmail, MemberPass, MemberName, MemberPhone) 
                          VALUES('$MemberEmail', '$MemberPass', '$MemberName', '$MemberPhone')";
        mysqli_query($db_conn, $query);
        $_SESSION['MemberEmail'] = $MemberEmail;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
  }
}
