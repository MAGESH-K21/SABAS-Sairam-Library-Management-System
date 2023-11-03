<?php
// Include your database connection here
include 'connection/db.php';

// Get filter values from GET parameters
$department = $_GET['department'];
$year = $_GET['year'];
$memberType = $_GET['memberType'];

$sql = "SELECT 
    SUM(CASE WHEN DATE(b.borrowed_date) = CURDATE() THEN 1 ELSE 0 END) AS borrowed_count,
    SUM(CASE WHEN DATE(b.returned_date) = CURDATE() THEN 1 ELSE 0 END) AS returned_count
FROM borrowings b
JOIN members m ON b.member_id = m.member_id";

$conditions = array();

if (!empty($year)) {
    $conditions[] = " m.year = '$year'";
}

if (!empty($department)) {
    $conditions[] = " m.department = '$department'";
}

if (!empty($memberType)) {
    $conditions[] = " m.member_type = '$memberType'";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

    $sql = rtrim($sql, "AND"); // Remove the last 'AND' if present


$result = $conn->query($sql);
if (!$result) {
    die('Database query error: ' . $conn->error);
}

$response = array(
    'labels' => array('Today'),
    'borrowed' => array(),
    'returned' => array()
);

while ($row = $result->fetch_assoc()) {
    // Since we are using SUM for counts, we don't need to use 'borrowed_date' and 'returned_date' here
    $response['borrowed'][] = $row['borrowed_count'];
    $response['returned'][] = $row['returned_count'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>


