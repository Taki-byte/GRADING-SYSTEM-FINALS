<?php
include "db.php";

$notification = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $surname = $_POST["surname"];
    $name = $_POST["name"];
    $middle_name = $_POST["middle_name"];

    if ($role == "student") {
        $student_id = $_POST["student_id"];
        $section = $_POST["section"];

        $check = $conn->prepare("SELECT id FROM users WHERE student_id = ?");
        $check->bind_param("s", $student_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $notification = "Student ID already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (student_id, password, role, surname, name, middle_name, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $student_id, $password, $role, $surname, $name, $middle_name, $section);
            if ($stmt->execute()) {
                $notification = "Sign-up successful! <a href='login.php'>Login here</a>";
            } else {
                $notification = "Error: " . $stmt->error;
            }
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO users (password, role, surname, name, middle_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $password, $role, $surname, $name, $middle_name);
        if ($stmt->execute()) {
            $notification = "Teacher sign-up successful! <a href='login.php'>Login here</a>";
        } else {
            $notification = "Error: " . $stmt->error;
        }
    }
}
?>

<form method="POST">
    <h1>Sign-up</h1>

    <?php if ($notification != ""): ?>
        <div style="padding: 10px; background-color: #f4f4f4; border: 1px solid #ccc; margin-bottom: 10px;">
            <?php echo $notification; ?>
        </div>
    <?php endif; ?>

    <select name="role" id="role" required>
        <option value="">Select Role</option>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
    </select><br><br>
    
    <input type="text" name="surname" placeholder="Surname" required><br><br>
    <input type="text" name="name" placeholder="First Name" required><br><br>
    <input type="text" name="middle_name" placeholder="Middle Name"><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    
    <div id="student-fields" style="display:none;">
        <input type="text" name="student_id" placeholder="Student ID" required><br><br>
        <input type="text" name="section" placeholder="Section"><br><br>
    </div>
    
    
    <br><br>
    <button type="submit">Sign Up</button>
    <p>Already have an account? <a href="login.php">Login here!</a></p>
</form>

<script>
    document.getElementById('role').addEventListener('change', function() {
        var role = this.value;
        var studentFields = document.getElementById('student-fields');
        
        if (role == 'student') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    });
</script>
