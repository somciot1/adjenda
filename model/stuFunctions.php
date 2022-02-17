<?php require_once('database.php'); ?>

<?php
// Retrieves all students from database
function getStudents() {
    global $db;
    $query = 'SELECT * FROM Students';
    $statement = $db->prepare($query);
    $statement->execute();
    $students = $statement->fetchAll();
    $statement->closeCursor();
    return $students;    
}

// Checks if the entered student email already exists in the database
function checkStuEmail($email){
    $found = false;

    $students = getStudents();

    // compares email from form with students emails from database,
    // if found then flags boolean variable
    foreach($students as $student){
        if($student['email'] == $email){
            $found = true;
            break;				
        }
    }
	
	return $found;
}

// Inserts a new student into the database
function addStudent($email,$fname,$lname,$hashedPass){
    global $db;
    $verified = 0;
    $query = 'INSERT INTO STUDENTS
                 (email, fname, lname, pass, verified)
              VALUES
                (:email, :fname, :lname, :hashedPass, :verified)';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':fname', $fname);
    $statement->bindValue(':lname', $lname);
    $statement->bindValue(':hashedPass', $hashedPass);
    $statement->bindValue(':verified', $verified);
    $statement->execute();
    $statement->closeCursor();
}

// Updates the account password associated with the given student email
function updateStuPass($hashedPass, $stuEmail){
	global $db;
    $query = 'UPDATE STUDENTS
              SET pass = :hashedPass
              WHERE email = :stuEmail';
	$statement = $db->prepare($query);
    $statement->bindValue(':hashedPass', $hashedPass);
	$statement->bindValue(':stuEmail', $stuEmail);
    $statement->execute();
    $statement->closeCursor();
}
?>
