<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: /grading-system/login.php");
    exit;
}

$student_id   = $_SESSION['user_id'] ?? '';
$first_name   = $_SESSION['first_name'] ?? '';
$surname      = $_SESSION['surname'] ?? '';
$middle_name  = $_SESSION['middle_name'] ?? '';
$section      = $_SESSION['section'] ?? '';
$role         = $_SESSION['role'] ?? '';

$conn = new mysqli("localhost", "root", "", "grading_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT task_name, score, week, term, date FROM grades WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Grading System</title>
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
        .box, .summary-box {
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
        .divider {
            text-align: center;
            margin: 20px;
        }
        .divider a {
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #333;
            margin: 0 5px;
        }
        .divider a:hover {
            background-color: #007bff;
            color: white;
        }
        .color{
            color: #007bff;
        }
    </style>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>
    <div class="sidebar">
        <a href="dashboardstudent.php">Dashboard</a>
        <a href="/grading-system/student/view.php">Grades</a>
        <a href="subject.php">Subjects</a>
<br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>

<div class="main-content">
    <h2>Subjects</h2>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">Financial Management</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">Information Management</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">IT ELECTIVE 2 (Advance Java or OOP)</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">Networking 2</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">SYSTEM INTEGRATION AND ARCHITECTURE 1</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">Team Sports</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">The Life and Works of Jose Rizal</div>
        </a>
    </div>

    <div class="box">
        <a class="color" href="#">
            <div class="box-header">Web Development (Advance Web / Platform)</div>
        </a>
    </div>
</div>
</a>
</th>
</tr>
</table>
</div>

</div>
</body>
</html>
