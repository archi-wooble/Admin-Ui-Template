<?php
// Include your database connection file (e.g., dbConnection.php)
include '../dbConnection.php';

// Check if the request method is POST and 'id' parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Sanitize the incoming ID parameter
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Query to fetch user data from the database
    $select = "SELECT * FROM `users_with_message` WHERE id = '$id'";
    $query = mysqli_query($conn, $select);

    if ($query) {
        // Fetch user data
        $userData = mysqli_fetch_assoc($query);

        // Check if user data exists
        if ($userData) {
            // Return user data as JSON response
            echo json_encode(array('status' => 'success', 'userData' => $userData));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'User not found.'));
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error querying database.'));
    }
} else {
    // Handle invalid request
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request.'));
}
?>
