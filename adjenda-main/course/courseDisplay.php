<!--Includes the defined header-->
<?php
$title = $course['name'];
include '../view/header.php';
?>

<style>
	.my-custom-scrollbar {
	position: relative;
	height: 450px;
	width: 600px;
	overflow: auto;
}
.my-custom-scrollbar-popup {
	position: relative;
	height: 350px;
	width: 468px;
	overflow: auto;
}
ul.no-bullets {
	list-style-type: none;
	margin: 0;
	padding: 0;
}
.table-wrapper-scroll-y {
	display: block;
}

.table-striped>tbody>tr:nth-child(odd)>td,
.table-striped>tbody>tr:nth-child(odd)>th {
  background-color: #2d2d2d;
  border-color: #2d2d2d;
  color: white;
}
.table-striped>tbody>tr:nth-child(even)>td,
.table-striped>tbody>tr:nth-child(even)>th {
  background-color: #4d4d4d;
  border-color: #4d4d4d;
  color: white;
}
</style>

<main>
	<!-- Course Header -->
	<div style="width:94.5%; margin-left:2.5%">
		<h1>(Course ID: <?php echo $course['id'].") ".$course['name'] ?></h1>
		<hr border-top: 10px solid black; border-radius: 5px;>
	</div>

	<!-- Course Information -->
	<div style="width:94.5%; height:600px; margin-left:2.5%; background-color: #EBEBEC; border-radius: 0.5em;">
		<header>
			<nav class="navbar navbar-expand-sm navbar-light sticky-top" style="background-color: #0079C2; border-radius: 0.5em; height: 8%;">
				<ul class="navbar-nav" style="margin-left:2%">
					<li class="nav-item">
						<a class="nav-link" style="color: white;" href="../course/course.php">
							Class Roster
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="color: white;" href="#">
							Groups
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="color: white;" href="#">
							Polls
						</a>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" style="color: white;">
							<?php if($_SESSION["accType"] == "I") : ?>
								<?php
								//NEED TO SWITCH CHECK TO IF CURRENT LESSON'S ATTENDNACE CODE FIELD IS NULL OR NOT
								//*currently generating one attendance code makes it display for all courses*
									if(isset($_SESSION['attendanceCode'])){
										$code = $_SESSION['attendanceCode'];
										echo "Attendance Code : ".$code;
									}
								?> 
							<?php endif; ?>
						</a>
					</li>
				</ul>
			</nav>
		</header>

		<!-- Class Roster -->
		<div style="padding-left: 5%; padding-bottom: 3%; float: left" width="100%">
			<div class="table-wrapper-scroll-y my-custom-scrollbar" >
				<table class="table table-bordered table-striped mb-0">
					<tbody>
						<?php $numofstudents = sizeof($students); ?>
						<?php $enrollmentCount = 0; //initial enrollment count ?>
						<?php for ($x = 0; $x < $numofstudents; $x++) : ?>
							<!--Checks if the student has been enrolled before showing them in the list-->
							<?php if ($students[$x]['enrollment'] != 0) : ?>
								<tr>
									<?php $enrollmentCount += 1; //increments enrollment count for each student found?>
									<th scope="row" style="padding-left: 15%"><?php echo $students[$x]['fName']." ".$students[$x]['lName']; ?></th>
								</tr>
							<?php endif; ?>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>

			<div style="padding-top: 2%">
				<?php if($_SESSION["accType"] == "I") : ?>
					<!-- Search Students -->
					<div>
						<form action="course.php" method="post">
							<input type="hidden" name="action" value="searchStudents">
								<div class="modal fade" id="searchModal">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<div class="modal-body ">
													<ul class="no-bullets" style="width:100%">
														<li><h3>Add Student</h3></li>
														<li>
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="radioButton" value="email" checked required>
																	<label class="form-check-label">
																		By Email
																	</label>
																</div>
																<div class="form-check form-check-inline">
																	<input class="form-check-input" type="radio" name="radioButton" value="name">
																	<label class="form-check-label">
																		By Last Name
																	</label>
																</div>
															<input type="text" class="form-control" style="width:80%; float:left" id="searchTerm" name="searchTerm" placeholder="Search students" required>
															<button type="submit" class="btn btn-primary" style="float:right">Search</button>
														</li>
													</ul>
												</div>
                                            </div>
										</div>
									</div>
								</div>
						</form>
						<button class="btn btn-primary btn-block" href="#" data-toggle="modal" data-target="#searchModal" style="width:25%; float:left;">Add Students</button>
					</div>
					<!-- Drop Student-->
					<div style="padding-left: 30%">
						<form action="course.php" method="post">
							<input type="hidden" name="action" value="dropStudents">
								<div class="modal fade" id="dropModal">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<ul class="no-bullets">
													<li><h3>Existing Roster</h3></li>
													<li><div class="table-wrapper-scroll-y my-custom-scrollbar-popup">
														<table class="table table-bordered table-striped mb-0">
															<tbody>
																<?php $numofstudents = sizeof($students); ?>
																	<?php for ($x = 0; $x < $numofstudents; $x++) : ?>
																		<tr>
																			<th scope="row" style="padding-left: 15%">
																				<div class="students">
																					<input type="checkbox" class="form-check-input" name="removedStuEmails[]" value="<?php echo $students[$x]['stuEmail'] ?>" required>
																					<label class="form-check-label"><?php echo " ".$students[$x]['fName']." ".$students[$x]['lName']; ?></label>
																				</div>
																			</th>
																		</tr>
																	<?php endfor; ?>
															</tbody>
														</table>
													</div></li>
													<div class="drop">
														<li style="padding-top: 3%"><button type="submit" class="btn btn-primary" style="width:40%;" disabled>Drop Students</button></li>
													</div>	
												</ul>
                                            </div>
										</div>
									</div>
								</div>
						</form>
						<!--Form Scripting-->
						<script type="text/javascript">
                            //Requires at least one checkbox be selected from students
                            $(function(){
                                var requiredCheckboxes = $('.students :checkbox[required]');
								var dropButton = $('.drop :submit[disabled]');
                                requiredCheckboxes.change(function(){
                                    if(requiredCheckboxes.is(':checked')) {
                                        requiredCheckboxes.removeAttr('required');
										dropButton.removeAttr('disabled');
                                    } 
                                    else {
                                        requiredCheckboxes.attr('required', 'required');
										dropButton.attr('disabled', 'disabled');
                                    }
                                });
                            });
                        </script>
						<!--Only enables the 'Remove Student' button if there is at least one student in the roster-->
						<?php 
							if($enrollmentCount != 0){
								echo "<button class=\"btn btn-primary btn-block\" href=\"#\" data-toggle=\"modal\" data-target=\"#dropModal\" style=\"width:40%;\">Remove Students</button>";
							}
							else{
								echo "<button class=\"btn btn-primary btn-block\" href=\"#\" data-toggle=\"modal\" data-target=\"#dropModal\" style=\"width:40%;\" disabled>Remove Students</button>";
							}
						?> 
					</div>
				<?php endif; ?>
			</div>

		</div>

		<?php if($_SESSION["accType"] == "S") : ?>
			<div class="form-group" style="float: right; padding-right: 5%; width: 400px">
				<a style="padding-left: 12%">Enter Attendance Code</a>
				<form action="course.php" method="post" style="padding-top:2%">
					<input type="hidden" name="action" value="enteredCode">
					<ul style="list-style: none;">
						<li>
							<input type="attendancecode" class="form-control" id="attendancecode" name="attendancecode" placeholder="Type your instructor's provided code" required>
						</li>
						<li style="padding-top:4%">
							<button type="submit" class="btn btn-primary">Submit</button>
						</li>
					</ul>
				</form>
			</div>
        <?php endif; ?>

		<?php if($_SESSION["accType"] == "I") : ?>
			<div class="form-group" style="float: right; width: 300px">
				<ul style="list-style: none;">
					<form action="course.php" method="post" style="padding-top:2%">
						<li style="padding-top:4%">
							<form action = "course.php" method = "post">
								<div class="form-group">
									<input type="hidden" name="action" value="takeAttendance">
									<!--Only enables the 'Take Attendance' button if there is at least one student in the roster-->
									<?php
										if($enrollmentCount != 0 && !(isset($_SESSION['attendanceCode']))){
											echo "<button type=\"submit\" class=\"btn btn-primary\" style=\"width: 182px\">Take Attendance</button>";
										}
										elseif(isset($_SESSION['attendanceCode'])){
											echo "<button type=\"submit\" class=\"btn btn-primary\" style=\"width: 182px\" disabled=\"true\">Code Generated</button>";
										}
										else{
											echo "<button type=\"submit\" class=\"btn btn-primary\" style=\"width: 182px\" disabled=\"true\">No Students</button>";
										}
                                	?>
								</div>
						</li>
					</form>
					<li style="padding-top:4%">
						<div class="modal fade" id="viewModal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1>View Attendance Logs</h1>
                                    </div>
                                    <div class="modal-body">
										<!--Drop down menu and list-->
										<form action="course.php" method="post">
											<div class="form-group row justify-content-center">
												<input type="hidden" name="action" value="downloadLog">
												<button type="submit" class="btn btn-primary" style="width: 25%" id="download" disabled="true">Download Log</button>
											</div>
											<div class="form-group row">
												<div class="col-sm-10" style="margin:auto">
													<select class ="form-control" name ="date" required>
														<option value="" selected hidden>Select a Date</option>
														<?php foreach($dates as $date) : ?>
															<option value="<?php echo $date['lessonDate'] ?>"><?php echo $date['lessonDate'] ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<!--Form Scripting-->
											<script type='text/javascript'>
												//Disables the 'Download Log' button until a date has been selected
												$('select').change(function() {
													var selectedDate = $(this).val();
													switch(selectedDate){
														case '':
															$('#download').prop('disabled',true);
															break;
														default:
															$('#download').prop('disabled',false);
															break;
													}
												});
											</script>
										</form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-dismiss="modal">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<button class="btn btn-primary" href="#" data-toggle="modal" data-target="#viewModal">View Attendance Logs</button>
					</li>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</main>

<!--Includes the defined footer-->
<?php include '../view/footer.php'; ?>