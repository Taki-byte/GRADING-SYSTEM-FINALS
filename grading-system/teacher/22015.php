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
            width: 30px;
            margin: 0;
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
        .section{
        color: #007bff;
        } 
        .box-content {
    background-color: white;
    padding: 20px;
    font-size:15px;
    text-align: center;
        }
        .box {
    width: 250px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    margin-left: 20px;
    margin-right : 20px;
        }
    </style>
</head>
<body>
    <h1 class="title"><img src="BCP.png" style="width:100px; height:auto;"> Student Grading System</h1>
    <div class="sidebar">
        <a href="/grading-system/teacher/dashboard.php">Dashboard</a>
        <a href="/grading-system/teacher/input.php">Manage Grades</a>
        <a href="/grading-system/teacher/section.php">Section</a>
        <a href="/grading-system/teacher/announcement.php">Announcement</a>
        <br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br>
        <a href="/grading-system/login.php?logout=1">Logout</a>
    </div>
<div class="main-content">
<h1>22015</h1>

<table>
    <tr>
        <th>

    </th>
    <th>

    </th>
    </tr>
</table>

</div>
</body>
</html>