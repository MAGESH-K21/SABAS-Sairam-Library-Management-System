<?php
include '../connection/db.php';

error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    // Delete department from the database using prepared statement
    $stmt = $conn->prepare("DELETE FROM departments WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();

    // Redirect to the department list page after deletion
    header('Location: academics.php#department');
    exit();
}
?>
