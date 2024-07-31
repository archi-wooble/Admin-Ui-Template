<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Optionally clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Send response
echo "Logged out successfully";
?>
