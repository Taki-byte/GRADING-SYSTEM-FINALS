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
        .box {
    width: 20%;
    border-radius: 15px;
    overflow: hidden;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
        }

       .box-header {
    background-color: #007bff;
    color: white;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
        }

        .box-content {
    background-color: white;
    padding: 20px;
    margin: 10px auto;
        }

    .users{
        color: #007bff;
    }
    .margin {
        margin-right: 40px;
    }

    </style>
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
</div>
    </div>
<div class="main-content">
<h1>Users</h1>

<table>
    <tr>

<div class="box">
    <a class ="users"href="/grading-system/admin/teacher-list.php"><div class="box-header">
        <h1 class="margin">Teacher</h1>
    </div>
    <div class="box-content">
        <p>list</p>
    </div>
</div></a>
    <div class="box">
    <a class ="users"href="/grading-system/admin/students-list.php"><div class="box-header">
        <h1 class="margin">Student</h1>
    </div>
    <div class="box-content">
        <p>list</p>
    </tr>
</table>
</div>

</div>
</body>
</html>