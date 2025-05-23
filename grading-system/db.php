<?php
$conn = new mysqli("localhost", "root", "", "grading_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
