<?php session_start(); //Resumes the session ?> 

<?php
require_once('../model/courseFunctions.php');
require_once('../model/rosterFunctions.php');
require_once('../model/attendanceFunctions.php');
require_once('../model/lessonFunctions.php');
require_once('../model/stuFunctions.php');
$title = '';

//retrieves the chosen action
$action = filter_input(INPUT_POST, 'action');

//when no action was chosen
if ($action == NULL) 
{
	//sets action to showCourse
    $action = 'showCourse';
}

//selects chosen action
switch($action){
	//displays the course
    case 'showCourse':
        $course = getCourseByID($_SESSION['courseID']);
        $students = getStudentRosterByID($_SESSION['courseID']);
        $dates = getAttendanceDates($_SESSION['courseID']);
        include('courseDisplay.php');
        break;
    //generates the attendance code for the current lesson
    case 'takeAttendance' :
        $course = getCourseByID($_SESSION['courseID']);
        $date = new DateTime("now", new DateTimeZone('America/New_York') );
        $currentTime = $date->format('H:i:s');
        //checks if the current date has a lesson
        if(checkLessonDate($_SESSION['courseID'], date("Y-m-d"))){
            //if the current date has a lesson, then check that the time is within the range of the lesson's start and end
            if((strtotime($currentTime) >= strtotime($course['sTime'])) && (strtotime($currentTime) <= strtotime($course['eTime']))){
                echo '<script> alert("IN SECOND IF STATEMENT"); </script>';
                $_SESSION['attendanceCode'] = getAttendanceCode();
                echo "<script> alert('Attendance code has been generated for the current lesson.'); </script>";
            }
        }
        //else a lesson is not happening at the current time, so do not generate code and instead display message saying that a class is not in progress
        else{
            echo "<script> alert('NO CODE HAS BEEN GENERATED: The class is not in progress'); </script>";
        }
        
        echo "<script> window.location='../course/course.php'; </script>"; //return to course page
        break;
    //downloads the selected attendance log
    case 'downloadLog' :
        echo "<script>
				alert('Downloading Selected Log...');
			</script>";
        //*needs logic for downloading the selected attendance log*
        //$selectedDate = $_POST['date'];
        //downloadLog($_SESSION['courseID'], $selectedDate)
        echo "<script> window.location='../course/course.php'; </script>"; //return to course page
        break;
    case 'searchStudents':
        $course = getCourseByID($_SESSION['courseID']);
        $searchTerm = filter_input(INPUT_POST, 'searchTerm');
        $radioButton = filter_input(INPUT_POST, 'radioButton');

        //determines if the search should be by name or email
        if($radioButton == "email"){
            $foundStudents = searchStudentsByEmail($searchTerm);
        }
        else if($radioButton == "name"){
            $foundStudents = searchStudentsByLastName($searchTerm);
        }

        //checks if each student found by the search is already in the class or not
        foreach($foundStudents as $foundStudent){
            if(!(checkRosterForStudent($_SESSION['courseID'], $foundStudent['email'])))
                $eligibleStudents[] = $foundStudent;
        }

        include('searchDisplay.php');
        break;
    case 'addStudents':
        $selectedStuEmails = $_POST['selectedStuEmails']; //array of student emails that were selected
        $allStuFNames = $_POST['allStuFNames']; //array of all student fNames found in the search
        $allStuLNames = $_POST['allStuLNames']; //array of all student lNames found in the search

        //cycles through array of names to find the students' names associated with the selected emails
        for($i = 0; $i < sizeof($allStuFNames); $i++){
            $match = false; //flag for determining if the iterated names match with one of the selected emails
            //cycles through array of selected emails
            for($j = 0; $j < sizeof($selectedStuEmails); $j++){
                $index = substr($selectedStuEmails[$j], 0, 1); //retrieves the original index of each of all the found emails from the first character of each string
                //if the original index of the email matches with the names
                //then mark flag and break out of loop
                if($index == $i){
                    $addedEmail = substr($selectedStuEmails[$j], 1);
                    $match = true;
                    break;
                }
            }
            //if the index of the email matches with the names, then add that student's name and email to the roster
            if($match){
                $enrollmentCode = generateEnrollmentCode(); //generated unique enrollment code
                echo '<script> alert("'.$allStuFNames[$i].' '.$allStuLNames[$i].' ('.$addedEmail.') has been added to the course."); </script>';
                addToRoster($_SESSION['courseID'], $addedEmail, $allStuFNames[$i], $allStuLNames[$i], $enrollmentCode);
            }
        }

        echo "<script> window.location='../course/course.php'; </script>"; //return to course page
        break;
    case 'dropStudents':
        $removedStudents = $_POST['removedStuEmails']; //array of student emails to be removed

        //cycle through array and call dropStudent function for each student
        for($i = 0; $i < sizeof($removedStudents); $i++){
            echo '<script> alert("'.$removedStudents[$i].' has been dropped from the course."); </script>';
            dropStudent($removedStudents[$i], $_SESSION['courseID']);
        }

        echo "<script> window.location='../course/course.php'; </script>"; //return to course page
        break;
}

?>