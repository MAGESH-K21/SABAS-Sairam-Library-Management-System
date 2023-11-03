<?php
include '../connection/db.php';
error_reporting(0);
// Check if the user is authenticated
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true)
 {
    header('Location: ./admin/login.php');
    exit();
}
 $s1 = "SELECT DISTINCT member_id FROM borrowings";
 $s1res = $conn->query($s1);
 
 while ($rowws = $s1res->fetch_assoc()) {
     $member_id = $rowws['member_id'];
 
     $s2 = "SELECT book_id,SUM(fine_amount) AS total_fine FROM borrowings WHERE member_id='$member_id'";
     $s2res = $conn->query($s2);
 
     if ($s2res && $rowws2 = $s2res->fetch_assoc()) {
         $fine = $rowws2['total_fine'];
       
 
         $sql2 = "UPDATE members SET fine_amount='$fine' WHERE member_id='$member_id'";
         $s3res = $conn->query($sql2);

       
 
         if (!$s3res) 
         {
             echo "Error updating fine amount for member ID: $member_id" . PHP_EOL;
             header('Location:members2.php');
            }
        } else {
            echo "Error retrieving fine amount for member ID: $member_id" . PHP_EOL;
            header('Location:members2.php');
     }
 }

// Add Fine to Member
function addFineToMember($memberId, $fineAmount)
{
    include '../connection/db.php';
    // Update the fine_amount column in the members table
    $sql = "UPDATE members SET fine_amount = fine_amount + $fineAmount WHERE member_id = $memberId";
    $sql = "UPDATE fines SET member_id = $memberId";
    $sql = "INSERT INTO fines (book_id, fine_amount, fine_date) VALUES ($bookId, $fineAmount, '$fineDate')";
    $conn->query($sql);
}
// Calculate Fine
function calculateFine($dueDate, $returnDate, $fineRate)
{
    $dueDateTime = new DateTime($dueDate);
    $returnDateTime = new DateTime($returnDate);
    $diff = $returnDateTime->diff($dueDateTime);

    $daysLate = $diff->days;
    $fineAmount = $daysLate * $fineRate;

    // Ensure the fine amount is not less than the minimum amount (e.g., Rs. 5)
    $minimumFineAmount = 5;
    $fineAmount = max($fineAmount, $minimumFineAmount);
    return $fineAmount;
}
// Handle form submission for adding fine to member
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_fine'])) {
        $memberId = $_POST['member_id'];
        $dueDate = $_POST['due_date'];
        $returnDate = $_POST['return_date'];
        $fineRate = 5; // Fine rate per day

        $fineAmount = calculateFine($dueDate, $returnDate, $fineRate);

        // Add the fine amount to the member
        addFineToMember($memberId, $fineAmount);
    }
}

// Add a new member
if (isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $member_type = $_POST['member_type'];

    // Validate input
    $errors = validateMemberInput($name, $email, $phone, $member_type);
    if (empty($errors)) {
       
            $department = $_POST['department'];
            $year = $_POST['year'];
            $class = $_POST['class'];
            $college_id = $_POST['college_id'];

            $sql = "INSERT INTO members (name, email, phone, member_type, department, year, class, college_id)
                    VALUES ('$name', '$email', '$phone', '$member_type', '$department','$year', '$class',  '$college_id')";
        
        if ($conn->query($sql)) {
            header('Location: members2.php');
            exit();
        } else {
            $errors[] = 'Failed to add the member.';
        }
    }
}

// Delete a member
if (isset($_GET['delete_member'])) {
    $member_id = $_GET['delete_member'];
    $sql = "DELETE FROM members WHERE member_id = $member_id";
    $conn->query($sql);
    header('Location: members2.php');
    exit();
}



// Determine the total number of records
$sqlCount = "SELECT COUNT(*) as total FROM members";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalRecords = $rowCount['total'];

// Set the number of records to display per page
$recordsPerPage = 3;

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure the current page is within valid range

// Calculate the starting offset for retrieving records
$offset = ($currentPage - 1) * $recordsPerPage;

// Define the number of visible page links
$visiblePageLinks = 15;

