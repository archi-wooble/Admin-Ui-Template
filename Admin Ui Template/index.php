<?php
include '../dbConnection.php';
?>
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Retrieve user's name from session
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <title>Interactive Admin UI Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <!-- MDB icon -->
    <link rel="icon" href="../img/favicon%20(1).ico" type="image/x-icon"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"/>
    <!-- MDB -->
    <link rel="stylesheet" href="../css/mdb.min.css"/>
    <style>
        .btn-group {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; /* Adjust as per your design preference */
        }

        /* Optional: Reduce button padding for smaller screens */
        @media only screen and (max-width: 576px) {
            .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>

</head>
<body class="overflow-x-hidden">
<div class="main-header mx-2">
    <h2 class="text-center mt-2" style="background-color: #1de9b6; color:#880e4f;">Admin UI</h2>
</div>
<div class="container-fluid d-flex flex-wrap justify-content-center justify-content-sm-around my-5 mb-0" style="max-width: 95%;">
    <div class="input-group my-2" style="width: 30rem;">
        <div class="form-outline search-user" data-mdb-input-init>
            <input id="search-input" type="search" class="form-control" oninput="searchFn(this.value)"/>
            <label class="form-label" for="search-input">Search</label>
        </div>
    </div>
    <!-- Button trigger modal -->
    <div class="add-user my-2">
        <button type="button" class="btn btn-outline-secondary" data-mdb-ripple-init data-mdb-modal-init
                data-mdb-target="#exampleModal">
            <i class="fas fa-folder-plus"></i>
            <span style="margin-left: 5px;">ADD USER</span>
        </button>
    </div>
</div>
<div class="container-fluid g-0 p-0 m-0 overflow-x-hidden m-auto d-flex justify-content-center align-items-center flex-column" style="height: 100vh;">

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="padding: 1.5rem;">Add New User Data</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="main m-2" style=" border: 1px solid #003153; border-radius: 0.5%;">
                        <form id="contactForm" style=" padding: 1rem;">
                            <!-- Name input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="form4Example1" class="form-control"/>
                                <label class="form-label" for="form4Example1">Name</label>
                            </div>

                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="form4Example2" class="form-control"/>
                                <label class="form-label" for="form4Example2">Email address</label>
                            </div>

                            <!-- Profession input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="form4Example3" class="form-control"/>
                                <label class="form-label" for="form4Example3">Profession</label>
                            </div>

                            <!-- Message input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <textarea class="form-control" id="form4Example4" rows="4"></textarea>
                                <label class="form-label" for="form4Example4">Message</label>
                            </div>
                            <!--                        Years of Experience input-->
                            <div class="form-outline mb-4">
                                <select id="formYearsOfExperience" class="form-select">
                                    <option value="NA">Select Years of Experience</option>
                                    <option value="0">Less than 1 year</option>
                                    <option value="1-3">1-3 years</option>
                                    <option value="3-5">3-5 years</option>
                                    <option value="5-10">5-10 years</option>
                                    <option value=">10">More than 10 years</option>
                                </select>
                                <label class="form-label" for="formYearsOfExperience">Years of Experience</label>
                            </div>
                            <div class="add-btn d-flex flex-column flex-sm-row justify-content-center align-items-center align-items-sm-start justify-content-sm-between">
                                <div class="image-button" style="max-width: 8.5rem;">
                                    <label class="btn btn-outline-info mb-4">
                                        Upload Image
                                        <input type="file" id="profileImage" name="profileImage" style="display: none;"
                                               accept="image/*" class="text-start" onchange="updateImageLabel(this)">
                                    </label>
                                    <div id="selectedFilePath">No file chosen</div>
                                </div>
                                <!-- Submit button -->
                                <div class="submit-button">
                                    <button type="submit" class="btn btn btn-outline-info mb-4 mt-2 mt-sm-0" data-mdb-ripple-init
                                            data-mdb-dismiss="modal" onClick="submitFn(event)">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mx-4 my-5 mt-0" style="border: 1px solid #003153; border-radius: 0.5%;  overflow-x: auto; width: 90%; overflow-y: auto">
        <table class="table table-responsive table-striped table-hover caption-top" style="padding: 1rem; ">
            <caption style="padding-left: 1.4rem; padding-right: 1.4rem">
                User's Data
            </caption>
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col" style="text-align: center;">Profile Img</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Profession</th>
                <th scope="col">Message</th>
                <th scope="col" class="text-center">Years of Experience</th>
                <th scope="col" style="text-align: center; width: 15%;">Action</th>
            </tr>
            </thead>
            <tbody style="vertical-align: middle">
            <?php
            $select = "SELECT * FROM `users_with_message`";
            $query = mysqli_query($conn, $select);
            while ($rows = mysqli_fetch_assoc($query)) {
                ?>
                <!-- PHP/HTML for populating table rows -->
                <tr id="user-<?php echo $rows['id']; ?>">
                    <th scope="row"><?php echo $rows['id']; ?></th>
                    <td class="text-center">
                        <img
                                src="../uploads/<?php echo htmlspecialchars($rows['profile_image']); ?>"
                                onerror="this.src='https://i0.wp.com/port2flavors.com/wp-content/uploads/2022/07/placeholder-614.png?w=1200&ssl=1'"
                                alt="Profile Image"
                                style="width: 45px; height: 45px;"
                                class="rounded-circle img-fluid"
                        >
                    </td>
                    <td><?php echo $rows['name']; ?></td>
                    <td><?php echo $rows['email']; ?></td>
                    <td class="text-center"><?php echo $rows['profession']; ?></td>
                    <td><?php echo $rows['message']; ?></td>
                    <td class="text-center"><?php echo $rows['years_of_experience']; ?></td>
                    <td style="text-align: center;">
                        <div class="btn-group" role="group" style="width: 10rem;">
                            <button type="button" class="btn btn-outline-info" data-mdb-tooltip-init
                                    data-mdb-placement="top" title="Edit"
                                    onclick="editRow(<?php echo $rows['id']; ?>);">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-mdb-tooltip-init
                                    data-mdb-placement="top" title="Delete" data-mdb-ripple-init
                                    onclick="deleteUser(<?php echo $rows['id']; ?>)">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Log Out Button -->
    <div class="container logout-btn d-flex flex-column flex-sm-row justify-content-center justify-content-sm-between align-items-center align-items-sm-end my-5 mx-0">
        <h4 class="mb-0 text-center text-sm-start">Welcome, You are now Lgged in as <?php echo htmlspecialchars($user_name); ?> !</h4>
        <button type="button" class="btn btn-danger mt-2" onclick="logout()">Log Out</button>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="container-fluid">
                <div class="modal-content">
                    <div class="modal-header-button p-3 pb-0 text-end">
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="editModalLabel">Edit User Data</h5>
                    </div>
                    <div class="modal-body" style="margin: 1rem; border: 1px solid #003153; border-radius: 2%">
                        <form id="editForm">
                            <input type="hidden" id="editUserId" name="editUserId" value="">
                            <!-- Name input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="editUserName" name="editUserName" class="form-control"/>
                                <label class="form-label" for="editUserName">Name</label>
                            </div>

                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="editUserEmail" name="editUserEmail" class="form-control"/>
                                <label class="form-label" for="editUserEmail">Email address</label>
                            </div>

                            <!-- Profession input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="editUserProfession" name="editUserProfession" class="form-control"/>
                                <label class="form-label" for="editUserProfession">Profession</label>
                            </div>

                            <!-- Message input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                            <textarea class="form-control" id="editUserMessage" name="editUserMessage"
                                      rows="4"></textarea>
                                <label class="form-label" for="editUserMessage">Message</label>
                            </div>

                            <!--Years of Experience input-->
                            <div class="form-outline mb-4">
                                <select id="editUserYearsOfExperience" class="form-select">
                                    <option value="NA">Select Years of Experience</option>
                                    <option value="0">Less than 1 year</option>
                                    <option value="1-3">1-3 years</option>
                                    <option value="3-5">3-5 years</option>
                                    <option value="5-10">5-10 years</option>
                                    <option value=">10">More than 10 years</option>
                                </select>
                                <label class="form-label" for="editUserYearsOfExperience">Years of Experience</label>
                            </div>

                            <!-- Upload Image -->
                            <div class="form-outline mb-4">
                                <label class="btn btn-outline-info mb-4">
                                    Update Image
                                    <input type="file" id="editProfileImage" name="editProfileImage" style="display: none;"
                                           accept="image/*" class="text-start" onchange="updateEditImageLabel(this)">
                                </label>
                                <div id="editSelectedFilePath"></div>
                            </div>

                            <!-- Image Preview -->
                            <div id="editProfileImageContainer" style="text-align: start; margin-bottom: 1rem;">
                            </div>
                            <!-- Submit button -->
                            <div class="update-button" style="text-align: end">
                                <button type="button" class="btn btn-outline-success" onclick="updateUser()">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">An error occurred.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onClick="clearSearchbar()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="bg-body-tertiary text-center text-lg-start">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        Developed by
        <a class="text-body" href="https://github.com/archielicious" style="color: crimson !important;">Archishman Dash</a>
    </div>
    <!-- Copyright -->
</footer>

<!-- MDB -->
<script type="text/javascript" src="../js/mdb.umd.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Custom scripts -->
<script type="text/javascript">
    function logout() {
        // AJAX request to log out
        $.ajax({
            url: '../ajax/logout.php', // URL of the logout script
            type: 'POST',
            success: function (response) {
                // Redirect to login page or another page
                window.location.href = './login.php';
            },
            error: function (xhr, status, error) {
                // Handle error
                alert("An error occurred: " + error);
            }
        });
    }

    function editRow(id) {
        console.log("id - " + id);
        $.ajax({
            type: "POST",
            url: "../ajax/editUser.php",
            dataType: "json",
            data: {
                id: id,
            },
            success: function (userData) {
                if (userData.status === 'success') {
                    console.log("UserData", userData)
                    console.log("Profile_Image", userData.userData.profile_image)
                    // Populate edit modal fields with user data
                    $('#editUserId').val(userData.userData.id);
                    $('#editUserName').val(userData.userData.name);
                    $('#editUserEmail').val(userData.userData.email);
                    $('#editUserProfession').val(userData.userData.profession);
                    $('#editUserMessage').val(userData.userData.message || '');
                    $('#editUserYearsOfExperience').val(userData.userData.years_of_experience || '');
                    // Display profile image if exists
                    if (userData.userData.profile_image) {
                        // Concatenate the message and the profile image URL into a single string
                        $('#editProfileImageContainer').text("Existing: " + userData.userData.profile_image);
                    }

                    // Show the edit modal
                    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                } else {
                    // Handle error
                    console.error("Error fetching user data:", userData.message);
                    alert("Error fetching user data: " + userData.message);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error("Error fetching user data:", errorThrown);
                // Handle network or server error
                alert("Error fetching user data. Please try again later.");
            }
        });
    }


    // Function to reset file input and clear displayed file path
    function resetFileInput() {
        document.getElementById('profileImage').value = ''; // Reset file input value
        document.getElementById('selectedFilePath').textContent = 'No file selected'; // Clear displayed file path
    }

    // Function to handle adding a new user
    function submitFn(event) {
        event.preventDefault();

        // Get form values
        let name = document.getElementById('form4Example1').value.trim();
        let email = document.getElementById('form4Example2').value.trim();
        let profession = document.getElementById('form4Example3').value.trim();
        let message = document.getElementById('form4Example4').value.trim();
        let yearsOfExperience = document.getElementById('formYearsOfExperience').value;

        // Validate form fields
        if (!name || !email || !profession || !message || yearsOfExperience === '') {
            showErrorModal("Please fill in all fields and select years of experience.");
            return;
        }

        // Construct formData object
        let formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('profession', profession);
        formData.append('message', message);
        formData.append('years_of_experience', yearsOfExperience);

        // Append profile image if selected
        let profileImageInput = document.getElementById('profileImage');
        if (profileImageInput.files.length > 0) {
            formData.append('profileImage', profileImageInput.files[0]);
        }

        // AJAX request to send formData to server
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax/userData.php", true);

        xhr.onload = function () {
            let response;
            try {
                response = JSON.parse(xhr.responseText);
            } catch (e) {
                console.error('Error parsing response:', e);
                showErrorModal('Unexpected response format.');
                return;
            }

            if (xhr.status === 200) {
                // Success
                console.log("Data sent successfully:", response.message);
                alert(response.message);

                // Reset form fields after successful submission
                document.getElementById('form4Example1').value = '';
                document.getElementById('form4Example2').value = '';
                document.getElementById('form4Example3').value = '';
                document.getElementById('form4Example4').value = '';
                document.getElementById('formYearsOfExperience').value = '';

                // Reset file input and displayed file path
                resetFileInput();

                // Close the modal if using one
                var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
                modal.hide();
                // Refresh the page
                window.location.reload();
            } else if (xhr.status === 409) {
                // Conflict
                showErrorModal(response.message || "User with this name already exists.");
            } else {
                // Other errors
                console.error("Error sending data:", xhr.statusText);
                showErrorModal("An error occurred: " + (response.message || xhr.statusText));
            }
        };

        xhr.onerror = function () {
            console.error('Request failed.');
            showErrorModal('Request failed.');
        };

        xhr.send(formData);
    }

    // Function to show error messages in the modal
    function showErrorModal(message) {
        document.getElementById('errorMessage').textContent = message;
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    // Function to hide the error modal
    // function hideErrorModal() {
    //     var errorModalElement = document.getElementById('errorModal');
    //     var errorModal = bootstrap.Modal.getInstance(errorModalElement);
    //     if (errorModal) {
    //         errorModal.hide();
    //     }
    // }


    // Function to reset file input and clear displayed file path when modal is closed
    document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
        resetFileInput();
    });

    // Function to update the displayed file path when an image is selected
    function updateImageLabel(input) {
        const selectedFile = input.files[0];
        const selectedFilePathElement = document.getElementById('selectedFilePath');

        if (selectedFile) {
            selectedFilePathElement.textContent = `Selected File: ${selectedFile.name}`;
        } else {
            selectedFilePathElement.textContent = 'No file selected';
        }
    }


    // Function to reset file input and clear displayed file path when modal is closed
    document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
        resetFileInput();
    });


    // Function to handle updating a user
    function updateUser() {
        // Get form values
        let id = document.getElementById('editUserId').value;
        let name = document.getElementById('editUserName').value;
        let email = document.getElementById('editUserEmail').value;
        let profession = document.getElementById('editUserProfession').value;
        let message = document.getElementById('editUserMessage').value;
        let yearsOfExperience = document.getElementById('editUserYearsOfExperience').value;

        // Validate form fields (name, email, profession cannot be empty)
        if (!name || !email || !profession || yearsOfExperience === '') {
            alert("Name, Email, Profession, and Years of Experience are required fields.");
            return;
        }

        // Construct formData object
        let formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('email', email);
        formData.append('profession', profession);
        formData.append('message', message);
        formData.append('years_of_experience', yearsOfExperience);

        // Append profile image if selected
        let profileImageInput = document.getElementById('editProfileImage');
        if (profileImageInput.files.length > 0) {
            formData.append('profileImage', profileImageInput.files[0]);
        }

        // AJAX request to update user data
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ajax/updateUser.php", true);
        xhr.onload = function () {
            let responseData;

            try {
                responseData = JSON.parse(xhr.responseText);
            } catch (e) {
                console.error('Error parsing response:', e);
                showErrorModal('Unexpected response format.');
                return;
            }

            if (xhr.status === 200) {
                if (responseData.status === 'success') {
                    console.log("User updated successfully:", responseData.userData);
                    showErrorModal("User updated successfully:", responseData.userData);
                    // Optionally update the table or UI with the new data

                    // Hide the modal
                    var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    editModal.hide();
                } else if (responseData.status === 'error') {
                    // Handle specific errors returned by the server
                    console.error("Error updating user:", responseData.message);
                    showErrorModal("An error occurred: " + responseData.message);
                } else {
                    // Handle unexpected success status
                    console.error("Unexpected response status:", responseData);
                    showErrorModal("An unexpected error occurred.");
                }
            } else {
                // Non-200 HTTP status
                console.error("Request failed with status:", xhr.status);
                showErrorModal("An error occurred: " + xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Request failed.');
            showErrorModal('Request failed.');
        };

        xhr.send(formData);
    }


    function deleteUser(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../ajax/deleteUser.php", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        console.log("User deleted successfully:", response.message);
                        alert("User deleted successfully:", response.message);
                            // Remove the row from the table
                        let deletedRow = document.getElementById('user-' + userId);
                        if (deletedRow) {
                            deletedRow.remove();
                        } else {
                            console.error("Error deleting user: Row not found.");
                            alert("Error deleting user: Row not found.");
                            return; // Exit early
                        }

                        // Check if there are any rows left
                        let tableBody = document.getElementById('table-body'); // Adjust this ID as needed
                        if (tableBody && tableBody.children.length === 0) {
                            alert("No matching results found.");
                        }
                    } else {
                        console.error("Error deleting user:", response.message);
                        alert("Error deleting user: " + response.message);
                    }
                } else {
                    console.error("Error deleting user:", xhr.statusText);
                    alert("Error deleting user.");
                }
            };
            xhr.onerror = function () {
                console.error('Request failed.');
                alert('Request failed.');
            };
            // Send userId as JSON data
            xhr.send(JSON.stringify({userId: userId}));
        }
        // This will reload the page from the cache
        window.location.reload();
    }



    function updateEditImageLabel(input) {
        const selectedFile = input.files[0];
        const selectedFilePathElement = document.getElementById('editSelectedFilePath');

        if (selectedFile) {
            selectedFilePathElement.textContent = `Selected File: ${selectedFile.name}`;
        } else {
            selectedFilePathElement.textContent = ''; // Clear the text if no file selected
        }
    }


    function searchFn(searchQuery) {
        console.log("searching : " + searchQuery);

        if (searchQuery.length === 0) {
            // If search input is empty, show original table
            populateOriginalTable();
            return;
        }

        $.ajax({
            url: "../ajax/searchUser.php",
            type: "POST",
            data: {
                query: searchQuery
            },
            dataType: "json",
            success: function (response) {
                populateTable(response);
            },
            error: function (xhr, status, error) {
                // console.error("Error searching:", status, error);
                console.log("responce is :" + xhr.responseText);
            }
        });

    }

    // Function to populate table based on search results
    function populateTable(data) {
        let tableBody = document.querySelector("tbody");
        tableBody.innerHTML = "";

        if (data.length === 0) {
            // Display alert if no results found
            console.log("No matching results found.");
            showErrorModal("No matching results found.");
        } else {
            // Populate table with search results
            data.forEach(function (row) {
                let newRow = document.createElement("tr");
                newRow.id = 'user-' + row.id;
                newRow.innerHTML = `
            <th scope="row">${row.id}</th>
            <td class="text-center">
<img
    src="../uploads/${row.profile_image}"
    onerror="this.src='https://i0.wp.com/port2flavors.com/wp-content/uploads/2022/07/placeholder-614.png?w=1200&ssl=1'"
    alt="Profile Image"
    style="width: 45px; height: 45px;"
    class="rounded-circle"
>
            </td>
            <td>${row.name}</td>
            <td>${row.email}</td>
            <td>${row.profession}</td>
            <td>${row.message}</td>
            <td class="text-center">${row.years_of_experience}</td>
            <td style="text-align: center">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-info edit-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="fas fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;
                tableBody.appendChild(newRow);

                // Attach event listeners for edit and delete buttons
                newRow.querySelector('.edit-btn').addEventListener('click', function () {
                    editRow(row.id); // Pass the user ID to editRow function
                });

                newRow.querySelector('.delete-btn').addEventListener('click', function () {
                    deleteUser(row.id); // Pass the user ID to deleteUser function
                });
            });
            // Initialize tooltips for all elements with 'data-bs-toggle="tooltip"'
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    // Function to populate the original table with all users
    function populateOriginalTable() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "../ajax/getAllUsers.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                populateTable(response);
            } else {
                console.error("Error fetching original data:", xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Request failed.');
        };
        xhr.send();
    }
    function clearSearchbar(){
        let searchInput=document.getElementById("search-input")
        searchInput.value="";
        populateOriginalTable();
    }

    // Call populateOriginalTable on page load
    // document.addEventListener('DOMContentLoaded', function () {
    //     populateOriginalTable();
    // });

</script>
</body>
</html>