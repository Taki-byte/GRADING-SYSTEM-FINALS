<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body{
    background-color: #007bff;
    }

    .center{
        text-align: center;
        margin-top: 200px;
        margin-bottom: -100px;
    }

    .option{
        background: white;
        border: solid;
        padding: 20px;
        border-radius: 10px;
        font-size: 30px;
    }

    .back{
        margin-top: 570px;
        margin-left: 30px;
        font-size: 20px;
    }    

    .space {
        margin-top: 40px;
        font-size: 20px;
    }



    </style>
    <title>Sign Up</title>
</head>
<body>

<h1 class="center"> sign up as:</h1>&nbsp;
<div class="center">
<a class="option" href="students2.php">student</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class= "option" href="teacher2.php">teacher</a>
<p class="space"> already have an account? <a href="login2.php">login here</a></p>
</div>


</body>
</html>
