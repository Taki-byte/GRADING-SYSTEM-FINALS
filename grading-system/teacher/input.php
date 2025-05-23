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
$result = false;

// Handle AJAX update for inline editing (excel-like)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_update'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $task_name = $_POST['task_name'];
    $score = $_POST['score'];
    $week = $_POST['week'];
    $term = $_POST['term'];
    $subject = $_POST['subject'];

    $orig_id = $_POST['orig_id'];
    $orig_task = $_POST['orig_task'];
    $orig_week = $_POST['orig_week'];
    $orig_term = $_POST['orig_term'];

    $update = $conn->prepare(
        "UPDATE grades
        SET student_id = ?, student_name = ?, task_name = ?, score = ?, week = ?, term = ?, subject=?
        WHERE student_id = ? AND task_name = ? AND week = ? AND term = ?"
    );
    // FIX: Corrected type string to match 11 parameters (7 's', 1 'i', 3 's')
    $update->bind_param(
        "sssisssssss",
        $student_id, $student_name, $task_name, $score, $week, $term, $subject,
        $orig_id, $orig_task, $orig_week, $orig_term
    );
    if ($update->execute()) {
        echo "success";
    } else {
        echo "error: " . $update->error;
    }
    exit;
}

// Add Grade
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['ajax_update'])) {
    $student_id   = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $task_name    = $_POST["task_name"];
    $score        = $_POST["score"];
    $week         = $_POST["week"];
    $term         = $_POST["term"];
    $subject      = $_POST["subject"];
    $date         = date("Y-m-d");

    $sql = "INSERT INTO grades (student_id, student_name, task_name, score, week, term, subject, date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $student_id, $student_name, $task_name, $score, $week, $term, $subject, $date);

    if ($stmt->execute()) {
        $success_message = "Grade submitted successfully!";
    } else {
        $success_message = "Error submitting grade: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_GET['success'])) {
    $success_message = $_GET['success'];
}

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $search = $_GET['id'];
}

