
<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to BCP</title>
    <style>
        body {
            background-color: #add8e6; /* light blue */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #007acc;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #3399ff;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        main {
            padding: 40px;
            text-align: center;
        }

        footer {
            background-color: #007acc;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        h1 {
            font-size: 2.5em;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome to BCP</h1>
        <p>Where future leaders begin their journey</p>
    </header>

    <nav>
        <a href="register_student.php">Register</a>
        <a href="login_student.php">Login</a>
        <a href="#">About Us</a>
        <a href="#">Contact</a>
    </nav>

    <footer>
        &copy; <?php echo date("Y"); ?> BCP. All rights reserved.
    </footer>

</body>
</html>
