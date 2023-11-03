<?php
include '../connection/db.php';

// Check if the user is authenticated
session_start();
error_reporting(0);

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}


$fineupdate_query = "SELECT * FROM borrowings";
$fineresult = $conn->query($fineupdate_query);
while($rows = $fineresult->fetch_assoc())
{
    
        if ($rows['status'] == 'Borrowed') {
            $update = 0;
            $current_borrowing_id = $rows['borrowing_id'];
            $current_book_id = $rows['book_id'];
            $current_member_id = $rows['member_id'];
            $current_due_date = $rows['due_date'];
            $returndate = date('Y-m-d');
            $fineRate = $_SESSION['CirculationAmount'];
            $fine_amount = calculateFine($current_due_date, $returndate, $fineRate);
            $updateSql = "UPDATE borrowings SET fine_amount = '$fine_amount' WHERE borrowing_id='$current_borrowing_id'";
            $conn->query($updateSql);
           
 $fine_amount;
             $updateSql1 = "UPDATE fines SET fine_amount = $fine_amount,fine_date='$returndate' WHERE book_id=$current_book_id AND member_id=$current_member_id";

        $conn->query($updateSql1);

            

        }
    
}


//pagination


// Determine the total number of records
$sqlCount = "SELECT COUNT(*) as total FROM borrowings";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalRecords = $rowCount['total'];

// Set the number of records to display per page
$recordsPerPage =100;

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

 







$count =0;
// Return a book
if (isset($_GET['return_borrowing'])) {
    echo "success";
    $borrowing_id = $_GET['return_borrowing'];
    date_default_timezone_set('Asia/Bangkok'); 
    echo $returned_date = date('Y-m-d');
    $sql1 = "SELECT member_id from borrowings where borrowing_id='$borrowing_id'";
    $res3 =  $conn->query($sql1);
    $row_member = $res3->fetch_assoc();
    $member_id = $row_member['member_id'];

    $sql59 = "SELECT bookscount from members where member_id='$member_id'";
    $res39 =  $conn->query($sql59);
    $row_member2 = $res39->fetch_assoc();
    $count = $row_member2['bookscount'];
    

   

    // Update the borrowing status to returned
    $sql = "UPDATE borrowings SET status = 'Returned' , returned_date='$returned_date' WHERE borrowing_id = '$borrowing_id'";
    $conn->query($sql);
    $count--;
    // Update the book availability status to true
    $updateSql = "UPDATE books SET available = available+1 WHERE book_id = (
        SELECT book_id FROM borrowings WHERE borrowing_id = $borrowing_id
    )";
    $conn->query($updateSql);
    $updateSql2 = "UPDATE members SET bookscount = $count WHERE member_id = $member_id";
    $conn->query($updateSql2);
    header('Location: borrowings.php');
    exit();
}
// Calculate Fines
function calculateFine($dueDate, $returnDate, $fineRate)
{
    $dueDateTime = new DateTime($dueDate);
    $returnDateTime = new DateTime($returnDate);
    $diff = $returnDateTime->diff($dueDateTime);
    $daysLate = $diff->days;
if($diff->format('%R%a')< 0)
{
    
     $fineAmount = $daysLate * $fineRate;
    $minimumFineAmount = 5;
    $fineAmount = max($fineAmount, $minimumFineAmount);
    return $fineAmount;
}
else{
    return 0;
}

// Ensure the fine amount is not less than the minimum amount (e.g., Rs. 5)

}


// Add Fine
function addFine($bookId, $fineAmount, $fineDate)
{
    include 'db.php';

    // Insert fine details into the fines table
    $sql = "INSERT INTO fines (book_id, fine_amount, fine_date) VALUES ($bookId, $fineAmount, '$fineDate')";
    $conn->query($sql);
}

// Get Fines
function getFines()
{
    include 'db.php';

    // Retrieve fine details from the fines table
    $sql = "SELECT * from fines";
    $result = $conn->query($sql);

    // Fetch the fines and return as an array
    $fines = [];
    while ($row = $result->fetch_assoc()) {
        $fines[] = $row;
    }

    return $fines;
}

