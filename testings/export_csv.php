<?php
session_start();
include '../admin/connection/db.php';
error_reporting(0);

// Your existing code for database queries and data retrieval
// Fetch data from the database and store it in a variable
// ...

// Initialize CSV content
$csv_content = "Borrowing ID,Member,Department,Member Type,College ID,Book,Category,Subject,Borrowed Date,Due Date,Returned Date,Fine Amount,Status\n";

while ($row = $result->fetch_assoc()) {
    // Prepare data for CSV, make sure to handle special characters
    $borrowing_id = $row['borrowing_id'];
    $member_name = $row['member_name'];
    $department = $row['department'];
    $member_type = $row['member_type'];
    $student_college_id = $row['student_college_id'];
    $book_title = $row['book_title'];
    $book_category = $row['book_category'];
    $book_subject = $row['book_subject'];
    $borrowed_date = $row['borrowed_date'];
    $due_date = $row['due_date'];
    $returned_date = $row['returned_date'];
    $fine_amount = $row['fine_amount'];
    $status = $row['status'];

    // Add data to CSV content
    $csv_content .= "$borrowing_id,$member_name,$department,$member_type,$student_college_id,$book_title,$book_category,$book_subject,$borrowed_date,$due_date,$returned_date,$fine_amount,$status\n";
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="library_report.csv"');

// Output the CSV content
echo $csv_content;
?>
