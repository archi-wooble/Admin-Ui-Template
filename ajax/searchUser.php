<?php
include '../dbConnection.php';

if (isset($_POST['query']) && !empty($_POST['query'])) {
    $searchQuery = $_POST['query'];


    $stmt = $conn->prepare("SELECT * FROM `users_with_message` WHERE `name` LIKE ? OR `email` LIKE ? OR `profession` LIKE ? OR `years_of_experience` LIKE ?");
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($users);
} else {
    echo json_encode([]);
}
?>
