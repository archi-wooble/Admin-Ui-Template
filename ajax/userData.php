<?php
include '../dbConnection.php';

// Check if form data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $profession = $_POST['profession'];
    $message = $_POST['message'];
    $years_of_experience = $_POST['years_of_experience'];

    // Initialize file handling variables
    $uploadedFile = null;
    $targetFile = null;
    $fileName = null;

    // Handle image upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $tempFile = $_FILES['profileImage']['tmp_name'];
        $fileName = basename($_FILES['profileImage']['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($tempFile);
        if ($check === false) {
            $response = [
                'status' => 'error',
                'message' => 'File is not an image.'
            ];
            http_response_code(400); // Bad Request
            echo json_encode($response);
            exit;
        }

        // Check if file size is within limit
        if ($_FILES['profileImage']['size'] > 500000) {
            $response = [
                'status' => 'error',
                'message' => 'Sorry, your file is too large.'
            ];
            http_response_code(400); // Bad Request
            echo json_encode($response);
            exit;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $response = [
                'status' => 'error',
                'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'
            ];
            http_response_code(400); // Bad Request
            echo json_encode($response);
            exit;
        }

        // File is valid; move it to a temporary location
        if (!move_uploaded_file($tempFile, $targetFile)) {
            $response = [
                'status' => 'error',
                'message' => 'Sorry, there was an error uploading your file.'
            ];
            http_response_code(500); // Internal Server Error
            echo json_encode($response);
            exit;
        }
    }

    // Check for duplicate names or emails
    $duplicateCheckQuery = "SELECT COUNT(*) AS count, 
                                    SUM(CASE WHEN name = '$name' THEN 1 ELSE 0 END) AS name_count,
                                    SUM(CASE WHEN email = '$email' THEN 1 ELSE 0 END) AS email_count
                            FROM users_with_message 
                            WHERE id != '$id'";
    $duplicateCheckResult = $conn->query($duplicateCheckQuery);

    if ($duplicateCheckResult) {
        $row = $duplicateCheckResult->fetch_assoc();
        $nameDuplicate = $row['name_count'] > 0;
        $emailDuplicate = $row['email_count'] > 0;

        if ($nameDuplicate || $emailDuplicate) {
            // Clean up uploaded file if it was uploaded
            if ($targetFile && file_exists($targetFile)) {
                unlink($targetFile);
            }

            $errorMessage = 'A user with this ';
            if ($nameDuplicate && $emailDuplicate) {
                $errorMessage .= 'name and email already exists.';
            } elseif ($nameDuplicate) {
                $errorMessage .= 'name already exists.';
            } elseif ($emailDuplicate) {
                $errorMessage .= 'email already exists.';
            }

            $response = [
                'status' => 'error',
                'message' => $errorMessage
            ];
            http_response_code(409); // Conflict
            echo json_encode($response);
            exit;
        }
    } else {
        // Clean up uploaded file if there was an error checking for duplicates
        if ($targetFile && file_exists($targetFile)) {
            unlink($targetFile);
        }

        $response = [
            'status' => 'error',
            'message' => 'Error checking for duplicates: ' . $conn->error
        ];
        http_response_code(500); // Internal Server Error
        echo json_encode($response);
        exit;
    }

    // Now insert other form data into database
    $sql = "INSERT INTO users_with_message (name, email, profession, message, profile_image, years_of_experience) 
            VALUES ('$name', '$email', '$profession', '$message', '$fileName', '$years_of_experience')";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'status' => 'success',
            'message' => 'Data inserted successfully'
        ];
        http_response_code(200); // OK
        echo json_encode($response);
    } else {
        // Clean up uploaded file if there was an error inserting data
        if ($targetFile && file_exists($targetFile)) {
            unlink($targetFile);
        }

        $response = [
            'status' => 'error',
            'message' => 'Error: ' . $conn->error
        ];
        http_response_code(500); // Internal Server Error
        echo json_encode($response);
    }

    // Close connection
    $conn->close();
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
    http_response_code(405); // Method Not Allowed
    echo json_encode($response);
}
?>
