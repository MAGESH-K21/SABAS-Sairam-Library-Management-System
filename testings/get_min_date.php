<?php
include '../admin/connection/db.php';

$query = "SELECT MIN(borrowings_time) AS min_date FROM borrowings";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $minDate = $row['min_date'];
    echo $minDate;
} else {
    // Default to the current date if no minimum date is found
    echo date('Y-m-d');
}

$conn->close();
?>
