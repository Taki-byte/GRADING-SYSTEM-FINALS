<?php
$filename = $_SERVER['DOCUMENT_ROOT'] . "/grading-system/student/announcements.txt";

$announcements = [];
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (array_reverse($lines) as $line) {
        $data = json_decode($line, true);
        if ($data) $announcements[] = $data;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Grading System - Announcements</title>
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
            width: 80%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            margin: 50px auto;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
        }
        .box-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .box-content {
            background-color: white;
            padding: 20px;
        }
        .announcement {
            margin: 30px auto 10px auto;
            width: 95%;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            padding: 20px 30px;
        }
        .announcement h3 {
            color: #007bff;
            margin-top: 0;
        }
        .announcement .date {
            color: #888;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .no-announcement {
            text-align: center;
            color: #888;
            font-size: 18px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>
    <div class="sidebar">
        <a href="dashboardstudent.php">Dashboard</a>
        <a href="/grading-system/student/view.php">Grades</a>
        <a href="subject.php">Subjects</a>
        <a href="/grading-system/student/announcements-students.php">Announcement</a>
        <br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>
    <div class="main-content">
        <div class="box">
            <div class="box-header">Announcements</div>
            <div class="box-content">
                <?php if (count($announcements) === 0): ?>
                    <div class="no-announcement">No announcements posted yet.</div>
                <?php else: ?>
                    <?php foreach ($announcements as $ann): ?>
                        <div class="announcement">
                            <div class="date"><?= htmlspecialchars($ann['date']) ?></div>
                            <h3><?= htmlspecialchars($ann['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($ann['message'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>