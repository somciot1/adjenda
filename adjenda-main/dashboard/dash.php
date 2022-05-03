<?php session_start(); //Resumes the session ?> 

<?php
require_once('../model/courseFunctions.php');
require_once('../model/rosterFunctions.php');
require_once('../model/lessonFunctions.php');

//retrieves the chosen action
$action = filter_input(INPUT_POST, 'action');

//when no action was chosen
if ($action == NULL) 
{
	//sets action to showDash
    $action = 'showDash';
}

//selects chosen action
switch($action){
	//Displays the dashboard
    case 'showDash':
        //determines if the user is a student or an instructor
        if($_SESSION["accType"] == "S"){
            //retrieves each courseID from the rosters that the student is in
            $courseIDs = getRosterCourseIDsByEmail($_SESSION["accEmail"]);
            //loops through all retrieved courseIDs and stores each course found in an array
            foreach($courseIDs as $courseID){
                $courses[] = getCourseByID($courseID['courseID']);
            }
        }
        elseif($_SESSION["accType"] == "I"){
            //retrieves all the courses associated with the instructor's email
            $courses = getCoursesByEmail($_SESSION["accEmail"]);
        }
        include('dashDisplay.php');
        break;
    //User selects course
    case 'selectCourse':
        //retrieves the selected courseID and stores it as a session variable
        $_SESSION["courseID"] = filter_input(INPUT_POST, 'courseID');
        echo "<script> document.location='../course/course.php'; </script>";
        break;
    //Instructor creates a new course
    case 'createCourse':
        //Course Name
        $courseName = filter_input(INPUT_POST, 'courseName'); //selected course name

        //Course Days
        $selectedDays = $_POST['selectedDays']; //array of the selected days
        $days = seperateDays($selectedDays); //seperates the selected days by slashes

        //Course Time
        $duration = filter_input(INPUT_POST, 'duration'); //selected duration
        $startHours = filter_input(INPUT_POST, 'startHours'); //selected start hours
        $startMinutes = filter_input(INPUT_POST, 'startMinutes'); //selected start minutes
        $endHrsMins = calculateEndHrsMins($duration, $startHours, $startMinutes); //calculates endHours & endMinutes (returns array)
        $endHours = $endHrsMins[0]; //course ending hours
        $endMinutes = $endHrsMins[1]; //course ending minutes
        $startTime = $startHours.":".$startMinutes.":00"; //created start time from start hours and minutes
        $endTime = $endHours.":".$endMinutes.":00"; //created end time from end hours and minutes

        //Adds the created course to the database after performing checks
        $courses = getCoursesByEmail($_SESSION["accEmail"]); //retrieves the instructor's exisiting courses
        $created = false; //boolean to represent if the course is successfully created or not
        //checks if the instructor already has an existing course with the given name
        if(!empty($courses)){
            if(checkInstrCourseNames($courses, $courseName)){
                echo "<script> alert('COURSE HAS NOT BEEN CREATED: You already have an existing course with the selected name.'); </script>";
            }
            else{
                //checks if the new course is on the same day as an existing course
                //and if so then checks if there is a time conflict
                if(checkInstrCourseDaysTime($courses, $selectedDays, $startTime)){
                    echo "<script> alert('COURSE HAS NOT BEEN CREATED: Your selected start time conflicts with one of your existing courses.'); </script>";
                }
                else{
                    addCourse($courseName, $_SESSION["accEmail"], $days, $startTime, $endTime);
                    $created = true;
                }
            }
        }
        else{
            addCourse($courseName, $_SESSION["accEmail"], $days, $startTime, $endTime);
            $created = true;
        }

        //Lessons
        //checks if the course was successfully created
        if($created){
            $semester = filter_input(INPUT_POST, 'semester'); //selected semester
            $months = getSemesterMonths($semester); //determines the numerical months within the semester
            $newID = getCourseID($_SESSION["accEmail"], $courseName); //retrieves the newly created course's ID
            determineLessonDates($months, $selectedDays, $newID); //determines the dates and creates the lessons for the selected days
        }

        //reloads the dashboard with the newly created course shown
        echo "<script> window.location='../dashboard/dash.php'; </script>";
        break;
}

///////////////////////////////
//FUNCTIONS FOR COURSE CREATION
///////////////////////////////

// Creates a string containing the given selected days seperated by slashes
function seperateDays($selectedDays){
    $days = "";

    //seperates each day by a foward slash
    for($i = 0; $i < sizeof($selectedDays); $i++){
        $days .= $selectedDays[$i]."/";
    }
    //remove the last slash from the days string
    $days = rtrim($days, "/");

    return $days;
}

