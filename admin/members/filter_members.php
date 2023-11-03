<?php
// Establish a database connection
include '../connection/db.php';
error_reporting(0);
// Check if the user is authenticated
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true)
 {
    header('Location: ./admin/login.php');
    exit();
}
// Get the selected department from the AJAX request
$selectedDepartment = $_GET['department'];

// Modify your SQL query to include the department filter
$sql = "SELECT member_id, member_type, name, email, phone, class, year, college_id, department, fine_amount 
        FROM members 
        WHERE department = '$selectedDepartment' 
        ORDER BY member_id DESC";

// Execute the query and generate the HTML list of members
// ...

// Output the HTML content
echo $htmlContent;
?>