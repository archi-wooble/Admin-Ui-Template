<?php
// Include your database connection file
include '../dbConnection.php';

// Prepare and execute query to fetch all users
$select = "SELECT * FROM `users_with_message`";
$query = mysqli_query($conn, $select);

// Fetch results into associative array
$users = [];
while ($row = mysqli_fetch_assoc($query)) {
    $users[] = $row;
}

// Close database connection
mysqli_close($conn);

// Return JSON response
echo json_encode($users);
?>
