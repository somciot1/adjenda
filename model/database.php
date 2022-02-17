<?php 
    try {
      $db = new PDO('sqlite:../model/database.sqlite');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException $e) {
      $error_message = $e->getMessage();
      echo "<p>An error occured while connecting
      to the database: $error_message</p>";
      exit();
    }
?>