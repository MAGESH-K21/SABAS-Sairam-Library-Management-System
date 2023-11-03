<?php

$host = 'localhost';
$db = 'library_management_system';
$user = 'root';
$pass = '';

// Create a database connection
$conn = new mysqli($host, $user, $pass, $db);

// Define your base URL

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
