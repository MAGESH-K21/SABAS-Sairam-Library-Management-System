<?php
session_start();
include '../admin/connection/db.php';

$resultsPerPage = 3; // Number of results per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number from the URL query parameter

$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d');
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

$queryTotal = "SELECT COUNT(*) as total FROM borrowings b WHERE b.borrowed_date BETWEEN ? AND ?";

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
          WHERE b.borrowed_date BETWEEN ? AND ?";

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
        $query .= " AND b.returned_date IS NULL AND b.borrowed_date >= ?";
   
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
            $bindTypes .= "s"; // Update the $bindTypes string
        }
    
}


$bindParams[] = &$offset;
$bindParams[] = &$resultsPerPage;
echo $bindTypes .= "ii";

echo $stmt->bind_param($bindTypes, ...$bindParams);

// Execute the prepared statement
$stmt->execute();




$result = $stmt->get_result();


$table = '';
while ($row = $result->fetch_assoc()) {
    // Build the table rows
    $table .= '<tr>';
$table .= '<td>' . $row['borrowing_id'] . '</td>';
$table .= '<td>' . $row['member_name'] . '</td>';
$table .= '<td>' . $row['department'] . '</td>';
$table .= '<td>' . $row['member_type'] . '</td>'; // Add this line for member_type
$table .= '<td>' . $row['student_college_id'] . '</td>';
$table .= '<td>' . $row['book_title'] . '</td>';
$table .= '<td>' . $row['book_category'] . '</td>';
$table .= '<td>' . $row['book_subject'] . '</td>';
$table .= '<td>' . $row['borrowed_date'] . '</td>';
$table .= '<td>' . $row['due_date'] . '</td>';
$table .= '<td>' . $row['returned_date'] . '</td>';
$table .= '<td>$' . $row['fine_amount'] . '</td>';
$table .= '<td>' . $row['status'] . '</td>';
$table .= '</tr>';

}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 10px;
        }
        .pagination a {
            padding: 5px 10px;
            border: 1px solid #ddd;
            margin-right: 5px;
        }
        .pagination a.active {
            background-color: #f2f2f2;
        }
    </style>
      <script>
        function showDateInputs() {
            var selection = document.getElementById("dateSelection").value;
            var todayDateInput = document.getElementById("todayDateInput");
            var particularDateInput = document.getElementById("particularDateInput");
            var fromDateInput = document.getElementById("fromDateInput");
            var toDateInput = document.getElementById("toDateInput");

            // Hide all date inputs
            todayDateInput.style.display = "none";
            particularDateInput.style.display = "none";
            fromDateInput.style.display = "none";
            toDateInput.style.display = "none";

            // Show the selected date input(s)
            if (selection === "today") {
                todayDateInput.style.display = "block";
            } else if (selection === "particular_date") {
                particularDateInput.style.display = "block";
            } else if (selection === "duration") {
                fromDateInput.style.display = "block";
                toDateInput.style.display = "block";
            }
        }
    </script>
</head>
<body>
<h1>Generate Overall Library Report</h1>
<form action="generate_report.php" method="get">
    <label for="from_date">From Date:</label>
    <input type="date" name="from_date" value="<?php echo $fromDate; ?>" required>
    <label for="to_date">To Date:</label>
    <input type="date" name="to_date" value="<?php echo $toDate; ?>" required>
    <label for="search_query">Search:</label>
    <input type="text" name="search_query" value="<?php echo isset($_GET['search_query']) ? $_GET['search_query'] : ''; ?>">
    
    <?php
        include '../admin/connection/db.php';

        // Create a new query for fetching distinct departments
$queryDistinctDepartments = "SELECT DISTINCT m.department FROM borrowings b
JOIN members m ON b.member_id = m.member_id
JOIN books bk ON b.book_id = bk.book_id";

$stmtDistinctDepartments = $conn->prepare($queryDistinctDepartments);

if ($stmtDistinctDepartments === false) {
die('Error preparing statement: ' . $conn->error);
}

if (!$stmtDistinctDepartments->execute()) {
die('Error executing query: ' . $stmtDistinctDepartments->error);
}

$resultDistinctDepartments = $stmtDistinctDepartments->get_result();

