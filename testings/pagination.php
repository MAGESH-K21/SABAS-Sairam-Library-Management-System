<?php
include 'db.php';

// Determine the total number of records
$sqlCount = "SELECT COUNT(*) as total FROM books";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalRecords = $rowCount['total'];

// Set the number of records to display per page
$recordsPerPage =1000;

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get the current page number
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

// Calculate the starting offset for retrieving records
$offset = ($currentPage - 1) * $recordsPerPage;

// Retrieve the records for the current page
$sql = "SELECT * FROM books LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);
echo '<div style="width:500px;height: 400px;overflow-x:scroll;overflow-y:scroll">';
echo '<table border="2px">';
// Display the paginated results
while ($row = $result->fetch_assoc()) {
    // Display the book details
    echo '<tr><td>';
    echo $row['title'];
    echo $row['author'];
    echo $row['publication_year'];
    echo $row['publication_year'];
    echo "</td></tr>";
}
echo '</table>';
echo '</div>';
// Display pagination links
echo "<div class='pagination'>";
if ($totalPages > 1) {
    if ($currentPage > 1) {
        echo "<a href='pagination.php?page=" . ($currentPage - 1) . "'>Previous</a>";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            echo "<span class='current'>$i </span>";
        } else {
            echo "<a href='pagination.php?page=$i'>$i </a>";
        }
    }

    if ($currentPage < $totalPages) {
        echo "<a href='pagination.php?page=" . ($currentPage + 1) . "'> Next</a>";
    }
}
echo "</div>";

// Close the database connection
$conn->close();
?>
