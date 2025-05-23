<?php
session_start();
session_unset(); 
session_destroy(); 
header("Location: grading-system/login.php"); 
exit();
?>