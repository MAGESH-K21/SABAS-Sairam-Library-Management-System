<?php
session_start();
error_reporting(0);
include '../connection/db.php';
$book_id = $_GET['book_id'];


$count = 0;

// Borrow a book
if (isset($_POST['issue_book'])) {
    $book_id = $_GET['book_id'];
    $member_id = $_POST['member_id'];
    date_default_timezone_set('Asia/Bangkok'); 
    $borrowed_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+7 days'));
    $fine_amount = 0;
    $fineDate = NULL;
    
    
    $sql1 = "SELECT member_type,bookscount from members where member_id='$member_id'";
    $res3 =  $conn->query($sql1);

    $sql111 = "SELECT * from settings";
    $res31=  $conn->query($sql111);
    $row_member = $res3->fetch_assoc();

    $row_member2 = $res31->fetch_assoc();
    

    if($row_member['member_type'] == 'student')
    {
        $limit = $row_member2['student_books_allowed']; 
    }
    else{
         $limit = $row_member2['staff_books_allowed']; 
    }
    
    $count = $row_member['bookscount'];
    
    if($row_member['bookscount'] < $limit)
    {
    $sql = "INSERT INTO borrowings (book_id, member_id,fine_amount, borrowed_date, due_date,status,borrowings_time)
            VALUES ($book_id, $member_id, $fine_amount, '$borrowed_date', '$due_date','Borrowed',CURDATE())";

echo $sql2 = "INSERT INTO fines (member_id,book_id, fine_amount, fine_date) VALUES ('$member_id','$book_id','$fine_amount', NULL)";
$res =  $conn->query($sql2);

if(!$res)
{
    $_SESSION['fail_fine'] =  "fail";
    header('Location:borrowings.php');
}
else{
    $count++;
   
}



    if ($conn->query($sql)) {
        // Update the book availability status
        $updateSql = "UPDATE books SET available = available-1 WHERE book_id = $book_id";
        $conn->query($updateSql);
        $updateSql2 = "UPDATE members SET bookscount = $count WHERE member_id = '$member_id'";
        $conn->query($updateSql2);
        header('Location: borrowings.php');
        exit();
    } else {
        $errors[] = 'Failed to borrow the book.';
    }
}
else{
    $errors[] = 'Failed to borrow the book Limit Exeeded.';
}
}

 // Function to sanitize user input
 function sanitize($input)
 {
     return htmlspecialchars(stripslashes(trim($input)));
 }

 // Get the search query from the form submission
 $search_query = sanitize($_POST['search_query']);

 // Prepare the SQL query to search for records matching the search query
 $query = "SELECT * FROM members WHERE college_id LIKE '%$search_query%' OR name LIKE '%$search_query%'";

 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        #sub-container{
            width:50vw; 
            margin-top:100px;
            background-color: white;
        }
        @media (max-width: 640px) {
        #sub-container {
            width:100vw;
        }
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
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../../dashboard.php'">Dashboard</a></li>

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
                        <a href="academics.php" class="dropbtn">Academics</a>
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

<td>
<div id="sub-container" class="container shadow-xl flex flex-col justify-center items-center mx-auto bg-gray-100">

<div class="headd text-gray-100 font-bold text-2xl items-center flex justify-center -t-xl w-full"style="height: 11vh; background-color: #3b82f6;">Issue To Member: </div>

<?php

// Execute the query
$result = $conn->query($query);

?>
<form method="POST" class="m-14 w-4/5">
    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
    <div class="flex">
            <label class="block m-2">Search:</label>
                <input type="text" name="search_query" id="search_query" class="w-full px-2 py-1 border border-gray-300 " placeholder="Enter a search term">
                <button type="submit" name="search" class="ml-2 bg-blue-500 text-white px-4 py-2 ">Search</button>
            </div>
    <label class="block mb-2">Member:</label>
    <?php
    if ($result->num_rows > 0) {
        // If results are found, create a dropdown with the matching college details
        echo '<h2>Search Results</h2>';
        echo '<form action="issue.php" method="post">';
        echo '<select name="member_id" class="block w-full p-2 border border-gray-300 -md focus:outline-none focus:ring-2 focus:ring-blue-500">';
    
        while ($row = $result->fetch_assoc()) {
            $member_id = $row['member_id'];
            $college_id = $row['college_id'];
            $staff_college_id = $row['staff_college_id'];
            $name = $row['name'];
            echo "<option class='w-full' value='$member_id'>$name (College ID: $college_id, Staff College ID: $staff_college_id)</option>";
        }
    
        echo '</select>';
       
    } else {
        echo '<h2>No results found.</h2>';
    }
    ?>
    
    <input type="submit" name="issue_book" value="Issue" class="mt-4 px-4 py-2 text-white bg-blue-500 -md hover:bg-blue-600">
</form>


</div>
</body>
</html>