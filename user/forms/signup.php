<?php
session_start();

class UserAuth {
    private $db_conn;

    public function __construct($db_conn) {
        $this->db_conn = $db_conn;
    }

    public function registerUser($MemberEmail, $MemberPass, $MemberName, $MemberPhone) {
        $errors = $this->validateForm($MemberEmail, $MemberPass, $MemberName, $MemberPhone);

        if (!empty($errors)) {
            return $errors;
        }

        if ($this->isUserExists($MemberEmail)) {
            return ["This account already exists"];
        }

        $hashedPassword = password_hash($MemberPass, PASSWORD_DEFAULT); // Use bcrypt for password hashing
        $query = "INSERT INTO member (MemberEmail, MemberPass, MemberName, MemberPhone) 
                    VALUES(?, ?, ?, ?)";
        $stmt = $this->db_conn->prepare($query);
        $stmt->bind_param("ssss", $MemberEmail, $hashedPassword, $MemberName, $MemberPhone);
        
        if ($stmt->execute()) {
            $_SESSION['MemberEmail'] = $MemberEmail;
            $_SESSION['success'] = "You are now registered and logged in";
            header('location: ../registration.php');
            exit;
        } else {
            return ["Error occurred while registering. Please try again."];
        }
    }

    public function loginUser($MemberEmail, $MemberPass) {
        $errors = [];

        if (empty($MemberEmail) || empty($MemberPass)) {
            $errors[] = "Email and password are required";
        } else {
            $query = "SELECT MemberPass FROM member WHERE MemberEmail = ?";
            $stmt = $this->db_conn->prepare($query);
            $stmt->bind_param("s", $MemberEmail);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($MemberPass, $row['MemberPass'])) {
                    $_SESSION['MemberEmail'] = $MemberEmail;
                    $_SESSION['success'] = "You are now logged in";
                    header('location: ../index.php');
                    exit;
                } else {
                    $errors[] = "Incorrect email or password";
                }
            } else {
                $errors[] = "Incorrect email or password";
            }
        }

        return $errors;
    }

    private function validateForm($MemberEmail, $MemberPass, $MemberName, $MemberPhone) {
        $errors = [];

        if (empty($MemberEmail)) { $errors[] = "Email is required"; }
        if (empty($MemberPass)) { $errors[] = "Password is required"; }
        if (empty($MemberName)) { $errors[] = "Name is required"; }
        if (empty($MemberPhone)) { $errors[] = "Phone Number is required"; }

        return $errors;
    }

    private function isUserExists($MemberEmail) {
        $query = "SELECT * FROM member WHERE MemberEmail = ?";
        $stmt = $this->db_conn->prepare($query);
        $stmt->bind_param("s", $MemberEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return ($result->num_rows > 0);
    }
}

include("../lib/db.php"); // Assuming db.php contains database connection details

$userAuth = new UserAuth($db_conn);

if (isset($_POST['signupbtn'])) {
    $errors = $userAuth->registerUser($_POST['MemberEmail'], $_POST['MemberPass'], $_POST['MemberName'], $_POST['MemberPhone']);

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<script type="text/javascript">alert("' . $error . '");</script>';
        }
    }
}

if (isset($_POST['loginbtn'])) {
    $errors = $userAuth->loginUser($_POST['MemberEmail'], $_POST['MemberPass']);

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<script type="text/javascript">alert("' . $error . '");</script>';
        }
    }
}
?>