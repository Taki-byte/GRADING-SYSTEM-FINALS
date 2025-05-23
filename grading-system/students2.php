<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $firstname = $_POST["firstname"];
    $surname = $_POST["surname"];
    $middle_initial = $_POST["middle_initial"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (student_id, first_name, surname, middle_initial, username, password, role)
            VALUES (?, ?, ?, ?, ?, ?, 'student')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $student_id, $firstname, $surname, $middle_initial, $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Student registered successfully!";
        header("Location: login2.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <div class="students">
          <h2>Register</h2>
          <form method="POST" action="signup2.php">
              <label for="student_id">Enter Your Student ID</label>
              <input type="text" id="student_id" name="student_id" required><br><br>

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

              <button type="submit">Submit</button>
          </form>
          <p>Already have an account? <a href="login2.php">Login here!</a></p>
        </div> 
    </div>

</body>
</html>
