<?php require_once('database.php'); ?>

<?php
// Retrieves all students from database
function getInstructors() {
    global $db;
    $query = 'SELECT * FROM INSTRUCTORS';
    $statement = $db->prepare($query);
    $statement->execute();
    $students = $statement->fetchAll();
    $statement->closeCursor();
    return $students;    
}

// Checks if the entered instructor email already exists in the database
function checkInstrEmail($email){
    $found = false;

    $instructors = getInstructors();

    // compares email from form with instructor emails from database,
    // if found then flags boolean variable
    foreach($instructors as $instructor){
        if($instructor['email'] == $email){
            $found = true;
            break;				
        }
    }
	
	return $found;
}

// Updates the account password associated with the given instructor email
function updateInstrPass($hashedPass, $instrEmail){
	global $db;
    $query = 'UPDATE INSTRUCTORS
              SET pass = :hashedPass
              WHERE email = :instrEmail';
	$statement = $db->prepare($query);
    $statement->bindValue(':hashedPass', $hashedPass);
	$statement->bindValue(':instrEmail', $instrEmail);
    $statement->execute();
    $statement->closeCursor();
}
?>
