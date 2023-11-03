<?php
include 'connection/db.php';

$departmentFilter = $_GET['department'] ?? null;
$yearFilter = $_GET['year'] ?? null;
$memberTypeFilter = $_GET['memberType'] ?? null;
$timeFilter = $_GET['timeFilter'] ?? null;

// Initialize additional filter variables
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;
$selectedMonth = $_GET['selectedMonth'] ?? null;
$selectedYear = $_GET['selectedYear'] ?? null;

// Default SQL query without filters
$sql = "SELECT b.returned_date, COUNT(*) AS count
        FROM borrowings AS b
        LEFT JOIN members AS m ON b.member_id = m.member_id
        WHERE b.returned_date IS NOT NULL";

// Modify the query if filters are applied
if ($departmentFilter || $yearFilter || $memberTypeFilter || $timeFilter) {
    $sql .= " AND 1=1"; // Start subquery

    if ($departmentFilter) {
        $sql .= " AND m.department = '$departmentFilter'";
    }
    if ($yearFilter) {
        $sql .= " AND m.year = '$yearFilter'";
    }
    if ($memberTypeFilter) {
        $sql .= " AND m.member_type = '$memberTypeFilter'";
    }

    if ($timeFilter === 'weekly') {
        // Add filter for start and end date (weekly)
        if ($startDate && $endDate) {
            $sql .= " AND b.returned_date BETWEEN '$startDate' AND '$endDate'";
        }
    } elseif ($timeFilter === 'monthly') {
        // Add filter for selected month (monthly)
        if ($selectedMonth) {
            $startDate = $selectedMonth . '-01';
            $endDate = date('Y-m-t', strtotime($selectedMonth));
            $sql .= " AND b.returned_date BETWEEN '$startDate' AND '$endDate'";
        }
    } elseif ($timeFilter === 'yearly') {
        // Add filter for selected year (yearly)
        if ($selectedYear) {
            $startDate = $selectedYear . '-01-01';
            $endDate = $selectedYear . '-12-31';
            $sql .= " AND b.returned_date BETWEEN '$startDate' AND '$endDate'";
        }
    }
}

$sql .= " GROUP BY b.returned_date";

$result = $conn->query($sql);

$response = array(
    'labels' => array(),
    'values' => array()
);

while ($row = $result->fetch_assoc()) {
    $response['labels'][] = $row['returned_date'];
    $response['values'][] = $row['count'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
