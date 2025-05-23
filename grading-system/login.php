<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$conn = new mysqli("localhost", "root", "", "users");

$error = "";

// Utility: Make role lowercase for consistency
function normalize_role($role) {
    return strtolower(trim($role));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role     = normalize_role($_POST["role"]);
    $user_id  = trim($_POST["user_id"]);
    $password = $_POST["password"];

    // Admin login (hardcoded)
    if ($user_id === "admin" && $password === "1234") {
        $_SESSION['user_id'] = "admin";
        $_SESSION['first_name'] = "System";
        $_SESSION['surname'] = "Administrator";
        $_SESSION['middle_name'] = "";
        $_SESSION['section'] = "N/A";
        $_SESSION['role'] = "admin";
        header("Location: /grading-system/admin/admin.php");
        exit;
    }

    // User login
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND LOWER(role) = ?");
    $stmt->bind_param("ss", $user_id, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if (!$user) {
        $error = "No user found for that ID and role.";
    } else {
        // Password check (choose correct verification method)
        if (
            (password_verify($password, $user['password'])) ||
            ($password === $user['password']) // fallback for plain text (not secure)
        ) {
            $_SESSION['user_id']     = $user['user_id'];
            $_SESSION['first_name']  = $user['first_name'];
            $_SESSION['surname']     = $user['surname'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['section']     = $user['section'];
            $_SESSION['role']        = $user['role'];

            if (strtolower($user["role"]) === "student") {
                header("Location: /grading-system/student/dashboardstudent.php");
            } else if (strtolower($user["role"]) === "teacher") {
                header("Location: /grading-system/teacher/dashboard.php");
            } else {
                // fallback: unknown role
                $error = "Unknown user role: " . htmlspecialchars($user["role"]);
                session_destroy();
            }
            exit;
        } else {
            $error = "Invalid Password or Username.";
        }
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
    <img src="BCP.png" style="width:100px; height:auto;">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>Login as:</p>
        <select name="role" id="role" onchange="toggleLoginLabel()" required>
            <option value="">Select</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br><br>
        
        <label id="id-label">Student/Teacher ID:</label>
        <input type="text" name="user_id" required><br>
        
        <p>Password:</p>
        <input type="password" name="password" required><br>
        
        <input type="submit" value="Login">
    </form>

    <p>No account? <a href="register.php">Sign in here!</a></p>
</div>

<script>
function toggleLoginLabel() {
    const role = document.getElementById("role").value;
    const idLabel = document.getElementById("id-label");
    if (role === "student") idLabel.textContent = "Student ID:";
    else if (role === "teacher") idLabel.textContent = "Teacher ID:";
    else idLabel.textContent = "Student/Teacher ID:";
}
</script>

</body>
</html>