<?php
include '../dbConnection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $profession = $_POST['profession'];
    $message = $_POST['message'];
    $years_of_experience = $_POST['years_of_experience'];

    // File upload handling
    $profileImage = null;
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        // Validate file type (you can extend this as per your requirements)
        $fileType = $_FILES['profileImage']['type'];
        $allowed = array('image/jpeg', 'image/png', 'image/gif');

        if (in_array($fileType, $allowed)) {
            $tempName = $_FILES['profileImage']['tmp_name'];
            $fileName = $_FILES['profileImage']['name'];
            $uploadPath = '../uploads/' . $fileName;

            // Check if the file already exists in the upload directory
            if (file_exists($uploadPath)) {
                echo json_encode(array('status' => 'error', 'message' => 'A profile image with this name already exists.'));
                exit;
            }

            // Move uploaded file to desired directory
            if (move_uploaded_file($tempName, $uploadPath)) {
                $profileImage = $fileName;
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to move uploaded file.'));
                exit;
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid file type. Allowed types: jpeg, png, gif.'));
            exit;
        }
    }

    // Check for duplicate names and emails
    $duplicateCheckQuery = "SELECT `name`, `email` FROM `users_with_message` WHERE `id` != '$id' AND (`name`='$name' OR `email`='$email')";
    $duplicateCheckResult = mysqli_query($conn, $duplicateCheckQuery);

    // Initialize flags for duplicate detection
    $nameDuplicate = false;
    $emailDuplicate = false;

    while ($row = mysqli_fetch_assoc($duplicateCheckResult)) {
        if ($row['name'] === $name) {
            $nameDuplicate = true;
        }
        if ($row['email'] === $email) {
            $emailDuplicate = true;
        }
    }

    if ($nameDuplicate || $emailDuplicate) {
        // Clean up uploaded file if it was uploaded
        if ($profileImage !== null) {
            $tempFile = '../uploads/' . $profileImage;
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        $errorMessage = 'A user with this ';
        if ($nameDuplicate && $emailDuplicate) {
            $errorMessage .= 'name and email already exist.';
        } elseif ($nameDuplicate) {
            $errorMessage .= 'name already exists.';
        } elseif ($emailDuplicate) {
            $errorMessage .= 'email already exists.';
        }

        echo json_encode(array('status' => 'error', 'message' => $errorMessage));
        exit;
    }

    // Retrieve the current profile image from the database
    $selectQuery = "SELECT `profile_image` FROM `users_with_message` WHERE `id`='$id'";
    $selectResult = mysqli_query($conn, $selectQuery);
    $currentProfileImage = mysqli_fetch_assoc($selectResult)['profile_image'];

    // Prepare and execute update query
    $updateQuery = "UPDATE `users_with_message` SET `name`='$name', `email`='$email', `profession`='$profession', `message`='$message', `years_of_experience`='$years_of_experience'";
    if ($profileImage !== null) {
        // Add the new image path to the update query
        $updateQuery .= ", `profile_image`='$profileImage'";
    }
    $updateQuery .= " WHERE `id`='$id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Remove the old profile image if a new one was uploaded
        if ($profileImage !== null && $currentProfileImage && $currentProfileImage !== $profileImage) {
            $oldFile = '../uploads/' . $currentProfileImage;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Fetch updated user data
        $result = mysqli_query($conn, "SELECT * FROM `users_with_message` WHERE `id`='$id'");
        $updatedUserData = mysqli_fetch_assoc($result);

        // Return updated user data as JSON response
        echo json_encode(array('status' => 'success', 'userData' => $updatedUserData));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error updating user data: ' . mysqli_error($conn)));
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>
