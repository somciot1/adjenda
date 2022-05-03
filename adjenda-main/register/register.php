<?php session_start(); //Resumes the session ?> 

<!--Includes the defined header-->
<?php include '../view/initHeader.php'; ?>

<main>
	<div style="background-color: white; padding: 1em 1em; width: 25%; border-radius: 0.5em; margin: auto">
		<img src="../images/logo.png" alt="logo" width="75%" style="display: block; margin: auto">
		<form action = "commit.php" method = "post">
			<div class="form-group">
				<a href="../login/login.php" class="btn btn-success">Exit</a>
			</div>
			<!--Checks if error variable has been set in session array,
				and if so displays the defined message-->
				<?php
				if(isset($_SESSION["error"])){
					echo $_SESSION["error"];
				}
			?>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Enter email" pattern=".+edu" required>
				<small id="passwordHelp" class="form-text text-muted">Email must end in .edu</small>
			</div>
			<div class="form-group">
				<label for="fName">First Name:</label>
				<input type="text" class="form-control" id="fName" name="fName" placeholder="Enter first name" pattern="[A-Za-z]+" required>
			</div>
			<div class="form-group">
				<label for="lName">Last Name:</label>
				<input type="text" class="form-control" id="lName" name="lName" placeholder="Enter last name" pattern="[A-Za-z]+" required>
			</div>
			<div class="form-group">
				<label for="pass">Password:</label>
				<input type="password" class ="form-control" id="pass" name="pass" placeholder="Enter password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*+~=?\|<>/]).{8,}" required>
				<small id="passwordHelp" class="form-text text-muted">Requires length of at least 8 characters </br> 
					(one lowercase letter, one uppercase letter, one number, one special character)</small>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary" style="display: block; margin: auto;">Submit</button>
			</div>
		</form>
		<!--Unsets error variable in session arrray-->
		<?php
			unset($_SESSION["error"]);
		?>
	</div>
</main>

<!--Includes the defined footer-->
<?php include '../view/initFooter.php'; ?>