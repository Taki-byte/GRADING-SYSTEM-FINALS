<?php
include "../db.php";

$grade = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'], $_GET['task'], $_GET['week'], $_GET['term'])) {
    $orig_id = trim($_GET['id']);
    $orig_task = trim($_GET['task']);
    $orig_week = trim($_GET['week']);
    $orig_term = trim($_GET['term']);

    $stmt = $conn->prepare(
        "SELECT * FROM grades 
        WHERE student_id=? AND task_name=? AND week=? AND term=?"
    );
    $stmt->bind_param("ssss", $orig_id, $orig_task, $orig_week, $orig_term);
    $stmt->execute();
    $grade = $stmt->get_result()->fetch_assoc();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orig_id'], $_POST['orig_task'], $_POST['orig_week'], $_POST['orig_term'])) {

    $orig_id = $_POST['orig_id'];
    $orig_task = $_POST['orig_task'];
    $orig_week = $_POST['orig_week'];
    $orig_term = $_POST['orig_term'];

    $new_id = $_POST['student_id'];
    $new_name = $_POST['student_name'];
    $new_task = $_POST['task_name'];
    $new_score = $_POST['score'];
    $new_week = $_POST['week'];
    $new_term = $_POST['term'];

    $update = $conn->prepare(
        "UPDATE grades
        SET student_id = ?, student_name = ?, task_name = ?, score = ?, week = ?, term = ?
        WHERE student_id = ? AND task_name = ? AND week = ? AND term = ?"
    );
    $update->bind_param(
        "sssissssss",
        $new_id, $new_name, $new_task, $new_score, $new_week, $new_term,
        $orig_id, $orig_task, $orig_week, $orig_term
    );
    $update->execute();

    $redirect_url = "edit_grade.php?id=" . urlencode($new_id)
        . "&task=" . urlencode($new_task)
        . "&week=" . urlencode($new_week)
        . "&term=" . urlencode($new_term);

    error_log("Redirecting to: " . $redirect_url);

    header("Location: " . $redirect_url);
    exit;
} else {
    die("Missing parameters.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Grading System - Editable Student List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .title {
            background-color: #007bff;
            width: 100%;
            font-size: 40px;
            height: 100px;
            position: fixed;
            margin-top: -100px;
            align-items: center;
            display: flex;
            padding-left: 20px;
            color: white;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 22px 25px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .main-content {
            margin-top: 100px;
            margin-left: 250px;
            padding: 20px;
        }
        .box {
            background-color: #f9f9f9;
            padding: 0;
            margin-bottom: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 95%;
            overflow: hidden;
        }
        .box-header{
            background-color: #007bff;
            width: 100%;
            color: white;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .box-content {
            background-color: #f9f9f9;
            color: #333;
            padding: 10px 15px;
            font-size: 16px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        table {
            border-collapse: collapse;
            width: 95%;
            margin: auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .update-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .update-btn:disabled { background: #aaa; cursor: not-allowed; }
    </style>
    <script>
    function updateUser(id) {
        var row = document.getElementById('row-' + id);
        var user_id = row.querySelector('input[name="user_id"]').value;
        var surname = row.querySelector('input[name="surname"]').value;
        var first_name = row.querySelector('input[name="first_name"]').value;
        var middle_name = row.querySelector('input[name="middle_name"]').value;
        var role = row.querySelector('input[name="role"]').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_user.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            alert(xhr.responseText);
        };
        xhr.send(
            'id=' + encodeURIComponent(id) +
            '&user_id=' + encodeURIComponent(user_id) +
            '&surname=' + encodeURIComponent(surname) +
            '&first_name=' + encodeURIComponent(first_name) +
            '&middle_name=' + encodeURIComponent(middle_name) +
            '&role=' + encodeURIComponent(role)
        );
    }
    </script>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>
    <div class="sidebar">
        <a href="/grading-system/admin/admin.php">Users</a>
        <a href="/grading-system/admin/announcement.php">Announcement</a>
        <a href="/grading-system/admin/view.php">Manage Grade</a>
        <br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>
    <div class="main-content">
    <h2>Edit Grade</h2>
    <?php if ($grade): ?>
        <div class="box">
            <div class="box-header">
                Edit Grade for <?= htmlspecialchars($grade['student_name']) ?>
            </div>
            <div class="box-content">
                <form method="post" action="">
                    <input type="hidden" name="orig_id" value="<?= htmlspecialchars($orig_id) ?>">
                    <input type="hidden" name="orig_task" value="<?= htmlspecialchars($orig_task) ?>">
                    <input type="hidden" name="orig_week" value="<?= htmlspecialchars($orig_week) ?>">
                    <input type="hidden" name="orig_term" value="<?= htmlspecialchars($orig_term) ?>">

                    <label>Student ID:<br>
                        <input type="text" name="student_id" value="<?= htmlspecialchars($grade['student_id']) ?>" required>
                    </label><br><br>

                    <label>Student Name:<br>
                        <input type="text" name="student_name" value="<?= htmlspecialchars($grade['student_name']) ?>" required>
                    </label><br><br>

                    <label>Task Name:<br>
                        <input type="text" name="task_name" value="<?= htmlspecialchars($grade['task_name']) ?>" required>
                    </label><br><br>

                    <label>Score:<br>
                        <input type="number" name="score" value="<?= htmlspecialchars($grade['score']) ?>" required>
                    </label><br><br>

                    <label>Week:<br>
                        <input type="text" name="week" value="<?= htmlspecialchars($grade['week']) ?>" required>
                    </label><br><br>

                    <label>Term:<br>
                        <input type="text" name="term" value="<?= htmlspecialchars($grade['term']) ?>" required>
                    </label><br><br>

                    <button type="submit">Update</button>
                </form>
                <br>
                <a href="/grading-system/admin/view.php">back</a>
            </div>
        </div>
    <?php else: ?>
        <p style="color:red;">Grade record not found. Please go back and try again.</p>
    <?php endif; ?>
</body>
</html>