<?php
$servername = "127.0.0.1"; // Replace with your MySQL server name
$username = "root"; // Replace with your MySQL username
$password = "password"; // Replace with your MySQL password
$dbname = "exampledb"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
//if ($conn->connect_error) {
//    echo "Connection failed: " . $conn->connect_error;
//}
//else{
//    echo "Connected successfully";
//}
?>