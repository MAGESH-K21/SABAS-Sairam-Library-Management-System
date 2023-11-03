<?php
include '../admin/connection/db.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System Report</title>
</head>
<body>
<label for="department">Department:</label>
<select name="department">
    <option value="">All Departments</option>
    <?php
    // Create a new query for fetching distinct departments
$queryDistinctDepartments = "SELECT DISTINCT m.department FROM borrowings b
                             JOIN members m ON b.member_id = m.member_id
                             JOIN books bk ON b.book_id = bk.book_id
                             WHERE b.borrowed_date BETWEEN ? AND ?";

$stmtDistinctDepartments = $conn->prepare($queryDistinctDepartments);

if ($stmtDistinctDepartments === false) {
    die('Error preparing statement: ' . $conn->error);
}

if (!$stmtDistinctDepartments->bind_param("ss", $fromDate, $toDate)) {
    die('Error binding parameters: ' . $stmtDistinctDepartments->error);
}

if (!$stmtDistinctDepartments->execute()) {
    die('Error executing query: ' . $stmtDistinctDepartments->error);
}

$resultDistinctDepartments = $stmtDistinctDepartments->get_result();

if ($resultDistinctDepartments === false) {
    die('Error getting result set: ' . $conn->error);
}

while ($rowDistinctDepartment = $resultDistinctDepartments->fetch_assoc()) {
    $selected = ($_GET['department'] === $rowDistinctDepartment['department']) ? 'selected' : '';
    echo '<option value="' . $rowDistinctDepartment['department'] . '" ' . $selected . '>' . $rowDistinctDepartment['department'] . '</option>';
}

?>
</select>

</body>
</html>
