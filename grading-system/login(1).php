<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST["identifier"]);
    $password   = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT * 
           FROM users 
          WHERE student_id = ? 
             OR name       = ? 
          LIMIT 1"
    );
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["student_id"] = $user["student_id"];
        $_SESSION["role"]       = $user["role"];
        $_SESSION["name"]       = $user["name"];
        $_SESSION["surname"]    = $user["surname"];

        if ($user["role"] === "teacher") {
            header("Location: teacher/dashboard.php");
        } else {
            header("Location: student/dashboardstudent.php?id=" . urlencode($user["student_id"]));
        }
        exit;
    } else {
        $error = "Invalid ID/Name or password.";
    }
}
?>
<form method="POST">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
      <div style="color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <input type="text" name="identifier" placeholder="Student ID or Teacher First Name" required><br>
    <input type="password" name="password"   placeholder="Password"                 required><br>
    <button type="submit">Login</button>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</form>
