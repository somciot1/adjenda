<main>
<div class="container">
    <h1>Successful login</h1>
    <h3>Account Email: <?php echo $_SESSION["accEmail"]?></h3>
    <h3>Account Type: 
        <?php
            if($_SESSION["accType"] == "S"){
                echo "Student";
            }
            else if($_SESSION["accType"] == "I"){
                echo "Instructor";
            }
            else{
                    echo "ERROR! No Account Type.";
                    exit;
            }
         ?>
    </h3>
</div>
</main>