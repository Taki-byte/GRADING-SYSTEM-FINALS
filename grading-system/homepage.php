
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 100px;
        }
        .button {
            display: inline-block;
            margin: 20px;
            padding: 15px 30px;
            font-size: 18px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Our System</h1>
        <p>Please choose where to log in:</p>
        <a href="admin_login.php" class="button">Admin Login</a>
        <a href="login_student.php" class="button">Student Login</a>
        <a href="login_teacher.php" class="button">Teacher Login</a>
    </div>
</body>
</html>
