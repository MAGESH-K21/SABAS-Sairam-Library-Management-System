<?php
include '../admin/connection/db.php';



$resultsPerPage = 10; // Number of results per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number from the URL query parameter

$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d');
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

$queryTotal = "SELECT COUNT(*) as total FROM borrowings b WHERE b.borrowings_time BETWEEN ? AND ?";

$stmtTotal = $conn->prepare($queryTotal);
$stmtTotal->bind_param("ss", $fromDate, $toDate);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$rowTotal = $resultTotal->fetch_assoc();
$totalCount = $rowTotal['total'];

$totalPages = ceil($totalCount / $resultsPerPage);

$offset = ($current_page - 1) * $resultsPerPage;

$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';


$reportType = isset($_GET['report_type']) ? $_GET['report_type'] : '';

$query = "SELECT b.*, m.name AS member_name, m.department, m.member_type, m.college_id AS student_college_id,
                 bk.title AS book_title, bk.category AS book_category,
                 bk.subject AS book_subject
          FROM borrowings b
          JOIN members m ON b.member_id = m.member_id
          JOIN books bk ON b.book_id = bk.book_id
          WHERE b.borrowings_time BETWEEN ? AND ? ";

if (!empty($searchQuery)) {
    $query .= " AND (m.name LIKE ? OR bk.title LIKE ?)";
}
if (!empty($_GET['department'])) {
    $query .= " AND m.department = ?";
}
if (!empty($_GET['member_type'])) {
    $query .= " AND m.member_type = ?";
}
if (!empty($reportType)) {
    if ($reportType === 'overdue') {
        $query .= " AND b.returned_date IS NULL AND b.due_date < CURDATE()";
    } elseif ($reportType === 'return') {
        $query .= " AND b.returned_date IS NOT NULL";
    } elseif ($reportType === 'borrowed') {
        $query .= " AND b.returned_date IS NULL AND b.borrowed_date BETWEEN ? AND ?";
    }
}

$query .= " LIMIT ?, ?";

$stmt = $conn->prepare($query);
$bindParams = array($fromDate, $toDate);
$bindTypes = "ss";
$searchParam = "%" . $searchQuery . "%";

if (!empty($searchQuery)) {
    $bindParams[] = &$searchParam;
    $bindParams[] = &$searchParam;
    $bindTypes .= "ss";
}

if (!empty($_GET['department'])) {
    $bindParams[] = &$_GET['department'];
    $bindTypes .= "s";
}

if (!empty($_GET['member_type'])) {
    $bindParams[] = &$_GET['member_type'];
    $bindTypes .= "s";
}
if (!empty($reportType)) {
    // Add appropriate binding for report_type
    if ($reportType === 'overdue') {
       
    } elseif ($reportType === 'return') {
        
    }
        elseif ($reportType === 'borrowed') {
            // Add binding for borrowed report type
            $bindParams[] = &$fromDate;
            $bindParams[] = &$toDate; // Add toDate here
            $bindTypes .= "ss"; // Update the $bindTypes string
        }
}

$bindParams[] = &$offset;
$bindParams[] = &$resultsPerPage;
$bindTypes .= "ii";

$stmt->bind_param($bindTypes, ...$bindParams);

// Execute the prepared statement
$stmt->execute();
$result = $stmt->get_result();


// Initialize CSV content
$csv_content = "Borrowing ID,Member,Department,Member Type,College ID,Book,Category,Subject,Borrowed Date,Due Date,Returned Date,Fine Amount,Status\n";

while ($row = $result->fetch_assoc()) {
    // Add data to CSV content
    $csv_content .= "{$row['borrowing_id']},{$row['member_name']},{$row['department']},{$row['member_type']},{$row['student_college_id']},{$row['book_title']},{$row['book_category']},{$row['book_subject']},{$row['borrowed_date']},{$row['due_date']},{$row['returned_date']},{$row['fine_amount']},{$row['status']}\n";
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="library_report.csv"');
echo $csv_content;
exit;
?>

