<!--Includes the defined header-->
<?php
$title = 'Dashboard';
include '../view/header.php';
require_once('../model/rosterFunctions.php');
?>



<main>
	<div style="width:94.5%; margin-left:2.5%">
		<h1>Courses</h1>
		<hr border-top: 10px solid black; border-radius: 5px;>

        <!--Course Card Counter-->
        <?php $counter = 0 ?>
        <!--Table formatting for each course-->
        <table style="border-collapse: separate; border-spacing: 14px;">
                <!--checks if the user is an instructor, if so displays course creation card-->
                <?php if($_SESSION["accType"] == "I") : ?>
                    <td>
                        <div class="card bg-light mb-3" style="height: 12rem; width: 18rem">
                            <div class="card-header">Course Creation</div>
                            <div class="card-body text-dark" style="padding-top:18%;margin: auto">
                                <!--Course creation popup form-->
                                <form action="dash.php" method="post">
                                    <input type="hidden" name="action" value="createCourse">
                                    <!--Name/Date/Time Modal-->
                                    <div class="modal fade" id="initialModal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1>Course Creation</h1>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="fname">Course Name:</label>
                                                        <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Enter a name for the course" pattern="[A-Za-z0-9\s]+" required>
                                                    </div>
                                                    <div class="form-group days">
                                                        <p>Days:</p>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" name="selectedDays[]" value="Monday" required>
                                                            <label class="form-check-label">Mon</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" name="selectedDays[]" value="Tuesday" required>
                                                            <label class="form-check-label">Tue</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" name="selectedDays[]" value="Wednesday" required>
                                                            <label class="form-check-label">Wed</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" name="selectedDays[]" value="Thursday" required>
                                                            <label class="form-check-label">Thu</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" class="form-check-input" name="selectedDays[]" value="Friday" required>
                                                            <label class="form-check-label">Fri</label>
                                                        </div>
                                                    </div>
                                                    <p></p>
                                                    <div class="form-group row">
                                                        <label for="startHours" class="col-sm col-form-label">Start Time:</label>
                                                        <div class="col-sm">
                                                            <select class="form-control" name="startHours" required>
                                                                <option value="08">(AM) 08</option>
                                                                <option value="09">(AM) 09</option>
                                                                <option value="10">(AM) 10</option>
                                                                <option value="11">(AM) 11</option>
                                                                <option value="12">(PM) 12</option>
                                                                <option value="13">(PM) 01</option>
                                                                <option value="14">(PM) 02</option>
                                                                <option value="15">(PM) 03</option>
                                                                <option value="16">(PM) 04</option>
                                                                <option value="17">(PM) 05</option>
                                                                <option value="18">(PM) 06</option>
                                                                <option value="19">(PM) 07</option>
                                                                <option value="20">(PM) 08</option>
                                                            </select>
                                                        </div>
                                                        <h4>:</h4>
                                                        <div class="col-sm">
                                                            <select class="form-control" name="startMinutes" required>
                                                                <option value="00">00</option>
                                                                <option value="05">05</option>
                                                                <option value="10">10</option>
                                                                <option value="15">15</option>
                                                                <option value="20">20</option>
                                                                <option value="25">25</option>
                                                                <option value="30">30</option>
                                                                <option value="35">35</option>
                                                                <option value="40">40</option>
                                                                <option value="45">45</option>
                                                                <option value="50">50</option>
                                                                <option value="55">55</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="duration" class="col-sm-4 col-form-label">Duration:</label>
                                                        <div class="col-sm">
                                                            <select class="form-control" name="duration" required>
                                                                <option value="1">1hrs 15mins</option>
                                                                <option value="2">2hrs 30mins</option>
                                                                <option value="3">3hrs 00mins</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="semester" class="col-sm-4 col-form-label">Semester:</label>
                                                        <div class="col-sm">
                                                            <select class="form-control" id="semester" name="semester" readonly required>
                                                                <option value="FALL">FALL (Sep to Dec)</option>
                                                                <option value="SPRING">SPRING (Jan to May)</option>
                                                                <option value="WINTER">WINTER (Dec to Jan)</option>
                                                                <option value="SUMMER">SUMMER (June to Aug)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" data-dismiss="modal">Exit</button>
                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Form Scripting-->
                                    <script type="text/javascript">
                                        //Requires at least one checkbox be selected from days
                                        $(function(){
                                            var requiredCheckboxes = $('.days :checkbox[required]');
                                            requiredCheckboxes.change(function(){
                                                if(requiredCheckboxes.is(':checked')) {
                                                    requiredCheckboxes.removeAttr('required');
                                                } 
                                                else {
                                                    requiredCheckboxes.attr('required', 'required');
                                                }
                                            });
                                        });
                                        //Only allows two day checkboxes to be selected
                                        $("input:checkbox[name='selectedDays[]']").click(
                                            function() {
                                                var bol = $("input:checkbox:checked").length >= 2;     
                                                $("input:checkbox").not(":checked").attr("disabled",bol);
                                            }
                                        );
                                        //Gets current month
                                        var date = new Date();
                                        var month = date.getMonth();
                                        //Automatically selects semester based on current date
                                        $(function(){
                                            if((month >= 9) && (month < 12)){
                                                $("#semester option[value='FALL']").attr('selected', 'selected');
                                            }
                                            else if(month == 12){
                                                $("#semester option[value='WINTER']").attr('selected', 'selected');
                                            }
                                            else if((month >= 1) && (month < 5)){
                                                $("#semester option[value='SPRING']").attr('selected', 'selected');
                                            }
                                            else if((month >= 5) && (month <= 7)){
                                                $("#semester option[value='SUMMER']").attr('selected', 'selected');
                                            }
                                            //disables the nonselected options
                                            $("#semester option:not(:selected)").prop("disabled", true);
                                        });
                                    </script>
                                </form>
                                <button class="btn btn-primary btn-block" href="#" data-toggle="modal" data-target="#initialModal">Create New Course</button>
                            </div>
                        </div>
                    </td>
                    <?php $counter+=1; //increments course card counter ?>
                <?php endif;?>
                <!--Checks if the account is associated with any courses-->
                <?php if(!empty($courses)) : ?>
                    <!--Goes through each retrieved course and displays them as cards, allowing for the user to navigate to one-->
                    <?php foreach ($courses as $course): ?>
                        <!--Checks if the user is an instructor-->
                        <!--if not then checks if the student has accepted enrollment into each course and displays those that they have-->
                        <?php if($_SESSION["accType"] == "I" || getRosterEnrollment($course['id'],$_SESSION['accEmail']) == 1) : ?>
                            <!--Course Cards-->
                            <td>
                                <div class="card bg-light mb-3" style="height: 12rem; width: 18rem;">
                                    <div class="card-header">Course ID: <?php echo $course['id'] ?></div>
                                    <div class="card-body text-dark">
                                        <h5 class="card-title"><?php echo $course['name'] ?></h5>
                                        <form action="dash.php" method="post">
                                            <input type="hidden" name="action" value="selectCourse">
                                            <input type="hidden" name="courseID" value="<?php echo $course['id'] ?>">
                                            <button type="submit" class="btn btn-primary">Select</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <?php $counter+=1; //increments course card counter ?>
                            <!--Checks if a new row needs to be created for spacing (creates a new row every 4 cards)-->
                            <?php 
                                if($counter == 4){
                                    echo "<tr></tr>";
                                    $counter = 0; //resets course card counter
                                }
                            ?>
                        <?php endif; ?>
                    <?php endforeach;?>
                <!--Else there are no courses-->
                <?php else : ?>
                    <td>
                        <!--empty data cell-->
                    </td>
                <?php endif; ?>
        </table>
	</div>
</main>

<!--Includes the defined footer-->
<?php include '../view/footer.php'; ?>