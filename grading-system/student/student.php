<?php
session_start();
if (!isset($_SESSION["student_id"])) {
    header("Location: ../login.php");
    exit;
}
include "../db.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

$records_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$search = '';
$result = null;

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $search = $_GET['id'];
    $stmt = $conn->prepare("SELECT student_id, student_name, task_name, score, week, term, date FROM grades WHERE student_id = ? OR student_name LIKE ? ORDER BY date ASC LIMIT ?, ?");
    $like = "%" . $search . "%";
    $stmt->bind_param("ssii", $search, $like, $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT student_id, student_name, task_name, score, week, term, date FROM grades ORDER BY date ASC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();
}

$total_stmt = $conn->prepare("SELECT COUNT(*) FROM grades");
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Grading System</title>
    <style>
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
        }

        .sidebar {
            width: 200px;
            background-color: #333;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
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

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
        }

        .divider {
            text-align: center;
            margin-top: 20px;
        }

        .divider a {
            padding: 10px 20px;
            text-decoration: none;
            border: 1px solid #333;
            margin: 0 5px;
        }

        .divider a:hover {
            background-color: #575757;
            color: white;
        }

        .box{

        }
    </style>
</head>
<body>
<h1 class="title">Student Grading System</h1>
    <div class="sidebar">
        <a href="dashboardstudent.php?id=<?= urlencode($student_id) ?>">Dashboard</a>
        <a href="view.php?id=<?= urlencode($student_id) ?>">View Grade</a>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>



    <div class="main-content">

        <h2>Grades</h2>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Task</th>
                <th>Score</th>
                <th>Week</th>
                <th>Term</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row["student_id"]) ?></td>
                    <td><?= htmlspecialchars($row["student_name"]) ?></td>
                    <td><?= htmlspecialchars($row["task_name"]) ?></td>
                    <td><?= htmlspecialchars($row["score"]) ?></td>
                    <td><?= htmlspecialchars($row["week"]) ?></td>
                    <td><?= htmlspecialchars($row["term"]) ?></td>
                    <td><?= htmlspecialchars($row["date"]) ?></td>
                </tr>
            <?php } ?>
        </table>

        <div class="divider">
            <?php if ($page > 1) { ?>
                <a href="?id=<?= htmlspecialchars($search) ?>&page=<?= $page - 1 ?>">Previous</a>
            <?php } ?>
            <?php if ($page < $total_pages) { ?>
                <a href="?id=<?= htmlspecialchars($search) ?>&page=<?= $page + 1 ?>">Next</a>
            <?php } ?>
        </div>
    </div>
</body>
</html>
