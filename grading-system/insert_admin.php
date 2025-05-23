<?php
include "db.php";

$check = $conn->prepare("SELECT username FROM users WHERE username = 'admin'");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG IN</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <div class="students">
          <label for="admin_id">Enter Your Admin ID</label>
          <input type="text" id="admin_id">
          <button type="submit">Submit</button>
        </div> 
    </div>
    
</body>
</html>
