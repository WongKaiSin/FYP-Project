<?php
session_start();

class UserRegistration {
    private $db_conn;
    private $errors;

    public function __construct($db_conn) {
        $this->db_conn = $db_conn;
        $this->errors = array();
    }

    public function registerUser($MemberEmail, $MemberPass, $MemberName, $MemberPhone) {
        // Form validation
        $this->validateForm($MemberEmail, $MemberPass, $MemberName, $MemberPhone);

        // Check if user already exists
        if ($this->isUserExists($MemberEmail)) {
          // Add error message to the $errors array
          array_push($this->errors, "This account already exists");
      
          // JavaScript alert for account existence
          echo '<script type="text/javascript">alert("This account already exists");</script>';
      }

        // Register user if there are no errors
        if (empty($this->errors)) {
          $MemberPass = md5($MemberPass); // Encrypt the password before saving in the database
          $query = "INSERT INTO member (MemberEmail, MemberPass, MemberName, MemberPhone) 
                    VALUES('$MemberEmail', '$MemberPass', '$MemberName', '$MemberPhone')";
          mysqli_query($this->db_conn, $query);
          $_SESSION['MemberEmail'] = $MemberEmail;
          $_SESSION['success'] = "You are now logged in";
      
          // JavaScript alert for successful registration
          echo '<script type="text/javascript">alert("LOGIN SUCCESSFUL");</script>';
      
          header('location: ../registration.php');
          exit;
      }
    }

    private function validateForm($MemberEmail, $MemberPass, $MemberName, $MemberPhone) {
        if (empty($MemberEmail)) { array_push($this->errors, "Email is required"); }
        if (empty($MemberPass)) { array_push($this->errors, "Password is required"); }
        if (empty($MemberName)) { array_push($this->errors, "Name is required"); }
        if (empty($MemberPhone)) { array_push($this->errors, "Phone Number is required"); }
    }

    private function isUserExists($MemberEmail) {
        $user_check_query = "SELECT * FROM member WHERE MemberEmail='$MemberEmail'";
        $result = mysqli_query($this->db_conn, $user_check_query);
        $member = mysqli_fetch_assoc($result);
        return ($member) ? true : false;
    }

    public function getErrors() {
        return $this->errors;
    }
}

// Usage
include("../lib/db.php"); // Assuming db.php contains database connection details

$userRegistration = new UserRegistration($db_conn);

// Check if the form is submitted
if (isset($_POST['signupbtn'])) {
    $MemberEmail = $_POST['MemberEmail'];
    $MemberPass = $_POST['MemberPass'];
    $MemberName = $_POST['MemberName'];
    $MemberPhone = $_POST['MemberPhone'];

    // Register the user
    $userRegistration->registerUser($MemberEmail, $MemberPass, $MemberName, $MemberPhone);

    // Get errors from UserRegistration object
    $errors = $userRegistration->getErrors();

    // Include errors.php to display any errors
    include("errors.php");
}
?>