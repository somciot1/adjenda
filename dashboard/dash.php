<?php session_start(); //Resumes the session ?> 

<?php
//retrieves the chosen action
$action = filter_input(INPUT_POST, 'action');

//when no action was chosen
if ($action == NULL) 
{
	//sets action to showOptions
    $action = 'showDash';
}

//selects chosen action
switch($action){
	//displays the dashboard
    case 'showDash':
        include('dashDisplay.php');
        break;
}
?>