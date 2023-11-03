<?php
session_start();
$baseUrl = '/sairam_lms';
$includeUrl = $baseUrl . '/admin/connection/db.php';
// Include the file
include $_SERVER['DOCUMENT_ROOT'] . $includeUrl;
error_reporting(0);
// Check if the user is authenticated

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
       if($member_type == 'student')
       {
            $department = $_POST['student_department'];
       }
       else{
            $department = $_POST['staff_department'];

       }
             $year = $_POST['year'];

             $class = $_POST['class'];

             if($member_type == 'student')
                {
                     $college_id = $_POST['college_id'];
                }
                else
                {
                    $college_id = $_POST['staff_college_id'];
                }
           
           

            $sql = "INSERT INTO members (name, email, phone, member_type, department, year, class, college_id)
                    VALUES ('$name', '$email', '$phone', '$member_type', '$department','$year', '$class',  '$college_id')";
        
        if ($conn->query($sql)) {
            $_SESSION['success_badd'] = "Member was added successfully!";
            header('Location: members2.php');
            exit();
        } else {
            $_SESSION['Failed_msg'] = 'Failed to add the member.';
        }
    }
}

// Delete a member
if (isset($_GET['delete_member'])) {
    $member_id = $_GET['delete_member'];
    $sql = "DELETE FROM members WHERE member_id = $member_id";
    $conn->query($sql);
    $_SESSION['success_badd'] = "$member_id was deleted successfully!";
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


$includeUrl = $baseUrl . '/vendor/autoload.php';
 $autoloaderPath = $_SERVER['DOCUMENT_ROOT'] . $includeUrl;
require $autoloaderPath;use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import_members'])) {
    // Check if a file is selected
    if ($_FILES['excel_file']['name']) {
        $file = $_FILES['excel_file']['tmp_name'];

        // Load the Excel file
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        // Get the highest row and column index
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        $successMessages = []; // Array to store success messages
        $errorMessages = [];   // Array to store error messages

        // Start from the second row to skip the header row
        $successCount = 0; // Counter for successful insertions
        for ($row = 2; $row <= $highestRow; $row++) {
             $name = $worksheet->getCell('A' . $row)->getValue();
             $email = $worksheet->getCell('B' . $row)->getValue();
             $phone = $worksheet->getCell('C' . $row)->getValue();
             $memberType = $worksheet->getCell('D' . $row)->getValue();

            // Additional fields for students
            $department = $worksheet->getCell('E' . $row)->getValue();
            $year = $worksheet->getCell('F' . $row)->getValue();
            $class = $worksheet->getCell('G' . $row)->getValue();
            $section = $worksheet->getCell('H' . $row)->getValue();
            $collegeID = $worksheet->getCell('I' . $row)->getValue();

            // Additional fields for staff
            $staffDepartment = $worksheet->getCell('E' . $row)->getValue();
            $staffCollegeID = $worksheet->getCell('F' . $row)->getValue();

          
            if (true) {
                
                if ($memberType === 'student') {
                
                    $sql = "INSERT INTO members (name, email, phone, member_type, department, class, year, section, college_id)
                            VALUES ('$name', '$email', '$phone', '$memberType', '$department', '$class','$year', '$section', '$collegeID')";

            } elseif ($memberType === 'staff') {
                    $sql = "INSERT INTO members (name, email, phone, member_type, department, college_id)
                            VALUES ('$name', '$email', '$phone', '$memberType', '$staffDepartment', '$staffCollegeID')";
                }
                
            
                // Example: Checking if the SQL query was successful
                if ($conn->query($sql)) {
                    $successCount++;
                    $successMessages[] = "$name Member Imported with member type $memberType";
                } else {
                    // Error occurred while adding the member
                    $errorMessages[] = "Error adding $name as a member (Row $row)";
                }
            } else {
                // Handle validation errors
                $errorMessages = array_merge($errorMessages, $errors);
            }
        }

        // Display success and error messages
        if ($successCount > 0) {
            $successMessages[] = "$successCount members have been imported successfully.";
        }
        if (!empty($successMessages)) {
            $_SESSION['success_message'] = $successMessages;
        }
        if (!empty($errorMessages)) {
            $_SESSION['error_message'] = $errorMessages;
        }
    } else {
        $importError = 'Please select an Excel file to import.';
    }
    header("Location: members2.php");
}
?>



    
<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Signika-Negative">

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
        /* form {
            display: flex;
            flex-direction: row;
            margin-bottom: 20px;
            max-width: 50vw;
            margin-left: auto;
            margin-right: auto;
        } */

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }



        select {
            width: 100%;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
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
            margin-left:10px
        }
        th{
            border: 1px solid #ddd;
            padding:4px;
            text-align: left;
            background: linear-gradient(269deg, #B3A3FE 22.86%, #B1B9FF 91.9%);
            width:fit-content;   
        }
        td {
            border: 1px solid #ddd;
            text-align: left;
            padding:4px;
            background: #EEEFFF;
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
.active {
    text-decoration: none;
    padding: 5px 10px;
    margin: 0 5px;
    border-radius: 3px;
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: #fff;
    color: #b2abfd; /* Set text color for the active page */
    border: 1px solid #b2abfd; 
}
.active:hover{
    background-color: #b2abfd;
    color:#fff;
}
.otherpage{
    text-decoration: none;
    padding: 5px 10px;
    margin: 0 5px;
    border-radius: 3px;
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: #b2abfd;
    color:#fff;
}
.otherpage:hover{
    background-color: #fff;
    color:#b2abfd;
    border: 1px solid #b2abfd; 
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
/* CSS for the modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height:100%;
    background-color: rgba(0, 0, 0, 0.7);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px 20px 0px 20px;
    border-radius: 5px;
    width: 80%;
    overflow:scroll;
    height: 75vh;
}

.close {
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

    </style>


<script>
       function hideModalNotification() {
            closeModalNotification.style.display = 'none';
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://localhost/sairam_lms/admin/books/unset_session_data.php', true); // Adjust the path to your PHP script
            xhr.send();
        }
</script>
     
</head>
<body>

<?php
include '../includes/header.php';
?>
<button class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900 absolute" id="showModalbutton">Show Inserted Data</button>
<div id="myModalmembers" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <div class="modal-body">
        <div class="grid grid-cols-3 gap-4 my-2">
          <div id="successCountContainer" class="bg-purple-200 p-2 ">
          <label class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300" for="">Success Counts</label>
 
            <div id="successCount">0</div>
        </div>
          <div id="errorCountContainer" class="bg-purple-200 p-2">
          <label class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300" for="">Error Counts</label>
 
          <div id="errorCount">0</div>
        </div>
        <div class="bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 text-center" id="hideModalContainer">
          View Later
        </div>
        
</div>
        
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Books Inserted!</strong>
          <span class="message-box block sm:inline" id="successBox"></span>
          
        </div>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Books Not Inserted!</strong>
          <span class="message-box block sm:inline" id="errorBox"></span>
          
        </div>
           
            <div id="pagination" class="pagination sticky bottom-0 bg-white py-5 px-[300px]">
                <button id="prevPage" class="pagination-btn px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
                

                <span class="text-sm text-gray-700 dark:text-gray-400">
      Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white"id="currentPage">1</span> of <span class="font-semibold text-gray-900 dark:text-white"id="totalPages">1</span> Entries
  </span>
                <button id="nextPage" class="pagination-btn px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_SESSION['success_badd'])){
?>
<div id="closeModalNotification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
  <strong class="font-bold">Success!</strong>
  <span class="block sm:inline"><?php
    echo $_SESSION['success_badd'];
  ?></span>
  <span class="absolute top-0 bottom-0 right-0 px-4 py-3"  onclick="hideModalNotification()">
    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
  </span>
</div>
<?php
}
?>
<?php
    if(isset($_SESSION['Failed_msg'])){
?>
<div id="closeModalNotification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
  <strong class="font-bold">Success!</strong>
  <span class="block sm:inline"><?php
    echo $_SESSION['Failed_msg'];
  ?></span>
  <span class="absolute top-0 bottom-0 right-0 px-4 py-3"  onclick="hideModalNotification()">
    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
  </span>
</div>
<?php
}
?>

<h1 class="text-center text-gray-900 font-bold text-2xl mx-auto mt-2 mb-4">Manage Members</h1>
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
 ?>
 
 
 

<div class="forms-container">
 <div class="form2 w-3/4 p-4 bg-[#EEEFFF] border rounded ">
        <div class="bg-[#EEEFFF]">
        <h2 style="font-size:25px;font-weight:bold;text-align:center;">Add Members</h2>
            <form method="POST" >
                <div class="grid lg:grid-cols-4 grid-cols-2 gap-2 m-4">
               
            <div class="col-span-1">
                <label class="block mb-1 font-semibold">Name:</label>
                <input type="text" name="name" class="w-full py-2 mb-2 border-violet-500 border rounded" required>
            </div>
            <div class="col-span-1">
                <label class="block mb-1 font-semibold">Email:</label>
                <input type="email" name="email" class="w-full py-2 mb-2 border-violet-500 border rounded" required>
            </div>
            <div class="col-span-1">
                <label class="block mb-1 font-semibold">Phone:</label>
                <input type="tel" name="phone" class="w-full py-2 mb-2 border-violet-500 border rounded" required>
            </div>
            <div class="col-span-1">
                <label class="block mb-1 font-semibold">Member Type:</label>
                <select name="member_type" class="w-full py-2 mb-2 border-violet-500 border rounded" required>
                    <option value="">Select Member Type</option>
                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
                <?php
                    $ql = "SELECT * from departments";
                    $re = $conn->query($ql);
                ?>
                </div>
                </div>
                <!-- Student Fields -->
                
                <div id="student_fields" class=" grid lg:grid-cols-4 grid-cols-2 gap-4 hidden">
                  <div class="col-span-1">
                    <label class=" mb-1 font-semibold">Department:</label>
                    <select name="student_department" class="w-full px-3 py-2 mb-2 border-violet-500 border rounded" id="department">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['department_name']; ?>"><?php echo $ro['department_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    </div>
                    <div class="col-span-1">
                    <label class=" mb-1 font-semibold">Section:</label>
                    <?php
                        $ql = "SELECT * from classes";
                        $re = $conn->query($ql);
                    ?>
                    <select name="class" class="w-full px-3 py-2 mb-2 border border-violet-500 rounded" id="sectclassion">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['class_name']; ?>"><?php echo $ro['class_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    </div>
                    <div class="col-span-1">
                    <label class=" mb-1 font-semibold">Year:</label>
                    <?php
                        $ql = "SELECT year_id from years";
                        $re = $conn->query($ql);
                    ?>
                    <select name="year" class="w-full px-3 py-2 mb-2 border border-violet-500 rounded" id="sectyearion">
                        <option value="">--Select--</option>
                        <?php while ($ro = $re->fetch_assoc()) : ?>
                            <option value="<?php echo $ro['year_id']; ?>"><?php echo $ro['year_id']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    </div>
                    <div class="col-span-1">
                    <label class=" mb-1 px-3 py-2 mb-2 font-semibold">Student ID:</label>
                    <input type="text" name="college_id" class="w-full px-3 py-2 mb-2 border border-violet-500 rounded">
                    </div>
                </div>
            
                <!-- Staff Fields -->
                <div id="staff_fields" class="hidden grid lg:grid-cols-4 grid-cols-2 gap-2 mx-auto">
                    <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Department:</label>
                    <select name="staff_department" class="w-full px-3 py-2 mb-2 border border-violet-500 rounded" id="department">
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
                        </div>
                        <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Staff ID:</label>
                    <input type="text" name="staff_college_id" class="w-full px-3 py-2 mb-2 border border-violet-500 rounded">
                        </div>
                </div>
            
                <div class="text-center">
                    <input type="submit" name="add_member" value="Add Member" class="w-1/4 px-4 py-2 mt-4 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded hover:bg-blue-600">
                </div>
            </form>

        
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
    <div class="form1">
        <h2 style="font-size:25px;font-weight:bold;text-align:center;">Import Members from Excel</h2>
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-2  p-4">
            <div class="span-col-1 ">
            <input type="file" name="excel_file" required class=" w-full border border-violet-500 bg-white p-2 rounded-l-lg ">
            </div>
            <div class="span-col-1">
            <input type="submit" name="import_members" value="Import Members " class="w-1/2 border border-violet-500 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 p-[9px] rounded-r-lg">
            </div>
        </form>
    </div>
</div>
</div>
<!-- </div> -->
<!-- Add Member Form -->



<h2 style="font-size:25px;font-weight:bold;text-align:center;">Member List</h2>
<form action="" method="GET">
<div class="grid grid-cols-1 md:grid-cols-2 bg-[#EEEFFF] lg:grid-cols-5 gap-4 mx-4 px-10 py-2">
    <!-- Add the search form with a search input box -->
    

    <div class="">
        <div class="search-form text-left">
                <label for="search">Search Members:</label>
                <input type="text" name="search" id="search" placeholder="Enter name, email, or phone" class="w-full px-2 py-2">
        </div>
    </div>

  <!-- Column 2 -->
  <div class="">
    <label for="class_filter">Filter by Class:</label>
            <select class="w-full px-2 py-2 bg-white" name="class_filter" id="class_filter">
                <option value="">All Classes</option>
                <?php
                // Query the classes from your database and populate the options
                $classQuery = "SELECT * FROM classes"; // Modify this query to match your database structure
                $classResult = $conn->query($classQuery);
                while ($classRow = $classResult->fetch_assoc()) {
                    $classId = $classRow['class_id'];
                    $className = $classRow['class_name'];
                    echo "<option value='$className'>$className</option>";
                }
                ?>
            </select>
    </div>

  <!-- Column 3 -->
  <div class=""></div>
  
  <!-- Column 4 -->
  <div class=""></div>
  
  <!-- Column 5 -->
  <div class="">            <input class="w-full px-4 py-2 mt-4 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400" type="submit" value="Apply Filter">     
</div>
  
</div>
</form>


<div class=" books flex items-center justify-center space-x-2 m-2">

<?php
// Define the number of records per page
$recordsPerPage = 10;

// Get the current page number from the URL, default to page 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $recordsPerPage;

// Check if the search and class filter form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Initialize the SQL query
    $sql = "SELECT member_id, member_type, name, email, phone, class, year, college_id, department, fine_amount FROM members 
            WHERE 1"; // Start with a basic query that selects all records

    // Check if the search query is provided
    if (isset($_GET['search'])) {
        $searchQuery = $_GET['search'];
        $sql .= " AND (name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%' OR phone LIKE '%$searchQuery%')";
    }

    // Check if the class filter is provided
    if (isset($_GET['class_filter']) && !empty($_GET['class_filter'])) {
        $classFilter = $_GET['class_filter'];
        $sql .= " AND class = '$classFilter'"; // Modify this to match your database structure
    }

    $sql .= " ORDER BY member_id DESC"; // Add the sorting condition

    // Execute the SQL query
    $result = $conn->query($sql);
}


if (!$result) {
    die('Database query error: ' . $conn->error);
}

if (isset($_GET['search'])) {
    $sql .= " LIMIT $recordsPerPage OFFSET $offset";
} else {
    $sql .= " LIMIT $offset, $recordsPerPage";
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


<div class="" style="width:99vw;max-height:40vh;overflow-y:scroll;" class="grid grid-cols-1">
    <table id="membersTable">
        <tr ">
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
                    <a href="view_member.php?member_id=<?php echo $row['member_id']; ?>" class="px-2 py-1 text-green-500 hover:bg-green-500 rounded hover:text-white">View</a> |
                    <a href="edit_member.php?member_id=<?php echo $row['member_id']; ?>" class="px-2 py-1  text-blue-600 hover:bg-blue-600 rounded  hover:text-white">Edit</a> |
                    <a class="px-2 py-1 text-red-500 hover:bg-red-500 rounded hover:text-white" href="members2.php?delete_member=<?php echo $row['member_id']; ?>" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                </td>
            </tr>
        <?php
            
        } // end while
        ?>
    </table>
    <!-- Add pagination controls -->

</div>
</div>
<div class="pagination" style="width: 100%; padding: 5px;">
    <?php if ($current_page > 1) : ?>
        <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>&class_filter=<?php echo isset($_GET['class_filter']) ? $_GET['class_filter'] : ''; ?>" class="otherpage">Previous</a>
    <?php endif; ?>

    <?php for ($i = max(1, $current_page - 2); $i <= min($totalPages, $current_page + 2); $i++) : ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>&class_filter=<?php echo isset($_GET['class_filter']) ? $_GET['class_filter'] : ''; ?>" <?php if ($i === $current_page) echo 'class="active"'; ?> class="otherpage"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($current_page < $totalPages) : ?>
        <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>&class_filter=<?php echo isset($_GET['class_filter']) ? $_GET['class_filter'] : ''; ?>" class="otherpage">Next</a>
    <?php endif; ?>
</div>


    <script>
        // Show/hide student fields based on member type selection
        var memberTypeSelect = document.querySelector('select[name="member_type"]');
        var studentFields = document.getElementById('student_fields');
        var staffFields = document.getElementById('staff_fields');

        memberTypeSelect.addEventListener('change', function() {
            if (this.value === 'student') {
                studentFields.style.display = 'grid';
                staffFields.style.display = 'none';
            } else if (this.value === 'staff') {
                studentFields.style.display = 'none';
                staffFields.style.display = 'grid';
            } else {
                studentFields.style.display = 'none';
                staffFields.style.display = 'none';
            }
        });

    </script>
 <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the modal and close button elements
        var modal = document.getElementById('myModalmembers');
        var closeModals = document.getElementById('closeModal');
        var successCountContainer = document.getElementById('successCountContainer');
        var errorCountContainer = document.getElementById('errorCountContainer');
        var successBox = document.getElementById('successBox');
        var errorBox = document.getElementById('errorBox');
        var prevPage = document.getElementById('prevPage');
        var nextPage = document.getElementById('nextPage');
        var currentPage = document.getElementById('currentPage');
        var totalPages = document.getElementById('totalPages');
        var hideModalContainer = document.getElementById('hideModalContainer');
        var showModalbutton = document.getElementById('showModalbutton');
        showModalbutton.style.display = 'none';

        
       


        <?php if(isset($_SESSION['success_message'])) { ?>
            var successMessages = <?php echo json_encode($_SESSION['success_message']); ?>;
        <?php } ?>

        <?php if(isset($_SESSION['error_message'])) { ?>
            var errorMessages = <?php echo json_encode($_SESSION['error_message']); ?>;
        <?php } ?>

        var itemsPerPage = 10;
        var currentSuccessPage = 1;
        var currentErrorPage = 1;
        var showSuccessMessages = true;

        function showModal() {
            modal.style.display = 'block';
            showModalbutton.style.display = 'none';
        }

        function hideModalContainers() {
            console.log("hello");
            modal.style.display = 'none';
            showModalbutton.style.display = 'block';
        }

        
        function closeModal() {
            console.log("geegasd");
            showModalbutton.style.display = 'none';
            hideModal();
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://localhost/sairam_lms/admin/books/unset_session_data.php', true); // Adjust the path to your PHP script
            xhr.send();
        }
        
        function hideModal() {
            modal.style.display = 'none';
        }
       
     

        function updateMessages() {
            var messagesToDisplay;
            var currentPageElement;

            if (showSuccessMessages) {
                messagesToDisplay = successMessages;
                currentPageElement = currentSuccessPage;
                successBox.style.display = 'block';
                errorBox.style.display = 'none';
            } else {
                messagesToDisplay = errorMessages;
                currentPageElement = currentErrorPage;
                successBox.style.display = 'none';
                errorBox.style.display = 'block';
            }

            var start = (currentPageElement - 1) * itemsPerPage;
            var end = start + itemsPerPage;
            var messagesPerPage = messagesToDisplay.slice(start, end);

            if (showSuccessMessages) {
                successBox.innerHTML = messagesPerPage.join('<br>');
                currentPage.innerText = currentSuccessPage;
                totalPages.innerText = Math.ceil(messagesToDisplay.length / itemsPerPage);
                successCount.innerText = messagesToDisplay.length;
            } else {
                errorBox.innerHTML = messagesPerPage.join('<br>');
                currentPage.innerText = currentErrorPage;
                totalPages.innerText = Math.ceil(messagesToDisplay.length / itemsPerPage);
                errorCount.innerText = messagesToDisplay.length;
            }
        }

        // Show success messages when clicking on the success count
        successCountContainer.addEventListener('click', function() {
            showSuccessMessages = true;
            currentSuccessPage = 1;
            currentErrorPage = 1; // Reset error page
            updateMessages();
        });

        // Show error messages when clicking on the error count
        errorCountContainer.addEventListener('click', function() {
            showSuccessMessages = false;
            currentSuccessPage = 1; // Reset success page
            currentErrorPage = 1;
            updateMessages();
        });

        // Show the modal when it's supposed to be displayed (e.g., after form submission)
        <?php if(isset($_SESSION['success_message']) || isset($_SESSION['error_message'])) { ?>
            showModal();
        <?php } ?>

        // Handle pagination buttons
        prevPage.addEventListener('click', function() {
            if (showSuccessMessages) {
                if (currentSuccessPage > 1) {
                    currentSuccessPage--;
                    updateMessages();
                }
            } else {
                if (currentErrorPage > 1) {
                    currentErrorPage--;
                    updateMessages();
                }
            }
        });

        nextPage.addEventListener('click', function() {
            var maxPage = Math.ceil(showSuccessMessages ? successMessages.length : errorMessages.length / itemsPerPage);

            if (showSuccessMessages) {
                if (currentSuccessPage < maxPage) {
                    currentSuccessPage++;
                    updateMessages();
                }
            } else {
                if (currentErrorPage < maxPage) {
                    currentErrorPage++;
                    updateMessages();
                }
            }
        });

        // Initialize messages and pagination
        updateMessages();

        // Close the modal when the close button is clicked
        hideModalContainer.onclick = hideModalContainers;
        closeModals.onclick = closeModal;
        showModalbutton.onclick = showModal;
    });
</script>


   <?php
    include '../includes/footer.php';
   ?>
 
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
