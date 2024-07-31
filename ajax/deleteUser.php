<?php
// Include your database connection file (e.g., dbConnection.php)
include '../dbConnection.php';

$targetDir = "../uploads/";

// Function to delete a file
function deleteFile($filePath) {
    // Check if file exists
    if (file_exists($filePath)) {
        // Attempt to delete the file
        if (unlink($filePath)) {
            return true; // File deleted successfully
        } else {
            return false; // Error deleting file
        }
    } else {
        return true; // File doesn't exist, consider it deleted
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the user ID from the POST request
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if userId is provided
    if (isset($data['userId'])) {
        $userId = intval($data['userId']); // Convert to integer for safety

        // Fetch the profile_image associated with the user
        $fetchQuery = "SELECT profile_image FROM users_with_message WHERE id = " . $userId;
        $fetchResult = $conn->query($fetchQuery);

        if ($fetchResult) {
            // Fetch the profile_image from the result
            $row = $fetchResult->fetch_assoc();
            $profileImage = $row['profile_image'];

            // Delete the user from the database
            $deleteQuery = "DELETE FROM users_with_message WHERE id = " . $userId;
            $deleteResult = $conn->query($deleteQuery);

            if ($deleteResult) {
                if ($conn->affected_rows > 0) {
                    // Delete the associated file if it exists
                    if (!empty($profileImage)) {
                        $filePath = $targetDir . $profileImage;
                        if (deleteFile($filePath)) {
                            echo json_encode(array('status' => 'success', 'message' => 'User and associated file deleted successfully.'));
                        } else {
                            echo json_encode(array('status' => 'error', 'message' => 'Error deleting associated file.'));
                        }
                    } else {
                        echo json_encode(array('status' => 'success', 'message' => 'User deleted successfully.'));
                    }
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'User not found or already deleted.'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Error deleting user: ' . $conn->error));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Error fetching user details: ' . $conn->error));
        }
    } else {
        // Return an error message if userId is not provided
        echo json_encode(array('status' => 'error', 'message' => 'User ID (userId) not provided.'));
    }
} else {
    // Return an error if the request method is not POST
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}

// Close the database connection
$conn->close();
?>
