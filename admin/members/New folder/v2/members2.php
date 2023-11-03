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
       if(isset($_POST['student_department']))
       {
           echo $department = $_POST['student_department'];
       }
       else{
           echo $department = $_POST['staff_department'];

       }
             $year = $_POST['year'];
           echo  $class = $_POST['class'];

           if(isset($_POST['student_department']))
           {
               echo $college_id = $_POST['college_id'];
           }
           else{
               echo $college_id = $_POST['staff_college_id'];
    
           }
             $college_id = $_POST['college_id'];

           echo $sql = "INSERT INTO members (name, email, phone, member_type, department, year, class, college_id)
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
            height: 55%;
        } 
        .pad{
            padding-top: 5px;
        }

        /* Add your existing styles here */

        @media screen and (max-width: 768px) {
            /* Adjustments for smaller screens */
            .form2 {
                padding: 10px;
            }
            .p-8 {
                padding: 5px;
            }
            .pad {
                width: 100%;
            }
            /* Add more responsive styles as needed */
        }
/* Pagination styles */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination a {
    text-decoration: none;
    padding: 5px 10px;
    margin: 0 5px;
    background-color: #f0f0f0;
    color: #333;
    border-radius: 3px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.pagination a:hover {
    background-color: #333;
    color: #fff;
}

.pagination .active {
    background-color: #333;
    color: #fff;
}

/* Style for previous and next links */
.pagination a:first-child,
.pagination a:last-child {
    background-color: #007bff;
    color: #fff;
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
    color: #D00000;
}
    </style>


   
     
</head>
<body>

<?php
include '../includes/header.php';
?>

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
        </form>
        <center>
        <input type="submit" name="import_members" value="Import Members">
</center>
    </div>


 <div class="form2 max-w-2xl  p-4 bg-white border rounded shadow-md">
        <div>
            <h2 class="text-lg font-bold text-center mb-4">Add Member</h2>
            <form method="POST">
                <label class="block mb-1 font-semibold">Name:</label>
                <input type="text" name="name" class=" px-3 py-2 mb-2 border rounded" required>
                <label class="block mb-1 font-semibold">Email:</label>
                <input type="email" name="email" class=" px-3 py-2 mb-2 border rounded" required>
                <label class="block mb-1 font-semibold">Phone:</label>
                <input type="tel" name="phone" class=" px-3 py-2 mb-2 border rounded" required>
        </div>
                <div class="p-8 shadow-2xl" style="padding-top: 1px;">
                <label class=" mb-1 font-semibold">Member Type:</label>
                <select name="member_type" class="w-1/4 px-3 py-2 mb-2 border rounded" required>
                    <option value="">Select Member Type</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>
                <?php
                    $ql = "SELECT * from departments";
                    $re = $conn->query($ql);
                ?>
                <!-- Student Fields -->
                <div id="student_fields" class="hidden">
                    <label class=" mb-1 font-semibold">Department:</label>
                    <select name="student_department" class="w-1/4  border rounded" id="department">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['department_name']; ?>"><?php echo $ro['department_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                 
                    <label class=" mb-1 font-semibold">Section:</label>
                    <?php
                        $ql = "SELECT * from classes";
                        $re = $conn->query($ql);
                    ?>
                    <select name="class" class="w-1/4 border rounded" id="sectclassion">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['class_name']; ?>"><?php echo $ro['class_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
               
                    <label class=" mb-1 font-semibold">Year:</label>
                    <?php
                        $ql = "SELECT year_id from years";
                        $re = $conn->query($ql);
                    ?>
                    <select name="year" class="w-1/4 px-3 py-2 mb-2 border rounded" id="sectyearion">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['year_id']; ?>"><?php echo $ro['year_id']; ?></option>
                        <?php endwhile; ?>
                    </select>
                   
                    <label class=" mb-1 font-semibold">Student ID:</label>
                    <input type="text" name="college_id" class="w-1/4 px-3 py-2 mb-2 border rounded">
                </div>
            
                <!-- Staff Fields -->
                <div id="staff_fields" class="hidden">
                    <br>
                    <label class="block mb-1 font-semibold">Department:</label>
                    <select name="staff_department" class="w-full px-3 py-2 mb-2 border rounded" id="department">
                        <option value="">--Select--</option>
                        <?php
                            // Reset the query and fetch departments again
                            $ql = "SELECT * from departments";
                            $re = $conn->query($ql);
                            while ($ro = $re->fetch_assoc()) {
                                echo '<option value="' . $ro['department_name'] . '">' . $ro['department_name'] . '</option>';
                            }
                        ?>
                    </select>
                    <br>
                    <br>
                    <label class="block mb-1 font-semibold">Staff ID:</label>
                    <input type="text" name="staff_college_id" class="w-full px-3 py-2 mb-2 border rounded">
                </div>
            
                <div class="text-center">
                    <input type="submit" name="add_member" value="Add Member" class="w-full px-4 py-2 mt-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600">
                </div>
            </form>
        </div>
        <br>
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


<div class=" books flex items-center justify-center space-x-2 m-2">

<?php
// Define the number of records per page
$recordsPerPage = 10;

// Get the current page number from the URL, default to page 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $recordsPerPage;

// Modify your SQL query to fetch a specific range of records
$sql = "SELECT member_id, member_type, name, email, phone, class, year, college_id, department, fine_amount FROM members ORDER BY member_id LIMIT $offset, $recordsPerPage ";

// Execute the SQL query
$result = $conn->query($sql);

if (!$result) {
    die('Database query error: ' . $conn->error);
}
// SQL query to count the total number of records in your table
$countQuery = "SELECT COUNT(*) AS total FROM members"; // Replace 'members' with your table name

// Execute the count query
$countResult = $conn->query($countQuery);

if (!$countResult) {
    die('Database query error: ' . $conn->error);
}

// Fetch the total count from the result
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);
?>


