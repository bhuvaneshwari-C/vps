<?php
 
 $servername="localhost";
 $db_username="root";
 $db_password="password";
 $dbname = "ecommerce";

 $conn = new mysqli($servername, $db_username, $db_password, $dbname);

 if($conn->connect_error){
    die("connection failed: " . $conn->connect_error);
 }
 
 ?>