<?php
include "db.php";

$check = $conn->prepare("SELECT id FROM users WHERE username = 'admin'");
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    $hashed_password = password_hash("1234", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, surname) VALUES (?, ?, 'admin', 'System', 'Administrator')");
    $stmt->bind_param("ss", $username, $password);

    $username = 'admin';
    $password = $hashed_password;

    $stmt->execute();
}
?>
