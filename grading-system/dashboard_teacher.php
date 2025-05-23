<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit;
}
?>
<h2>Welcome Teacher: <?php echo $_SESSION["name"]; ?></h2>
<a href="logout.php">Logout</a>

<a href="">signup</a>