<div class="" style="width:99vw;height:45vh;overflow-y:scroll;margin:20px 0px;">
    <table id="membersTable">
        <tr style="position:sticky;top:0px;">
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
        <?php while ($row = $result->fetch_assoc()) {
            
            
        ?>
            <tr>
                <td nowrap><?php echo $row['member_id']; ?></td>
                <td nowrap><?php echo $row['member_type']; ?></td>
                <td nowrap><?php echo $row['name']; ?></td>
                <td nowrap><?php echo $row['email']; ?></td>
                <td nowrap><?php echo $row['phone']; ?></td>
                <td nowrap><?php echo $row['class']; ?></td>
                <td nowrap><?php echo $row['year']; ?></td>
                <td nowrap><?php echo $row['college_id']; ?></td>
                <td nowrap><?php echo $row['department']; ?></td>
                <td nowrap><?php echo $row['fine_amount']; ?></td>
                <td>
                    <a href="view_member.php?member_id=<?php echo $row['member_id']; ?>" class="px-2 py-1 bg-green-800 hover:bg-green-300 text-gray-100 hover:text-gray-900">View</a>
                    <a href="edit_member.php?member_id=<?php echo $row['member_id']; ?>" class="px-2 py-1 bg-blue-800 hover:bg-blue-300 text-gray-100 hover:text-gray-900">Edit</a>
                    <a class="bg-red-700 hover:bg-red-300 text-gray-100 hover:text-gray-900 px-3 py-1 text-center" href="members2.php?delete_member=<?php echo $row['member_id']; ?>" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                </td>
            </tr>
        <?php
            
        } // end while
        ?>
    </table>
    <!-- Add pagination controls -->
    <div class="pagination" style="position: sticky; bottom: 0; background-color: #fff;">
            <?php if ($current_page > 1) : ?>
                <a href="?page=<?php echo $current_page - 1; ?>#membersTable">Previous</a>
            <?php endif; ?>
    
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>#membersTable" <?php if ($i === $current_page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
    
            <?php if ($current_page < $totalPages) : ?>
                <a href="?page=<?php echo $current_page + 1; ?>#membersTable">Next</a>
            <?php endif; ?>
        </div>
</div>


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
