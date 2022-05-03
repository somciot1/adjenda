<?php require_once('database.php'); ?>

<?php
//Adds a new lesson to the database
function addLesson($courseID, $lDate){
    global $db;
    $query = 'INSERT INTO LESSONS
                 (courseID, lDate)
              VALUES
                (:courseID, :lDate)';
    $statement = $db->prepare($query);
    $statement->bindValue(':courseID', $courseID);
    $statement->bindValue(':lDate', $lDate);
    $statement->execute();
    $statement->closeCursor();
}

//Checks if the current date is a lesson for the selected course
function checkLessonDate($courseID, $currentDate) {
    global $db;
    $query = 'SELECT * FROM LESSONS
                WHERE courseID = :courseID';
    $statement = $db->prepare($query);
    $statement->bindValue(':courseID', $courseID);
    $statement->execute();
    $lessons = $statement->fetchAll();
    $statement->closeCursor();

    $found = false;
    foreach($lessons as $lesson){
        if($currentDate == $lesson['lDate']){
            $found = true;
            break; //breaks out of the loop if found
        }
    }
    return $found;
}

//Generates the instructor's random attendance code for a given class lesson
function getAttendanceCode() {
    $attendcode = '';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';   

    for ($i = 0; $i < 6; $i++) {
        $attendcode .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $attendcode;
}
?>
