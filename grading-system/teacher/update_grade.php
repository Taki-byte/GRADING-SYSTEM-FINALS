<?php
include "../db.php";

if (isset($_POST['id'], $_POST['column'], $_POST['value'])) {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $value = $_POST['value'];

    $allowed = ['student_id', 'student_name', 'task_name', 'score', 'week', 'term', 'subject'];
    if (!in_array($column, $allowed)) {
        echo "Invalid column!";
        exit;
    }

    $stmt = $conn->prepare("UPDATE grades SET $column = ? WHERE id = ?");
    $stmt->bind_param("si", $value, $id);

    if ($stmt->execute()) {
        echo "Saved!";
    } else {
        echo "Failed to save.";
    }
}
?>
