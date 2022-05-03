<?php require_once('database.php'); ?>

<?php
// Retrieves the attendance dates for the given course ID
function getAttendanceDates($courseID) {
    global $db;
    $query = 'SELECT lessonDate FROM ATTENDANCES
                WHERE courseID = :courseID';
    $statement = $db->prepare($query);
    $statement->bindValue(':courseID', $courseID);
    $statement->execute();
    $dates = $statement->fetchAll();
    $statement->closeCursor();
    return $dates;
}

// Retrieves the attendance status of all students for the selected course ID and date
function getAttendanceLog($courseID, $lessonDate){
    global $db;
    $query = 'SELECT * FROM ATTENDANCES
                WHERE courseID = :courseID AND lessonDate = :lessonDate';
    $statement = $db->prepare($query);
    $statement->bindValue(':courseID', $courseID);
    $statement->bindValue(':lessonDate', $lessonDate);
    $statement->execute();
    $log = $statement->fetchAll();
    $statement->closeCursor();
    return $log;
}

// Downloads the retrieved attendance Log
function downloadLog($courseID, $date){
    $log = getAttendanceLog($courseID, $date);
    //*add logic for downloading the log as plain text*
}
?>