if ($search !== '') {
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT subject, student_id, student_name, task_name, score, week, term, date 
                            FROM grades 
                            WHERE student_id = ? OR student_name LIKE ? 
                            ORDER BY date DESC 
                            LIMIT ?, ?");
    $stmt->bind_param("ssii", $search, $like, $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_stmt = $conn->prepare("SELECT COUNT(*) FROM grades WHERE student_id = ? OR student_name LIKE ?");
    $total_stmt->bind_param("ss", $search, $like);
    $total_stmt->execute();
    $total_rows = $total_stmt->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $records_per_page);
    $total_stmt->close();

    $stmt_total = $conn->prepare("SELECT task_name, score FROM grades WHERE student_id = ? OR student_name LIKE ?");
    $stmt_total->bind_param("ss", $search, $like);
    $stmt_total->execute();
    $grades_result = $stmt_total->get_result();

    $total_activity = $count_activity = $total_test = $count_test = 0;
    while ($grade_avg = $grades_result->fetch_assoc()) {
        $task = strtolower(trim($grade_avg['task_name']));
        $score = (float)$grade_avg['score'];
        if (strpos($task, 'activity') !== false) {
            $total_activity += $score;
            $count_activity++;
        } elseif (strpos($task, 'test') !== false) {
            $total_test += $score;
            $count_test++;
        }
    }
    $stmt_total->close();

    $avg_activity = $count_activity ? $total_activity / $count_activity : 0;
    $avg_test = $count_test ? $total_test / $count_test : 0;
    $final_grade = round(($avg_activity * 0.3) + ($avg_test * 0.3), 2);
} else {
    $stmt = $conn->prepare("SELECT subject, student_id, student_name, task_name, score, week, term, date 
                            FROM grades 
                            ORDER BY date DESC 
                            LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_stmt = $conn->prepare("SELECT COUNT(*) FROM grades");
    $total_stmt->execute();
    $total_rows = $total_stmt->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $records_per_page);
    $total_stmt->close();
    $stmt->close();
}
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
        .save-btn {
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 4px 10px;
            text-decoration: none;
            margin-right: 5px;
            cursor: pointer;
        }
        .edit-cell input,
        .edit-cell select {
            width: 90px;
            padding: 2px 5px;
        }
        .edit-cell select {
            width: 100px;
        }
        .success-msg { color: #28a745; font-weight: bold; }
        .error-msg { color: #dc3545; font-weight: bold; }
    </style>
    <script>
    function saveRow(rowId) {
        var row = document.getElementById("grade-row-"+rowId);
        if (!row) return;
        var tds = row.querySelectorAll('.edit-cell');
        var data = {};
        data['subject'] = tds[0].querySelector('input').value;
        data['student_id'] = tds[1].querySelector('input').value;
        data['student_name'] = tds[2].querySelector('input').value;
        data['task_name'] = tds[3].querySelector('input').value;
        data['score'] = tds[4].querySelector('input').value;
        data['week'] = tds[5].querySelector('input').value;
        data['term'] = tds[6].querySelector('select').value;

        // Originals
        data['orig_id'] = row.getAttribute('data-orig-id');
        data['orig_task'] = row.getAttribute('data-orig-task');
        data['orig_week'] = row.getAttribute('data-orig-week');
        data['orig_term'] = row.getAttribute('data-orig-term');
        data['ajax_update'] = 1;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.responseText.trim() === "success") {
                alert('Grade updated successfully!');
                // update the data-orig-* to new values
                row.setAttribute('data-orig-id', data['student_id']);
                row.setAttribute('data-orig-task', data['task_name']);
                row.setAttribute('data-orig-week', data['week']);
                row.setAttribute('data-orig-term', data['term']);
            } else {
                alert('Failed to update grade: ' + xhr.responseText);
            }
        };
        var params = [];
        for (var key in data) {
            params.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
        xhr.send(params.join('&'));
    }
    </script>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>
    <div class="sidebar">
        <a href="/grading-system/teacher/dashboard.php">Dashboard</a>
        <a href="/grading-system/teacher/input.php">Manage Grades</a>
        <a href="/grading-system/teacher/section.php">Section</a>
        <a href="/grading-system/teacher/announcement.php">Announcement</a>
        <br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>
    <div class="main-content">
        <div class="box">
            <form method="GET" action="">
                <input type="text" name="id" placeholder="Search by ID or Name" value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
            <h2>Grades</h2>
            <form method="POST">
                <table>
                    <tr>
                        <th><label for="subject">Subject:</label><br>
                            <input id="subject" name="subject" required></th>
                        <th><label for="student_id">Student ID:</label><br>
                            <input id="student_id" name="student_id" required></th>
                        <th><label for="student_name">Name:</label><br>
                            <input id="student_name" name="student_name" required></th>
                        <th><label for="task_name">Task name:</label><br>
                            <input id="task_name" name="task_name" required></th>
                        <th><label for="score">Score:</label><br>
                            <input type="number" id="score" name="score" required></th>
                        <th><label for="week">Week:</label><br>
                            <select id="week" name="week">
                                <?php for ($i = 1; $i <= 19; $i++) echo "<option value='Week $i'>Week $i</option>"; ?>
                            </select></th>
                        <th><label for="term">Term:</label><br>
                            <select id="term" name="term">
                                <option>Prelim</option>
                                <option>Midterm</option>
                                <option>Finals</option>
                            </select></th>
                        <th>
                            <button type="submit" name="add_grade">Submit</button>
                        </th>
                    </tr>
            </form>
            <?php if (!empty($success_message)) : ?>
                <p class="success-msg"><?= htmlspecialchars($success_message) ?></p>
            <?php endif; ?>
        </div>
        <table>
            <tr>
                <th>Subject</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Task</th>
                <th>Score</th>
                <th>Week</th>
                <th>Term</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $rownum=0; while ($row = $result->fetch_assoc()) : $rownum++; ?>
                    <tr id="grade-row-<?= $rownum ?>"
                        data-orig-id="<?= htmlspecialchars($row["student_id"]) ?>"
                        data-orig-task="<?= htmlspecialchars($row["task_name"]) ?>"
                        data-orig-week="<?= htmlspecialchars($row["week"]) ?>"
                        data-orig-term="<?= htmlspecialchars($row["term"]) ?>">
                        <td class="edit-cell">
                            <input type="text" value="<?= htmlspecialchars($row["subject"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <input type="text" value="<?= htmlspecialchars($row["student_id"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <input type="text" value="<?= htmlspecialchars($row["student_name"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <input type="text" value="<?= htmlspecialchars($row["task_name"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <input type="number" value="<?= htmlspecialchars($row["score"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <input type="text" value="<?= htmlspecialchars($row["week"]) ?>">
                        </td>
                        <td class="edit-cell">
                            <select>
                                <option <?= $row['term']=='Prelim'?'selected':'' ?>>Prelim</option>
                                <option <?= $row['term']=='Midterm'?'selected':'' ?>>Midterm</option>
                                <option <?= $row['term']=='Finals'?'selected':'' ?>>Finals</option>
                            </select>
                        </td>
                        <td><?= htmlspecialchars($row["date"]) ?></td>
                        <td>
                            <button type="button" class="save-btn" onclick="saveRow('<?= $rownum ?>')">Save</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No records found.</td></tr>
            <?php endif; ?>
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