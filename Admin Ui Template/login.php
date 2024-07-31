<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php"); // Redirect to dashboard if already logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="icon" href="../img/favicon%20(2).ico" type="image/x-icon"/>
</head>
<body class="g-0 p-0 overflow-x-hidden" style="min-width: 100vw">
<div class="container-fluid d-flex flex-column justify-content-center align-items-center align-content-center" style="height: 100vh;">
    <div class="w-auto">
        <h2 class="text-center">Login to Admin UI</h2>
        <form id="loginForm" style="padding: 2rem; border: 1px solid #880e4f; border-radius: 2%;">
            <div class="form-group">
                <label for="Email">Email:</label>
                <input type="text" id="Email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="login-btn d-flex flex-column flex-sm-row justify-content-between">
                <button type="button" class="btn btn-warning mt-3" onclick="submitLoginData()">Login</button>
                <!-- Trigger Modal Button -->
                <button type="button" class="btn btn-warning mt-3" data-toggle="modal" data-target="#registerModal">
                    Register
                </button>
            </div>
        </form>
    </div>
    <!-- Registration Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Password:</label>
                            <input type="password" id="registerPassword" name="password" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="registerSubmit" class="btn btn-warning" onclick="submitUserData()">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal HTML -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">You are not registered. Please register first.</p>
                </div>
                <div class="modal-footer login-btn d-flex flex-column flex-sm-row justify-content-between">
                    <button type="button" class="btn btn-secondary mb-3" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="registerFromError">Register</button>
                </div>
            </div>
        </div>
    </div>

</div>
<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        Developed by
        <a class="text-body" href="https://github.com/archielicious" style="color: salmon">Archishman Dash</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- jQuery and Bootstrap Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- AJAX Script -->
<script>
    function submitLoginData() {
        // Prevent the default form submission
        event.preventDefault();

        // Get form data
        var formData = $("#loginForm").serialize();

        // AJAX request
        $.ajax({
            url: '../ajax/login_process.php', // URL of the backend script
            type: 'POST', // Method type
            data: formData, // Data to be sent
            success: function (response) {
                if (response === "Login successful") {
                    // Redirect to index.php upon successful login
                    window.location.href = './index.php';
                } else {
                    // Show error modal with message
                    $('#errorMessage').text(response);
                    $('#errorModal').modal('show');
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                if (xhr.status === 400) {
                    console.log(xhr)
                    errorMsg = (xhr.responseText);
                }
                $('#errorMessage').text(errorMsg);
                $('#errorModal').modal('show');
            }
        });
    }

    function submitUserData() {
        // Prevent the default form submission
        event.preventDefault();

        // Get form data
        var formData = $("#registerForm").serialize();

        // AJAX request
        $.ajax({
            url: '../ajax/register_process.php', // URL of the backend script
            type: 'POST', // Method type
            data: formData, // Data to be sent
            success: function (response) {
                // Handle success or error message
                console.log(response)
                // alert(response); // Show response message

                if (response === "Registration successful!") {
                    // Close modal and reset form
                    $('#registerModal').modal('hide');
                    $("#registerForm")[0].reset();
                    alert(response);
                    // Optionally redirect or reload page
                    location.reload(); // Reload the current page
                }
                else {
                    // Show error message in the error modal
                    $('#errorMessage').text(response);
                    $('#errorModal').modal('show');
                }
            },
            error: function (xhr, status, error) {
                // Handle error
                alert("An error occurred: " + error);
                $('#errorMessage').text("An error occurred: " + error);
                $('#errorModal').modal('show');
            }
        });
    }

    $(document).ready(function () {
        // Handle "Register" button click in error modal
        $('#registerFromError').on('click', function () {
            $('#errorModal').modal('hide'); // Close error modal
            $('#registerModal').modal('show'); // Open registration modal
        });
    });
</script>

</body>
</html>
