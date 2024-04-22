<?php
session_start();
include("../lib/db.php"); 

if (isset($_POST["BtnContact"])) {
  $Name = $_POST["Name"];    
    $Email = $_POST["Email"];
    $Subject = $_POST["Subject"];    
    $Message = $_POST["Message"];

     // Check if inputs are not empty
     if (!empty($Name) && !empty($Email) && !empty($Subject) && !empty($Message)) {
      // Escape user inputs to prevent SQL injection
      $Name = $db_conn->real_escape_string($Name);
      $Email = $db_conn->real_escape_string($Email);
      $Subject = $db_conn->real_escape_string($Subject);
      $Message = $db_conn->real_escape_string($Message);

      // Add date
      $ConAddDate = date('Y-m-d H:i:s');

      // Construct the SQL query
      $sql = "INSERT INTO contact (Name, Email, Subject, Message, ConAddDate) 
              VALUES ('$Name', '$Email', '$Subject', '$Message', '$ConAddDate')";

      // Execute the query
      if ($db_conn->query($sql) === TRUE) {
          $_SESSION['alert'] = 'Message sent successfully!';
          header("Location: ../index.php");
          exit();
      } else {
          $_SESSION['alert'] = 'Error: ' . $sql . '<br>' . $db_conn->error;
          header("Location: ../index.php");
          exit();
      }
  } else {
      $_SESSION['alert'] = 'Please fill in all fields.';
      header("Location: ../index.php");
      exit();
  }
} else {
  $_SESSION['alert'] = 'Form submission error.';
  header("Location: ../index.php");
  exit();
}
?>