// Calculates the endHours and endMinutes of the course based on the selected duration and start time
function calculateEndHrsMins($duration, $startHours, $startMinutes){
    //determines the duration hours and minutes based on the selected duration
    if($duration == 1){
        $durationHours = 1;
        $durationMinutes = 15;
    }
    elseif($duration == 2){
        $durationHours = 2;
        $durationMinutes = 30;
    }
    elseif($duration == 3){
        $durationHours = 3;
        $durationMinutes = 0;
    }

    //calculates the end hours and minutes by adding the duration to the start time
    $hours = $startHours + $durationHours;
    $minutes = $startMinutes + $durationMinutes;
    //if the end minutes equals 60 or more then adds an hour to the end time and leaves the difference as the minutes
    if($minutes >= 60){
        $hours += 1;
        $minutes -= 60;
    }

    //adds a leading zero to the hours (endTime[0]) or minutes (endTime[1]) if they are only one digit
    $endHrsMins = array();
    $endHrsMins[0] = str_pad(strval($hours), 2, "0", STR_PAD_LEFT); //endHours
    $endHrsMins[1] = str_pad(strval($minutes), 2, "0", STR_PAD_LEFT); //endMinutes

    return $endHrsMins;
}

//Determines if the instructor already has an existing course with the same given name
function checkInstrCourseNames($courses, $courseName){
    $match = false;
    foreach($courses as $course){
        if(strtoupper($courseName) == strtoupper($course['name'])){
            $match = true;
            break;
        }
    }

    return $match;
}

//Determines if the instructor already has an existing course on the same day and if so if there is a time conflict
function checkInstrCourseDaysTime($courses, $selectedDays, $startTime){
    $conflict = false;
    foreach($courses as $course){
        $courseDaysArray = explode("/", $course['days']); //breaks up the course's days into an array of tokens delimited by /
        //loops through each of the existing course's days
        for($cdCount = 0; $cdCount < sizeof($courseDaysArray); $cdCount++){
            //loops through each of the selected days for the new course
            for($i = 0; $i < sizeof($selectedDays); $i++){
                //checks if the selected day matches with the existing day
                if($selectedDays[$i] == $courseDaysArray[$cdCount]){
                    if((strtotime($startTime) >= strtotime($course['sTime'])) && (strtotime($startTime) <= strtotime($course['eTime']))){
                        $conflict = true;
                        break 3; //breaks out of all 3 for loops
                    }
                }
            }
        }
    }

    return $conflict;
}

//Determines the months of the course based on the given semester
function getSemesterMonths($semester) {
    //determines the semester start and end based on the selected semester
    if($semester == "FALL"){
        $months = array("09", "10", "11", "12");
        //$semesterSTART = "September";
        //$semesterEND = "December";
    }
    elseif($semester == "SPRING"){
        $months = array("01", "02", "03", "04", "05");
        //$semesterSTART = "January";
        //$semesterEND = "May";
    }
    elseif($semester == "WINTER"){
        $months = array("12", "01");
        //$semesterSTART = "December";
        //$semesterEND = "January";
    }
    elseif($semester == "SUMMER"){
        $months = array("05", "06", "07");
        //$semesterSTART = "May";
        //$semesterEND = "July";
    }

    return $months;
}

//Determines the lesson dates for the course based on the previously provided information
function determineLessonDates($months, $selectedDays, $courseID){
    //loops through each month in the semester's months array
    for($mCount = 0; $mCount < sizeof($months); $mCount++){
        $numOfDays = cal_days_in_month(CAL_GREGORIAN, $months[$mCount], date("Y")); //retrieves the number of days in each given month

        //loops through each day in the given month
        for($days = 1; $days <= $numOfDays; $days++){
            $julianDay = gregoriantojd($months[$mCount], $days, date("Y")); //converts gregorian date to a julian day count
            $weekday = jddayofweek($julianDay, 1); //retrieves the name of the weekday for the julian day count

            //loops through the selected days for the course
            for($sdCount = 0; $sdCount < sizeof($selectedDays); $sdCount++){
                //checks if the current weekday matches one of the selected course days
                if($weekday == $selectedDays[$sdCount]){
                    $day = str_pad(strval($days), 2, "0", STR_PAD_LEFT); //pads lesson date's day with a 0 if it is only one digit
                    $lessonDate = date("Y")."-".$months[$mCount]."-".$day; //creates an appropriately formatted string for the given date to be inserted as an SQL DATE
                    addLesson($courseID, $lessonDate); //creates a new lesson in the database for the given course and its date
                }
            }
        }
    }
}
?>