<?php
include 'connection/db.php';

$query = "SELECT d.department_name, COUNT(*) AS books_taken
          FROM borrowings b
          JOIN members m ON b.member_id = m.member_id
          JOIN departments d ON m.department = d.department_name
          GROUP BY d.department_name";

$result = $conn->query($query);

$departmentData = array();
while ($row = $result->fetch_assoc()) {
    $departmentData[$row['department_name']] = (int) $row['books_taken'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($departmentData);
?>