// Handle form submission for calculating and adding fines
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted for fine calculation
    if (isset($_POST['calculate_fine'])) {
        $dueDate = $_POST['due_date'];
        $returnDate = $_POST['return_date'];
        $fineRate = 5; // Fine rate per day

        $fineAmount = calculateFine($dueDate, $returnDate, $fineRate);
    }

    // Check if the form is submitted for adding the fine
    if (isset($_POST['add_fine'])) {
        $bookId = $_POST['book_id'];
        $fineAmount = $_POST['fine_amount'];
        $fineDate = date('Y-m-d');

        addFine($bookId, $fineAmount, $fineDate);
    }
}
if (isset($_GET['search'])) 
{
    $search = $_GET['searchInput'];
    $sql = "SELECT borrowings.book_id, borrowings.borrowing_id, borrowings.fine_amount, books.title, members.name, borrowings.borrowed_date, borrowings.due_date, borrowings.status, borrowings.renew
    FROM borrowings
    INNER JOIN books ON borrowings.book_id = books.book_id
    INNER JOIN members ON borrowings.member_id = members.member_id
    WHERE members.member_id LIKE '%$search%' OR borrowings.borrowing_id LIKE '%$search%' OR books.book_id LIKE '%$search%'
    ORDER BY borrowings.borrowed_date DESC LIMIT $offset, $recordsPerPage";
}
 else
 {
// Retrieve all borrowings
$sql = "SELECT borrowings.book_id, borrowings.borrowing_id, borrowings.fine_amount,
books.title, members.name, borrowings.borrowed_date, borrowings.due_date,
borrowings.status, borrowings.renew
FROM borrowings
INNER JOIN books ON borrowings.book_id = books.book_id
INNER JOIN members ON borrowings.member_id = members.member_id
ORDER BY borrowings.borrowed_date DESC
LIMIT $offset, $recordsPerPage;
";
 }
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Borrowings</title>
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
  
   


    /* Style for the search form */
    form.search-form {
        margin-bottom: 20px;
        margin-left: 30px;
    }

    form.search-form label {
        font-weight: bold;
    }

    form.search-form input[type="text"] {
        width: 25vw;
        padding: 5px;
        border: 1px solid #ccc;
    }

    form.search-form input[type="submit"] {
        padding: 5px 10px;
        background-color: #4caf50;
        color: white;
        border: none;
        cursor: pointer;
    }

    form.search-form input[type="submit"]:hover {
        background-color: #45a049;
    }

   


th{
            background-color:rgb(220,220,220);
            position: sticky;
            top:0px;
            max-width:15vw;
        }
        table{
            width: 100%;
           border-collapse:separate;
            border-spacing:0px;
            min-width:max-content;
            
        }

        .outer{
            box-shadow: 0px 0px 3px black;
            margin:20px;
            max-width:100vw;
            /* position:fixed; */
            margin-left:30px;
        }
       .inner{
        max-height:350px;
        overflow-y:scroll;
        margin:20px;
       }
       th,td{
        padding:15px;
        border-bottom:1px solid gray;
       }
       

      </style>
</head>
<body>

<header class="bg-blue-500">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <h1 class="text-white text-3xl font-bold">SABAS</h1>
            <nav>
                <ul class="flex space-x-4 text-white text-lg">
                    <!-- <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="books.php">Manage Books</a></li>
                    <li><a href="add_books.php">Add Books</a></li>
                    <li><a href="members2.php">Manage Members</a></li>
                    <li><a href="borrowings.php">Manage Borrowings</a></li> -->
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../../index.php'">Home</a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="../members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
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
                            <a href="./add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="./books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="./borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
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

    <h1 style="font-size:30px;font-weight:bold;text-align:center;">Manage Borrowings</h1>
    <h1><?php if(isset( $_SESSION['fail_fine'])){
    echo $_SESSION['fail_fine'];} unset($_SESSION['fail_fine']);
    ?></h1>
    <form action="borrowings.php" method="GET" class="search-form">
    <label>Search:</label>
    <input type="text" name="searchInput" id="searchInput"  style="width:25vw" >
    <input type="submit" name="search" value="search">

</form>
    <!-- Borrowing List -->
    <!-- // Display books links -->
    <?php
echo "<div class='borrowings'>";
if ($totalPages > 1) {
   if ($currentPage > 1) {
       echo "<a href='borrowings.php?page=" . ($currentPage - 1) . "'>Previous</a>";
   }

   for ($i = 1; $i <= $totalPages; $i++) {
       if ($i == $currentPage) {
           echo "<span class='current'>$i </span>";
       } else {
           echo "<a href='borrowings.php?page=$i'>$i </a>";
       }
   }

   if ($currentPage < $totalPages) {
       echo "<a href='borrowings.php?page=" . ($currentPage + 1) . "'> Next</a>";
   }
}

echo "</div>";
?>
<div class="outer">
<div class="inner">
    <table class="borrowing-list-table">
        <tr>
            <th>Book id</th>
            <th>Borrowing ID</th>
            <th>Fine Amount</th>
            <th>book title</th>
            <th>Member Name</th>
            <th>Borrowed Date</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>renew</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_array()) { ?>
            <tr>
                <td><?php echo $row[0]; ?></td>
                <td><?php echo $row[1]; ?></td>
                <td><?php echo "Rs.".$row[2].".00"; ?></td>
                <td><?php echo $row[3]; ?></td>
                <td><?php echo $row[4]; ?></td>
                <td ><?php echo $row[5]; ?></td>
                <td contenteditable="true"><?php echo $row[6]; ?></td>
                <td><?php echo $row[7]; ?></td>
                <td><?php echo $row[8]; ?></td>
                
                <td class="action-buttons">
                    <?php if($row['status'] === 'Borrowed' && $row['fine_amount'] == 0) {?>
                        <form action="renew.php" method="post">
                            <input type="text" name="book_id" id="book_id" style="display:none;" value="<?php echo $row[0]; ?>"
">
                            <button class="renew-button" type="submit" id="renew">Renew</button>
                        </form>
                        <a class="return-button" href="borrowings.php?return_borrowing=<?php echo $row['borrowing_id']; ?>" id="return">Return</a>
                    <?php } ?>
                    <?php if ($row['status'] === 'Borrowed' && $row['fine_amount']> 0) {?>
                        <a class="return-button"  href="borrowings.php?return_borrowing=<?php echo $row['borrowing_id']; ?>" id="return">Return</a>
                    <?php }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
    <br>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
