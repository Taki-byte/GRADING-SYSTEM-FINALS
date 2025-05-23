<?php
include "../db.php";

$records_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$search = '';
$avg_activity = 0;
$avg_test = 0;
$final_grade = 0;
$total_pages = 1;
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $task_name = $_POST["task_name"];
    $score = $_POST["score"];
    $week = $_POST["week"];
    $term = $_POST["term"];
    $subject = $_POST["subject"];
    $date = date("Y-m-d");

    $sql = "INSERT INTO grades (student_id, student_name, task_name, score, week, term, subject, date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissss", $student_id, $student_name, $task_name, $score, $week, $term, $subject, $date);
    
    if ($stmt->execute()) {
        $success_message = "Grade submitted successfully!";
        $search = $student_id;
    }
}

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $search = $_GET['id'];
}

if ($search !== '') {
    $like = "%" . $search . "%";

    $stmt = $conn->prepare("SELECT subject, student_id, student_name, task_name, score, week, term, date 
                            FROM grades 
                            WHERE student_id = ? OR student_name LIKE ? 
                            ORDER BY date ASC 
                            LIMIT ?, ?");
    $stmt->bind_param("ssii", $search, $like, $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_stmt = $conn->prepare("SELECT COUNT(*) FROM grades WHERE student_id = ? OR student_name LIKE ?");
    $total_stmt->bind_param("ss", $search, $like);
    $total_stmt->execute();
    $total_rows = $total_stmt->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $records_per_page);

    $stmt_total = $conn->prepare("SELECT task_name, score FROM grades WHERE student_id = ? OR student_name LIKE ?");
    $stmt_total->bind_param("ss", $search, $like);
    $stmt_total->execute();
    $grades_result = $stmt_total->get_result();

    $total_activity = $count_activity = $total_test = $count_test = 0;
    while ($grade = $grades_result->fetch_assoc()) {
        $task = strtolower(trim($grade['task_name']));
        $score = (float)$grade['score'];
        if (strpos($task, 'activity') !== false) {
            $total_activity += $score;
            $count_activity++;
        } elseif (strpos($task, 'test') !== false) {
            $total_test += $score;
            $count_test++;
        }
    }

    $avg_activity = $count_activity ? $total_activity / $count_activity : 0;
    $avg_test = $count_test ? $total_test / $count_test : 0;
    $final_grade = round(($avg_activity * 0.3) + ($avg_test * 0.3), 2);
} else {
    $stmt = $conn->prepare("SELECT subject, student_id, student_name, task_name, score, week, term, date 
                            FROM grades 
                            ORDER BY date ASC 
                            LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_stmt = $conn->prepare("SELECT COUNT(*) FROM grades");
    $total_stmt->execute();
    $total_rows = $total_stmt->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $records_per_page);
}
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
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 95%;
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
    </style>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>

    <div class="sidebar">
        <a href="/grading-system/teacher/dashboard.php">Dashboard</a>
        <a href="/grading-system/teacher/input.php">Input Grade</a>
        <a href="/grading-system/teacher/view.php">View Grade</a>
        <a href="/grading-system/teacher/section.php">Section</a>
        <a href="/grading-system/teacher/announcement.php">Announcement</a>
        <br><br><br><br><br><br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>

    <div class="main-content">
        <div class="box">
        

        <form method="GET" action="">
            <input type="text" name="id" placeholder="Search by ID or Name" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <h2>Grades</h2>
        <table>
            <form method="POST">
            <th><label>Subject:</label><br>
            <input name="subject" required></th>
            <th><label>Student ID:</label><br>
                <input name="student_id" required></th>
                <th><label>Name:</label><br>
                <input name="student_name" required></th>
                <th><label>Task name:</label><br>
                <input name="task_name" required></th>
                <th><label>Score:</label><br>
                <input type="number" name="score" required></th>
                <th><label>Week:</label>
                <select name="week">
                    <?php for ($i = 1; $i <= 19; $i++) echo "<option value='Week $i'>Week $i</option>"; ?>
                </select></th>
                <th><label>Term:</label>
                <select name="term">
                    <option>Prelim</option>
                    <option>Midterm</option>
                    <option>Finals</option>
                </select></th>
                <th><button type="submit">Submit</button></th>
            </form>
            <?php if (!empty($success_message)) : ?>
                <p><?= htmlspecialchars($success_message) ?></p>
            <?php endif; ?>
        </div>
            </table>
        <table>
                <th>Subject</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Task</th>
                <th>Score</th>
                <th>Week</th>
                <th>Term</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row["subject"]) ?></td>
                    <td><?= htmlspecialchars($row["student_id"]) ?></td>
                    <td><?= htmlspecialchars($row["student_name"]) ?></td>
                    <td><?= htmlspecialchars($row["task_name"]) ?></td>
                    <td><?= htmlspecialchars($row["score"]) ?></td>
                    <td><?= htmlspecialchars($row["week"]) ?></td>
                    <td><?= htmlspecialchars($row["term"]) ?></td>
                    <td><?= htmlspecialchars($row["date"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br><br>
        <?php if ($search !== ''): ?>
            <div class="summary-box">
                <h3>Grade Summary</h3>
                <p><strong>Activity Average:</strong> <?= number_format($avg_activity, 2) ?></p>
                <p><strong>Test Average:</strong> <?= number_format($avg_test, 2) ?></p>
                <p><strong>Total Grade (30% Activity + 30% Test):</strong> <?= number_format($final_grade, 2) ?></p>
            </div>
        <?php endif; ?>

        <div class="divider">
            <?php if ($page > 1): ?>
                <a href="?id=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
            <?php endif; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?id=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
