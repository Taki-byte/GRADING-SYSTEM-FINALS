<?php
session_start();
$conn = new mysqli("localhost", "root", "", "users");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role        = $_POST["role"];
    $user_id     = $_POST["user_id"];
    $surname     = $_POST["surname"];
    $first_name  = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $password    = $_POST["password"];
    $confirm     = $_POST["confirm"];
    $section     = $_POST["section"] ?? '';

    if ($password !== $confirm) {
        $message = "<span style='color:red;'>Passwords do not match.</span>";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        if ($role === 'student' && empty($section)) {
            $message = "<span style='color:red;'>Section is required for students.</span>";
        } else {
            try {
                if ($role === 'student') {
                    $stmt = $conn->prepare("INSERT INTO users (user_id, surname, first_name, middle_name, password, role, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $user_id, $surname, $first_name, $middle_name, $hashed, $role, $section);
                } else {
                    $stmt = $conn->prepare("INSERT INTO users (user_id, surname, first_name, middle_name, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $user_id, $surname, $first_name, $middle_name, $hashed, $role);
                }

                $stmt->execute();
                $message = "<span style='color:green;'>Registration successful. <a href='login.php'>Login</a></span>";

            } catch (mysqli_sql_exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $message = "<span style='color:red;'>User ID already exists.</span>";
                } else {
                    $message = "<span style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</span>";
                }
            }
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

<div class="container" style="margin-top: 190px;">
    <img src="BCP.png" style="width:100px; height:auto;">
    <h2>Sign up</h2>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <select name="role" id="role" onchange="toggleLabels()" required>
            <option value="">Select</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br><br>
        <label id="id-label"></label>
        <input type="text" name="user_id" required><br>
        <p>Surname:</p>
        <input type="text" name="surname" required><br>
        <p>First name:</p>
        <input type="text" name="first_name" required><br>
        <p>Middle name:</p>
        <input type="text" name="middle_name"><br>
        <p>Password:</p>
        <input type="password" name="password" required><br>
        <p>Confirm Password:</p>
        <input type="password" name="confirm" required><br>

        <div id="section-field" style="display:none;">
            <p>Section:</p>
            <input type="text" name="section"><br><br>
        </div>

        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login here!</a></p>
</div>

<script>
function toggleLabels() {
    const role = document.getElementById("role").value;
    const idLabel = document.getElementById("id-label");
    const sectionField = document.getElementById("section-field");

    idLabel.textContent = role === "student" ? "Student ID:" : "Teacher ID:";
    sectionField.style.display = role === "student" ? "block" : "none";
}
</script>
</body>
</html>
