<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG IN</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <div class="teachers">
        <label for="prof_name">ID #</label>
        <input type="text" id="prof_name">
        <label for="prof_name">Enter Your Name</label>
        <input type="text" id="prof_name">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
          <button type="submit">Submit</button>
          <p>already have an account? <a href="login2.php">login here!</a>
        </div> 
    </div>
    
</body>
</html>
