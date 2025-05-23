<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
$surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$middle_name = isset($_POST['middle_name']) ? trim($_POST['middle_name']) : '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';

if ($id <= 0) {
    http_response_code(400);
    echo "Invalid user ID.";
    exit;
}

$stmt = $conn->prepare("UPDATE users SET user_id=?, surname=?, first_name=?, middle_name=?, role=? WHERE id=?");
$stmt->bind_param("sssssi", $user_id, $surname, $first_name, $middle_name, $role, $id);

if ($stmt->execute()) {
    echo "User updated successfully!";
} else {
    http_response_code(500);
    echo "Failed to update user.";
}
$stmt->close();
$conn->close();
?>