
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'database.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $middle_initial = htmlspecialchars(trim($_POST['middle_initial']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    if (empty($firstname) || empty($surname) || empty($middle_initial) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstname, surname, middle_initial, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $surname, $middle_initial, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful. You can now log in.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>REGISTER AS STUDENT</h1>
    <form action="register.php" method="POST">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required><br><br>
        <label for="middle_initial">Middle Initial:</label>
        <input type="text" id="middle_initial" name="middle_initial" maxlength="1" required><br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Register</button>
        <p>Already have account? <a href="login_student.php">Log In</a></p>
    </form>
</body>
</html>
