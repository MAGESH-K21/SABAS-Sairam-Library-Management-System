<?php
// Include the database conn file (db.php)
require_once '../admin/connection/db.php';
error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Report</title>
    <link href="https://cdn.tailwindcss.com/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        </style>
</head>
<body class="bg-gray-100">
  
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
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../index.php'">Home</a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../admin/dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="../admin/members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Members</a>
                            <a href="../testings/reports.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Reports</a>
                            <a href="../admin/circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Settings</a>
                           
                        </div>
                       
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="#" class="dropbtn">books</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../admin/books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="../admin/books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="../admin/books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="../admin/academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../admin/academics/academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="../admin/academics/academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="../admin/academics/academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="../admin/academics/academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Course</a>
                        </div>
                    </li>

                
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-gray-900 bg-blue-900" ><a href="../admin/logout.php">Logout</a></li>

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
   <center>
   <h2 class="text-2xl font-bold mb-4">Generate Library Report</h2>
   </center> 
    <div class="container mx-auto">
        <form method="post" action="" wi>
            <label for="from_date" class="block mb-2">From Date:</label>
            <input type="date" id="from_date" name="from_date" required class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <br><br>
            <label for="to_date" class="block mb-2">To Date:</label>
            <input type="date" id="to_date" name="to_date" required class="block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <br><br>
            <input type="submit" name="submit" value="Generate Report" class="mt-4 px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
        </form>
    </div>
    <div class="table flex justify-center items-center mx-auto">
        <?php

// Function to fetch data from the database based on the date range
function generateReport($fromDate, $toDate)
{
    include '../admin/connection/db.php';

    // Format the dates to match the database date format (assuming 'Y-m-d' format)
    $fromDate = date('Y-m-d', strtotime($fromDate));
    $toDate = date('Y-m-d', strtotime($toDate));

    // Query to fetch data from the database based on the date range
    $query = "SELECT members.name, books.title, borrowings.borrowed_date, borrowings.returned_date, fines.fine_amount
    FROM members
    LEFT JOIN borrowings ON members.member_id = borrowings.member_id
    LEFT JOIN books ON borrowings.book_id = books.book_id
    LEFT JOIN fines ON members.member_id = fines.member_id AND borrowings.book_id = fines.book_id
    WHERE borrowings.borrowed_date >= '$fromDate' AND (borrowings.returned_date <= '$toDate' OR borrowings.returned_date IS NULL)";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query executed successfully
    if ($result) {
        // Fetch the data as an associative array
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Print the report data in a table format
        echo '<table class="table-auto" style="border-collapse: collapse; width: 100%;">';
        echo '<tr>';
        echo '<th style="border: 1px solid #000; padding: 8px;">Member Name</th>';
        echo '<th style="border: 1px solid #000; padding: 8px;">Book Title</th>';
        echo '<th style="border: 1px solid #000; padding: 8px;">Borrowed Date</th>';
        echo '<th style="border: 1px solid #000; padding: 8px;">Returned Date</th>';
        echo '<th style="border: 1px solid #000; padding: 8px;">Fine Amount</th>';
        echo '</tr>';

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td style="border: 1px solid #000; padding: 8px;">' . $row['name'] . '</td>';
            echo '<td style="border: 1px solid #000; padding: 8px;">' . $row['title'] . '</td>';
            echo '<td style="border: 1px solid #000; padding: 8px;">' . $row['borrowed_date'] . '</td>';
            echo '<td style="border: 1px solid #000; padding: 8px;">' . $row['returned_date'] . '</td>';
            echo '<td style="border: 1px solid #000; padding: 8px;">' . $row['fine_amount'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        // Display the Export to CSV button
        echo '<form method="post" action="export.php">';
        echo '<input type="hidden" name="csv_data" value="' . htmlspecialchars(json_encode($data)) . '">';
        echo '<center><input type="submit" class="bg-green-500 m-6 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded" name="export_csv" value="Export to CSV"></center>';
        echo '</form>';
    } else {
        // Handle the query error
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database conn
    
    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];

    // Generate the report
    generateReport($fromDate, $toDate);
}

        ?>
    </div>
</body>
</html>


