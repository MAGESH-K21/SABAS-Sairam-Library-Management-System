<?php
include '../connection/db.php';

error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Check if the course ID is provided
// if (!isset($_GET['couser_id'])) {
//     header('Location: course.php');
//     exit();
// }

// Get the course ID from the URL parameter
echo $courseId = $_GET['course_id'];

// Delete the course from the database
$sql = "DELETE FROM courses WHERE course_id = '$courseId'";
$conn->query($sql);

// Redirect to the course list page
header('Location: academics.php#course');
exit();
?>
