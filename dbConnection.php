<?php
$servername = getenv('DB_SERVER'); // Get database server from environment variable
$username = getenv('DB_USERNAME'); // Get database username from environment variable
$password = getenv('DB_PASSWORD'); // Get database password from environment variable
$dbname = getenv('DB_NAME'); // Get database name from environment variable

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
