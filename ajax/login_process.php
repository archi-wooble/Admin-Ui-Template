<?php
include '../dbConnection.php'; // Adjust the path as needed

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize user input
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validate form inputs
if (empty($email) || empty($password)) {
    // Check which fields are empty and create a response accordingly
    $missingFields = [];
    if (empty($email)) {
        $missingFields[] = "email";
    }
    if (empty($password)) {
        $missingFields[] = "password";
    }

    $responseMessage = "The following fields are required: " . implode(", ", $missingFields);
    echo $responseMessage;
    http_response_code(400); // Bad Request
    exit;
}

// Prepare SQL statement to prevent SQL injection
$sql = "SELECT * FROM admin_details WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Fetch user data
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start the session and set logged-in status
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id']; // Optional: store user ID in session
        $_SESSION['user_name'] = $user['name']; // Store user's name in session
        echo "Login successful";
    } else {
        echo "Incorrect password, Try again";
    }
} else {
    echo "You are not registered. Please register first.";
}

// Close the connection
$stmt->close();
$conn->close();
?>