// Calculate the range of page links to display
$startPage = max(1, $currentPage - floor($visiblePageLinks / 2));
$endPage = min($startPage + $visiblePageLinks - 1, $totalPages);
$startPage = max(1, $endPage - $visiblePageLinks + 1);

// Adjust the range if it extends beyond the total number of pages
$endPage = min($endPage, $totalPages);
$startPage = max(1, $startPage);


// Retrieve all members
$sql = "SELECT * FROM members LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);

// Function to validate member input
function validateMemberInput($name, $email, $phone, $member_type)
{
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Please enter the member name.';
    }

    if (empty($email)) {
        $errors[] = 'Please enter the email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($phone)) {
        $errors[] = 'Please enter the phone number.';
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Please enter a valid 10-digit phone number.';
    }

    if (empty($member_type)) {
        $errors[] = 'Please select the member type.';
    }

    return $errors;
}
?>
 <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("membersTable");
            switching = true;
            dir = "asc"; // Set the sorting direction to ascending
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[n];
                    y = rows[i + 1].getElementsByTagName("td")[n];
                    if (dir === "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>

    
<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }

         h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: row;
            margin-bottom: 20px;
            max-width: 50vw;
            margin-left: auto;
            margin-right: auto;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            
        }

        select {
            width: 100%;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        /* Table styles */
        table {
           width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin:auto;
            margin-left:10px
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
            font-size:12px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Search and filter styles */
        .search-form {
            text-align: right;
            margin-bottom: 10px;
        }

        .search-input {
            padding: 5px;
            border: 1px solid #ccc;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 10px;
            max-width:30%;
        }

        .filter-select {
            margin-right: 10px;
            padding: 5px;
            border: 1px solid #ccc;
        }

       

        /* Error message styles */
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .error li {
            list-style: none;
        }
        .forms-container{
            display:flex;
            justify-content:space-evenly;
        } 
    </style>

   
     <script>
        function searchMembers() {
            // Get the search query
            var searchQuery = document.getElementById("searchInput").value.toLowerCase();
            
            // Get the table rows
            var table = document.getElementById("membersTable");
            var rows = table.getElementsByTagName("tr");

            // Loop through the rows and hide those that don't match the search query
            for (var i = 1; i < rows.length; i++) {
                var memberName = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();
                var studentid = rows[i].getElementsByTagName("td")[7].innerText.toLowerCase();
                var staffid = rows[i].getElementsByTagName("td")[10].innerText.toLowerCase();
                
                if (memberName.includes(searchQuery))
                {
                    rows[i].style.display = "";
                } 
                else if(studentid.includes(searchQuery))
                {
                    rows[i].style.display = "";
                } 
                else if(staffid.includes(searchQuery)){
                    rows[i].style.display = "";
                } 
                else
                {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>

<script>
        function searchMembers() {
            // Get the search query
            var searchQuery = document.getElementById("searchInput").value.toLowerCase();

            // Get the table rows
            var table = document.getElementById("membersTable");
            var rows = table.getElementsByTagName("tr");

            // Loop through the rows and hide those that don't match the search query
            for (var i = 1; i < rows.length; i++) {
                var memberName = rows[i].getElementsByTagName("td")[2].innerText.toLowerCase();

                if (memberName.includes(searchQuery)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function filterMembers() {
            // Get the filter values
            var memberType = document.getElementById("filterMemberType").value;
            var classFilter = document.getElementById("filterClass").value;

            // Get the table rows
            var table = document.getElementById("membersTable");
            var rows = table.getElementsByTagName("tr");

            // Loop through the rows and hide those that don't match the filter criteria
            for (var i = 1; i < rows.length; i++) {
                var memberTypeCell = rows[i].getElementsByTagName("td")[1].innerText;
                var classCell = rows[i].getElementsByTagName("td")[5].innerText;

                var memberTypeMatch = (memberType === "" || memberType === memberTypeCell);
                var classMatch = (classFilter === "" || classFilter === classCell);

                if (memberTypeMatch && classMatch) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>

<header class="bg-blue-500">
        <div class=" mx-auto py-4 px-6 flex justify-between items-center">
            <h1 class="text-white text-3xl font-bold">SABAS</h1>
            <nav>
                <ul class="flex space-x-4 text-white text-lg">
                    <!-- <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="books.php">Manage Books</a></li>
                    <li><a href="add_books.php">Add Books</a></li>
                    <li><a href="members2.php">Manage Members</a></li>
                    <li><a href="borrowings.php">Manage Borrowings</a></li> -->
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a href="../../index.php">Home</a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Members</a>
                            <a href="../../testings/reports.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Reports</a>
                            <a href="../circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Settings</a>
                           
                        </div>
                       
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="#" class="dropbtn">books</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="../books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="../books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="../academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../academics/academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="../academics/academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="../academics/academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="../academics/academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Course</a>
                        </div>
                    </li>

                
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-gray-900 bg-blue-900" ><a href="../logout.php">Logout</a></li>

                    <?php
                        }
                        else{

                        ?>
                         <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a href="./admin/login.php">Admin Login</a></li>
                         <?php

                        }
                        ?>
                </ul>
            </nav>
        </div>
    </header>

    <h1 class="m-4 bg-blue-400" style="font-size:30px;font-weight:bold;text-align:center;">Manage Members</h1>
<?php
 $s1 = "SELECT DISTINCT member_id FROM borrowings";
 $s1res = $conn->query($s1);
 
 while ($rowws = $s1res->fetch_assoc()) {
     $member_id = $rowws['member_id'];
 
     $s2 = "SELECT SUM(fine_amount) AS total_fine FROM borrowings WHERE member_id='$member_id'";
     $s2res = $conn->query($s2);
 
     if ($s2res && $rowws2 = $s2res->fetch_assoc()) {
         $fine = $rowws2['total_fine'];
       
 
         $sql2 = "UPDATE members SET fine_amount='$fine' WHERE member_id='$member_id'";
         $s3res = $conn->query($sql2);
 
         if (!$s3res) {
             echo "Error updating fine amount for member ID: $member_id" . PHP_EOL;
             header('Location:members2.php');
            }
        } else {
            echo "Error retrieving fine amount for member ID: $member_id" . PHP_EOL;
            header('Location:members2.php');
     }
 }
 
// require_once '../Classes/PHPExcel.php';

// Import members from Excel file
if (isset($_POST['import_members'])) {
    echo "suv";
    $file = $_FILES['excel_file']['tmp_name'];

    // Load the Excel file
    $objPHPExcel = PHPExcel_IOFactory::load($file);
    $worksheet = $objPHPExcel->getActiveSheet();

    // Get the highest row and column index
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    // Loop through each row (starting from the second row)
    for ($row = 2; $row <= $highestRow; $row++) {
        $name = $worksheet->getCell('A' . $row)->getValue();
        $email = $worksheet->getCell('B' . $row)->getValue();
        $phone = $worksheet->getCell('C' . $row)->getValue();
        $memberType = $worksheet->getCell('D' . $row)->getValue();

        // Additional fields for students
         $department = $worksheet->getCell('E' . $row)->getValue();
         $class = $worksheet->getCell('F' . $row)->getValue();
         $section = $worksheet->getCell('G' . $row)->getValue();
         $collegeID = $worksheet->getCell('H' . $row)->getValue();

        // Additional fields for staff
         $staffDepartment = $worksheet->getCell('E' . $row)->getValue();
         $staffCollegeID = $worksheet->getCell('F' . $row)->getValue();

        // Validate input
        $errors = validateMemberInput($name, $email, $phone, $memberType);
        if (empty($errors)) {
            if ($memberType == 'student') {
                
                $sql = "INSERT INTO `members` (name, email, phone, member_type, department, class, section, college_id)
                        VALUES ('$name', '$email', '$phone', '$memberType', '$department', '$class', '$section', '$collegeID')";
            } elseif ($memberType == 'staff') {
                $sql = "INSERT INTO members (name, email, phone, member_type, department, college_id)
                        VALUES ('$name', '$email', '$phone', '$memberType', '$staffDepartment', '$staffCollegeID')";
            }
            
            if ($conn->query($sql)) {
                continue;
            }
            else{
                
                echo "fail";
            }
        }

        // Handle errors
        $errors[] = 'Failed to import member from Excel row ' . $row;
    }
}
?>


<!-- Import Members Form -->
<div class="forms-container">
    <div class="form1">
        
        <h2 style="font-size:25px;font-weight:bold;text-align:center;">Import Members from Excel</h2>
        <form method="POST" enctype="multipart/form-data" class="import-excel-form shadow-2xl p-8">
            <input type="file" name="excel_file" required class="import-excel">
            <br>
            <input type="submit" name="import_members" value="Import Members">
        </form>
    </div>
    <div class="form2">
    
        <h2 class="" style="font-size:25px;font-weight:bold;text-align:center;">Add Member</h2>
        <form method="POST" class="p-8 shadow-2xl">
            <label>Name:</label>
            <input type="text" name="name" required>
                    <label>Email:</label>
            <input type="email" name="email" required>
            <label>Phone:</label>
            <input type="tel" name="phone" required>
            <br>
            <label>Member Type:</label>
            <select name="member_type" required class="import-excel">
                <option value="">Select Member Type</option>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
            </select>
            <br>
            <?php
                        $ql = "SELECT * from departments";
                        $re = $conn->query($ql);
                 ?>
            <!-- Student Fields -->
            <div id="student_fields" style="display: none;">
                <label>Department:</label>
                <select name="department" id="department">
                    <option value="">--Select--</option>
                    <?php
                    while($ro = $re->fetch_assoc())
                    {
                ?>
                <option value="<?php echo $ro['department_name']; ?>"><?php echo $ro['department_name']; ?></option>
                <?php
                    }
                        ?>
                </select>
                <br>
                <label>Section:</label>
                <?php
                        $ql = "SELECT * from classes";
                        $re = $conn->query($ql);
                 ?>
                  <select name="class" id="sectclassion">
                    <option value="">--Select--</option>
                    <?php
                    while($ro = $re->fetch_assoc())
                    {
                ?>
                <option value="<?php echo $ro['class_name']; ?>"><?php echo $ro['class_name']; ?></option>
        
                <?php
                        }
                        ?>
                </select>
                <br>
                <label>Year:</label>
                <?php
                        $ql = "SELECT year_id from years";
                        $re = $conn->query($ql);
                 ?>
                  <select name="year" id="sectyearion">
                    <option value="">--Select--</option>
                    <?php
                    while($ro = $re->fetch_assoc())
                    {
                ?>
                <option value="<?php echo $ro['year_id']; ?>"><?php echo $ro['year_id']; ?></option>
        
                <?php
                        }
                        ?>
                </select>
                <br>
             
                <br>
              
                <label>College ID:</label>
                <input type="text" name="college_id">
                <br>
            </div>
        
            <!-- Staff Fields -->
            <div id="staff_fields" style="display: none;">
                <label>Department:</label>
                <input type="text" name="department">
                <br>
                <label>College ID:</label>
                <input type="text" name="college_id">
                <br>
            </div>
        
            <input type="submit" name="add_member" value="Add Member">
        </form>
        
        <!-- Error Handling -->
        <?php if (!empty($errors)) : ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <!-- Member List -->
    </div>
    
</div>
<!-- Add Member Form -->
<h2 style="font-size:25px;font-weight:bold;text-align:center;">Member List</h2>

<div class="books flex items-center justify-center space-x-2 m-2">
    
    <?php if ($totalPages > 1): ?>
        <?php if ($currentPage > 1): ?>
            <a href="<?php echo getPageUrl($currentPage - 1); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Previous</a>
            <?php endif; ?>
            
            <?php if ($startPage > 1): ?>
                <a href="<?php echo getPageUrl(1); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 ">1</a>
                <?php if ($startPage > 2): ?>
                    <span class="current bg-blue-500 text-white font-bold py-2 px-4 ">...</span>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="current bg-blue-500 text-white font-bold py-2 px-4 "><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="<?php echo getPageUrl($i); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $i; ?></a>
                                <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <span class="current bg-blue-500 text-white font-bold py-2 px-4 ">...</span>
                                        <?php endif; ?>
                                        <a href="<?php echo getPageUrl($totalPages); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $totalPages; ?></a>
                                        <?php endif; ?>
                                        
                                        <?php if ($currentPage < $totalPages): ?>
                                            <a href="<?php echo getPageUrl($currentPage + 1); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Next</a>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
<?php
// Function to generate the page URL with the given page number
function getPageUrl($pageNumber) {
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $queryParams);
    $queryParams['page'] = $pageNumber;
    return $_SERVER['PHP_SELF'] . '?' . http_build_query($queryParams) .'#membersTable';
}
?>
    <div class="" style="width:96vw;overflow-x:scroll;margin:20px 0px;">
        
        <table id="membersTable">
            <tr>
                <th  onclick="sortTable(0)">Member ID</th>
                <th nowrap onclick="sortTable(4)">Member Type</th>
                <th nowrap onclick="sortTable(1)">Name</th>
                <th nowrap onclick="sortTable(2)">Email</th>
                <th nowrap onclick="sortTable(3)">Phone</th>
                <th nowrap onclick="sortTable(5)">Class</th>
                <th nowrap onclick="sortTable(5)">Year</th>
                <th nowrap onclick="sortTable(7)">College ID</th>
            <th nowrap onclick="sortTable(8)">Department</th>
            <th nowrap onclick="sortTable(11)">Fine Amount</th>
            <th>Actions</th>


        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td nowrap><?php echo $row['member_id']; ?></td>
                <td nowrap><?php echo $row['member_type']; ?></td>
                <td nowrap><?php echo $row['name']; ?></td>
                <td nowrap ><?php echo $row['email']; ?></td>
                <td nowrap><?php echo $row['phone']; ?></td>
                <td nowrap><?php echo $row['class']; ?></td>
                <td nowrap><?php echo $row['year']; ?></td>
                <td nowrap><?php echo $row['college_id']; ?></td>
                <td nowrap><?php echo $row['department']; ?></td>
               
                <td nowrap><?php echo $row['fine_amount']; ?></td>
                <td>
                <a href="view_member.php?member_id=<?php echo $row['member_id']; ?> "class="px-2 py-1 bg-green-800 hover:bg-green-300 text-gray-100 hover:text-gray-900">View</a>
                    <a href="edit_member.php?member_id=<?php echo $row['member_id']; ?>"class="px-2 py-1 bg-blue-800 hover:bg-blue-300 text-gray-100 hover:text-gray-900">Edit</a>
                    <a class="bg-red-700 hover:bg-red-300 text-gray-100 hover:text-gray-900 px-3 py-1 text-center" href="members2.php?delete_member=<?php echo $row['member_id']; ?>" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>

    <script>
        // Show/hide student fields based on member type selection
        var memberTypeSelect = document.querySelector('select[name="member_type"]');
        var studentFields = document.getElementById('student_fields');
        var staffFields = document.getElementById('staff_fields');

        memberTypeSelect.addEventListener('change', function() {
            if (this.value === 'student') {
                studentFields.style.display = 'block';
                staffFields.style.display = 'none';
            } else if (this.value === 'staff') {
                studentFields.style.display = 'none';
                staffFields.style.display = 'block';
            } else {
                studentFields.style.display = 'none';
                staffFields.style.display = 'none';
            }
        });
    </script>
    </div>

    <footer class="bg-blue-500 body-font">
  <div class="container px-4 py-4 mx-auto flex items-center sm:flex-row flex-col">
    <a class="flex title-font font-medium items-center md:justify-start justify-center ">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
      </svg>
      <span class="ml-3 text-xl">Sairam-LMS</span>
    </a>
    <p class="text-sm sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">© 2023 future8.infotech@gmail.com —
      <a href="https://twitter.com/knyttneve" class=" ml-1" rel="noopener noreferrer" target="_blank">@future8</a>
    </p>
    <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
      <a class="">
        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
          <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
          <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
          <circle cx="4" cy="4" r="2" stroke="none"></circle>
        </svg>
      </a>
    </span>
  </div>
</footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
