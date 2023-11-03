<?php
session_start();
include '../admin/connection/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 5);
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




$table = '';

while ($row = $result->fetch_assoc()) {
    // Build the table rows
    $table .= '<tr class="bg-[#EEEFFF] font-signika-negative border-b border-purple-700">';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['borrowing_id'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['member_name'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['department'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['member_type'] . '</td>'; // Add this line for member_type
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['student_college_id'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['book_title'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['book_category'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['book_subject'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['borrowed_date'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['due_date'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['returned_date'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">$' . $row['fine_amount'] . '</td>';
$table .= '<td nowrap class="px-3 py-2 text-center whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">' . $row['status'] . '</td>';
$table .= '</tr>';

}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System Report</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet"> -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
        #update-btn{
            border-width:2px;
            border-color:#818cf8;
            border-style:solid;
           
            background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
            transition-property:border-width;
            transition-timing-function:ease-in;
            transition-duration:.1s;
        }

        #update-btn:hover{
            background-image: linear-gradient(to left,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
            border-width:4px;
        }
        /* Pagination styles */
        .pagination {
            margin-top: 10px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid #ddd;
            margin-right: 5px;
            background-color: #b1b1ff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a.active {
            background-color: #333;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
            .pur{
    color: #5500A9;
            }
            .rr{
                color: #D00000;
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
<?php
    include '../admin/includes/header.php';
?>
<h1 class="text-center text-gray-900 font-bold text-2xl mx-auto mt-2 mb-4">Generate Overall Library Report</h1>
<div class="md:container md:mx-auto ">

    <form action="generate_report.php" method="get">

    <div class="p-7 rounded-md grid grid-cols-3 bg-[#EEEFFF] sm:grid-cols-7 gap-1 m-3 border border-indigo-700">
        <!-- Row 1 -->
        <div class="rounded-md  p-1">
        <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="date_selection">Select Date Range: </label>
            <select id="date_selection" class="border border-purple-700 bg-white  text-gray-900 w-full py-[6px]" name="date_selection" onchange="updateDateFields()">
                <option style="padding:10px;" class="py-2 border border-purple-700" value="today">Today</option>
                <option class="py-2  border border-purple-700" value="daily">Daily</option>
                <option class="py-2 border border-purple-700" value="duration">Duration</option> <!-- Default: Duration -->
            </select>
        </div>
        <div class=" rounded-md  p-1">
            
        <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="from_date">From Date:
        <input  class="border border-purple-700 text-gray-900 w-full py-2" type="date" id="from_date" name="from_date" value="<?php echo $fromDate; ?>" required>
        </label>
        </div>
        <div class=" rounded-md  p-1">
        <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="to_date">To Date:
        <input  class="border border-purple-700 text-gray-900 w-full py-2" type="date" id="to_date" name="to_date" value="<?php echo $toDate; ?>" required></label>
        </div>
        <div class=" rounded-md  p-1">
               
        <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="search_query">SEARCH:
        <input  class="border border-purple-700 text-gray-900 w-full py-[9px]" type="text" placeholder="Search For Name,Book id etc"  name="search_query" value="<?php echo isset($_GET['search_query']) ? $_GET['search_query'] : ''; ?>"></label>
        </div>
        
        <!-- Row 2 -->
        <div class=" rounded-md  p-1">
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
        <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="department">Department:
    <select  class="border border-purple-700 bg-white text-gray-900 w-full py-2" name="department" > 
        <option class="px-4 py-2" value="">All Departments</option>
           <?php
    
    while ($rowDistinctDepartment = $resultDistinctDepartments->fetch_assoc()) {
        $selected = ($_GET['department'] === $rowDistinctDepartment['department']) ? 'selected' : '';
        echo '<option class="bg-blue-900" value="' . $rowDistinctDepartment['department'] . '" ' . $selected . '>' . $rowDistinctDepartment['department'] . '</option>';
    }
    ?>
    
          
    </select></label>
    <?php
    
    $stmtDistinctDepartments->close();
    $conn->close();
    ?>

        </div>
        <div class="rounded-md  p-1">
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
    <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="report_type">Member Type:
    
    <select  class=" border border-purple-700 bg-white text-gray-900 w-full py-2"  name="member_type" >
        <option value="">All Member Types</option>
        <?php
        while($rowDistinctMemberType = $resultDistinctMemberTypes->fetch_assoc()) {
            $selected = ($_GET['member_type'] === $rowDistinctMemberType['member_type']) ? 'selected' : '';
            echo '<option value="' . $rowDistinctMemberType['member_type'] . '" ' . $selected . '>' . $rowDistinctMemberType['member_type'] . '</option>';
        }
    ?>
    </select></label>
    <?php
        $stmtDistinctMemberTypes->close();
    ?></div>
        <div class="rounded-md  p-1"> 
    <label class="text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider" for="report_type">Report Type:
    <select  class="border border-purple-700 bg-white text-gray-900 w-full py-2" name="report_type" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="overdue" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
        <option value="return" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'return' ? 'selected' : ''; ?>>Return</option>
        <option value="borrowed" <?php echo isset($_GET['report_type']) && $_GET['report_type'] === 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
    </select></label>
    </div>
        <!-- <div class="bg-teal-200 bg-violet-400 rounded-md  p-1">8</div> -->
       
    </div>
    
         <!-- Dropdown for selecting date range -->
         
            
    <button type="submit" id="update-btn" class="block font-bold px-4 py-2 mx-auto my-2 bg-purple-500 text-gray-100">Generate Report</button>
    </form>

<script>
      function updateDateFields() {
        var dateSelection = document.getElementById("date_selection").value;
        var currentDate = new Date().toISOString().split("T")[0]; // Get current date in "YYYY-MM-DD" format
        
        if (dateSelection === "today") {
            document.getElementById("from_date").value = currentDate;
            document.getElementById("to_date").value = currentDate;
        } else if (dateSelection === "duration") {
            // Make an AJAX request to fetch the minimum date from the database
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var minDate = xhr.responseText;
                    document.getElementById("from_date").value = minDate;
                    document.getElementById("to_date").value = currentDate;
                }
            };
            xhr.open("GET", "get_min_date.php", true);
            xhr.send();
        }
    }
</script>

<div class="grid grid-cols-1 sm:grid-cols-2 m-3 ">
       
        <!-- First Row -->
        
        <div class="bg-[#EEEFFF] border-b-2   p-1 text-center font-bold text-xl align-middle sm:col-span-3"> <h1>Library Management System Report</h1></div>
       
<div class="bg-[#EEEFFF] border-b-2   p-1 text-center font-bold text-xl align-middle">      
<form action="generate_report.php" method="get">
    <!-- ... (Your existing form elements) ... -->

    <!-- Add this Export to CSV button -->
    <a href="generate_csv.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="csvbtn" class=""><img src="../exportcsv.png" alt="" width="40px" class="mx-4 border-2 border-[#7f5fff]"></a>
</form>


</div>
        
        <!-- Second Row -->
        <div class=" min-h-fit max-h-80 sm:col-span-4 ">
            <div class="outer h-full  overflow-scroll">
    <div class="inner">
        <?php if ($table !== '') : ?>
            <table id="booksTable">
            <tr class="bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 border-b-2  sticky top-0">
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Borrowing ID</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Member</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Department</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Member Type</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">College ID</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Book</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Category</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Subject</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Borrowed Date</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Due Date</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Returned Date</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Fine Amount</th>
    <th nowrap class="px-6 py-3  text-center text-xs leading-4 font-bold text-gray-900 uppercase tracking-wider">Status</th>
</tr>

                <?php echo $table; ?>
            </table>
        <?php else : ?>
            <p class="text-red-500 font-bold text-center align-middle">No records found.</p>
        <?php endif; ?>
    </div>
</div>
<div class="bg-[#EEEFFF] p-1 mt-0 sm:col-span-4">
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?php echo $i; ?>&from_date=<?php echo urlencode($fromDate); ?>&to_date=<?php echo urlencode($toDate); ?>&search_query=<?php echo urlencode($searchQuery); ?>&department=<?php echo urlencode($_GET['department']); ?>&member_type=<?php echo urlencode($_GET['member_type']); ?>&report_type=<?php echo urlencode($_GET['report_type']); ?>"
           <?php if ($current_page == $i) echo 'class="active"'; ?>>
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>
</div>
        
        <!-- Third Row -->
      </div>
    </div>
    


</div>
<script src="exportcsv.js"></script>


</body>
</html>
