<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data
$sql = "SELECT id, user_id, surname, first_name, middle_name, role FROM users WHERE TRIM(LOWER(role)) = 'student'";
$result = $conn->query($sql);
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
        <div class="box" style="width: 100%; margin: 0 auto;">
            <h2>List of Students (Editable)</h2>
            <div class="box-content">
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Student's ID</th>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row-<?= htmlspecialchars($row['id']) ?>">
                        <td><input type="text" name="user_id" value="<?= htmlspecialchars($row['user_id']) ?>"></td>
                        <td><input type="text" name="surname" value="<?= htmlspecialchars($row['surname']) ?>"></td>
                        <td><input type="text" name="first_name" value="<?= htmlspecialchars($row['first_name']) ?>"></td>
                        <td><input type="text" name="middle_name" value="<?= htmlspecialchars($row['middle_name']) ?>"></td>
                        <td><input type="text" name="role" value="<?= htmlspecialchars($row['role']) ?>" style="width:70px;"></td>
                        <td><button class="update-btn" onclick="updateUser('<?= htmlspecialchars($row['id']) ?>')">Save</button></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No students found.</p>
            <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>