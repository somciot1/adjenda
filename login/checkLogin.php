<?php session_start(); //Resumes the session ?>

<?php
	require_once('../model/stuFunctions.php'); //Student Functions
	require_once('../model/instrFunctions.php'); //Instructor Functions
?>

<?php
// Defines error message
	$error = "<p style=\"color:red\">Invalid login. Please try again.</p>";

// Gets the entered form data
    $email = $_POST['email'];
    $pass = $_POST['pass'];
	
// Retrieves array of students from database
	$students = getStudents();
	
// Compares email from form with student emails from database, if found
// then compares the pass from form with the hashed password from database
	$foundStu = false;
	foreach($students as $student){
		if($student['email'] == $email){
			if(password_verify($pass, $student['pass'])){
				$foundStu = true;
				break;
			}				
		}
	}
	
// Checks if the email/password combination was found
	if($foundStu == true){
		// Sets account variables in session array
		$_SESSION["accType"] = "S";
		$_SESSION["accEmail"] = $email;
		echo "<script> window.location='../dashboard/dash.php'; </script>";
	}
// If not, then checks if the user is an instructor
	else{
		// Retrieves array of instructors from database
			$instructors = getInstructors();
			
		// Compares email from form with instructor emails from database, if found
		// then compares the pass from form with the password from database
			$foundInstr = false;
			foreach($instructors as $instructor){
				if($instructor['email'] == $email){
					if(password_verify($pass,$instructor['pass'])){
						$foundInstr = true;
						break;
					}				
				}
			}
			
		// Checks if the email/password combination was found
			if($foundInstr == true){
				// Sets account variables in session array
				$_SESSION["accType"] = "I";
				$_SESSION["accEmail"] = $email;
				echo "<script> window.location='../dashboard/dash.php'; </script>";
			}
			else{
				// Sets error variable in session array
				$_SESSION["error"] = $error;
				// Returns to index (login screen)
				echo "<script> window.location='login.php'; </script>";
			}
	}
?>