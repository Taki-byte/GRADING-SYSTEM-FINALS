<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if ($role === "admin" && $username === "admin" && $password === "admin123") {
        $_SESSION["username"] = "admin";
        $_SESSION["role"] = "admin";
        header("Location: /grading-system/admin/admin.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["student_id"] = $user["student_id"];
            $_SESSION["prof_id"] = $user["prof_id"];

            if ($user["role"] === "student") {
                header("Location: student/dashboardstudent.php");
            } elseif ($user["role"] === "teacher") {
                header("Location: teacher/dashboard.php");
            }
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="login2.php">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Login</button>
            <select name="role" required>
                <option value="" disabled selected>Select</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </form>
        <div class="sign_up">
            Don't have an account? <a href="signup2.php">Sign Up here</a>
        </div>
    </div>
    
</body>
</html>