if ($resultDistinctDepartments === false) {
die('Error getting result set: ' . $conn->error);
}
?>
    <label for="department">Department:</label>
<select name="department"> 
    <option value="">All Departments</option>
       <?php

while ($rowDistinctDepartment = $resultDistinctDepartments->fetch_assoc()) {
    $selected = ($_GET['department'] === $rowDistinctDepartment['department']) ? 'selected' : '';
    echo '<option value="' . $rowDistinctDepartment['department'] . '" ' . $selected . '>' . $rowDistinctDepartment['department'] . '</option>';
}
?>

      
</select>
<?php

$stmtDistinctDepartments->close();
$conn->close();
?>
<?php
        include '../admin/connection/db.php';

    $queryDistinctMemberTypes = "SELECT DISTINCT m.member_type FROM borrowings b
                                 JOIN members m ON b.member_id = m.member_id
                                 JOIN books bk ON b.book_id = bk.book_id";

    $stmtDistinctMemberTypes = $conn->prepare($queryDistinctMemberTypes);

    if ($stmtDistinctMemberTypes === false) {
        die('Error preparing statement: ' . $conn->error);
    }

    if (!$stmtDistinctMemberTypes->execute()) {
        die('Error executing query: ' . $stmtDistinctMemberTypes->error);
    }

    $resultDistinctMemberTypes = $stmtDistinctMemberTypes->get_result();

    if ($resultDistinctMemberTypes === false) {
        die('Error getting result set: ' . $conn->error);
    }

?>
<select name="member_type">
    <option value="">All Member Types</option>
    <?php
    while($rowDistinctMemberType = $resultDistinctMemberTypes->fetch_assoc()) {
        $selected = ($_GET['member_type'] === $rowDistinctMemberType['member_type']) ? 'selected' : '';
        echo '<option value="' . $rowDistinctMemberType['member_type'] . '" ' . $selected . '>' . $rowDistinctMemberType['member_type'] . '</option>';
    }
?>
</select>
<?php
    $stmtDistinctMemberTypes->close();
?>


<label for="report_type">Report Type:</label>
<select name="report_type" onchange="this.form.submit()">
    <option value="">All</option>
    <option value="overdue" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
    <option value="return" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'return' ? 'selected' : ''; ?>>Return</option>
    <option value="borrowed" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
</select>


<button type="submit">Generate Report</button>
</form>

    <h1>Library Management System Report</h1>
    <p>Report Date: <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>Date Range: <?php echo $fromDate . ' to ' . $toDate; ?></p>

    
<div class="outer">
    <div class="inner">
        <?php if ($table !== '') : ?>
            <table id="booksTable">
            <tr>
    <th class="px-4 py-2">Borrowing ID</th>
    <th class="px-4 py-2">Member</th>
    <th class="px-4 py-2">Department</th>
    <th class="px-4 py-2">Member Type</th>
    <th class="px-4 py-2">College ID</th>
    <th class="px-4 py-2">Book</th>
    <th class="px-4 py-2">Category</th>
    <th class="px-4 py-2">Subject</th>
    <th class="px-4 py-2">Borrowed Date</th>
    <th class="px-4 py-2">Due Date</th>
    <th class="px-4 py-2">Returned Date</th>
    <th class="px-4 py-2">Fine Amount</th>
    <th class="px-4 py-2">Status</th>
</tr>

                <?php echo $table; ?>
            </table>
        <?php else : ?>
            <p>No records found.</p>
        <?php endif; ?>
    </div>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>&from_date=<?php echo urlencode($fromDate); ?>&to_date=<?php echo urlencode($toDate); ?>&search_query=<?php echo urlencode($searchQuery); ?>&department=<?php echo urlencode($_GET['department']); ?>&member_type=<?php echo urlencode($_GET['member_type']); ?>&report_type=<?php echo urlencode($_GET['report_type']); ?>"
           <?php if ($current_page == $i) echo 'class="active"'; ?>>
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>
<div class="bg-green-200 p-1 font-bold text-center align-middle"><p>Report Date: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    <div class="bg-red-200 p-1 font-bold text-center align-middle"><p>Date Range: <?php echo $fromDate . ' to ' . $toDate; ?></p>
</div>

</body>
</html>
