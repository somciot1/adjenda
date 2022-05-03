<?php session_start(); //Resumes the session ?> 

<?php
require_once('../model/instrFunctions.php');
require_once('../model/stuFunctions.php');

// Defines error & success messages
	$in_use = "<p style=\"color:red\">Email already in database, please choose another email.</p>";
	$success = "<p style=\"color:green\">Student account successfully created.</p>";

// Gets the entered form data
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	
//checks if email is already being used by a student
	$stuFound = checkStuEmail($email);

//checks if email is already being used by a instructor
	if($stuFound == false){
		$instrFound = checkinstrEmail($email);
	}
	
// Checks if email was found to be already in use
	if($stuFound == true || $instrFound == true){
		// Sets error variable in session array
		$_SESSION["error"] = $in_use;
		// Returns to register screen
		echo "<script> window.location='register.php'; </script>";
	}
	else{
		// Sets success variable in session array
		$_SESSION["success"] = $success;
		// Encrypts the entered password
		$hashedPass = password_hash($pass, PASSWORD_DEFAULT);
		// Adds customer to the database
		addStudent($email,$fName,$lName,$hashedPass);
		// Returns to index (login screen)
		echo "<script> window.location='../login/login.php'; </script>";
	}
?>