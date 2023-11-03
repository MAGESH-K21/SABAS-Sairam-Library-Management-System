<?php
include '../connection/db.php';
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
    <title>Academics</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

<style>
   /* Overlay background */
   .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        z-index: 10000;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }

    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        color: #aaa;
        cursor: pointer;
    }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        /* body{
            margin: 0;
            padding: 0;
        } */
    </style>
    
</head>
<body style="margin: 0px;padding: 0px;">
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
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../../admin/dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="../../admin/members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
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
                            <a href="../../admin/books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="../../admin/books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="../../admin/books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Course</a>
                        </div>
                    </li>

                
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-gray-900 bg-blue-900" ><a href="../logout.php">Logout</a></li>

                    <?php
                        }
                        else{

                        ?>
                         <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a href="../admin/login.php">Admin Login</a></li>
                         <?php

                        }
                        ?>
                </ul>
            </nav>
        </div>
    </header>



    <h1  style="font-size:25px;font-weight:bold;text-align:center;" class="bg-blue-500 text-4xl font-bold mt-4">Academics</h1>

<div class="container mx-auto p-4" style="width: 90vw; padding-left: 5vw">
    <div id="department" class="mb-4">
        <?php include 'department.php'; ?>
    </div>
    <div id="class" class="mb-4">
        <?php include 'class.php'; ?>
    </div>
    <div id="year" class="mb-4">
        <?php include 'year.php'; ?>
    </div>
    <div id="course" class="mb-4">
        <?php include 'course.php'; ?>
    </div>
    <div id="batch" class="mb-4">
    <?php
// Include your database connection here

// Handle batch addition
if (isset($_POST['add_batch'])) {
    $batch_name = $_POST['batch_name'];
    $from_year = $_POST['from_year'];
    $to_year = $_POST['to_year'];
    $course_id = $_POST['course_id'];
    
    $sql = "INSERT INTO batch (batch_name, from_year, to_year, course_id) VALUES ('$batch_name', '$from_year', '$to_year', '$course_id')";
    $conn->query($sql);
}

// Handle batch deletion
if (isset($_GET['delete_batch'])) {
    $batch_id = $_GET['delete_batch'];
    $sql = "DELETE FROM batch WHERE batch_id = '$batch_id'";
    $conn->query($sql);
}

// Handle batch editing
if (isset($_POST['edit_batch'])) {
    $batch_id = $_POST['batch_id'];
    $batch_name = $_POST['edit_batch_name'];
    $from_year = $_POST['edit_from_year'];
    $to_year = $_POST['edit_to_year'];
    $course_id = $_POST['edit_course_id'];
    
    $sql = "UPDATE batch SET batch_name = '$batch_name', from_year = '$from_year', to_year = '$to_year', course_id = '$course_id' WHERE batch_id = '$batch_id'";
    $conn->query($sql);
}

// Fetch batch data
$sql = "SELECT * FROM batch";
$result = $conn->query($sql);
?>
    <h2 style="background-color: #333; color: #fff; text-align: center; padding: 20px;">Batch Management</h2>
    
    <!-- Batch Addition Form -->
    <h3 style="margin-top: 20px;">Add Batch</h3>
    <form method="post" action="" style="margin-bottom: 20px;">
        <label for="batch_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Batch Name (Year):</label>
        <input type="number" name="batch_name" required style="padding: 8px; margin-bottom: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        <br>
        <label for="from_year" style="display: block; margin-bottom: 5px; font-weight: bold;">From Year:</label>
        <input type="year" name="from_year" required id="from_year" onchange="updateToYear();" style="padding: 8px; margin-bottom: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
        <br>
        <label for="to_year" style="display: block; margin-bottom: 5px; font-weight: bold;">To Year:</label>
        <input type="year" name="to_year" required id="to_year" style="padding: 8px; margin-bottom: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
      
        <br>
        <label for="course_id" style="display: block; margin-bottom: 5px; font-weight: bold;">Course:</label>
<select name="course_id" required onchange="updateToYear();" style="padding: 8px; margin-bottom: 10px; width: 100%; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
    <?php
    // Include the database connection
    include '../admin/connection/db.php';

    // Query to retrieve courses
    $courseQuery = "SELECT * FROM courses";
    $courseResult = mysqli_query($conn, $courseQuery);

    // Populate dropdown options with courses
    while ($courseRow = mysqli_fetch_assoc($courseResult)) {
        $course_id = $courseRow['course_id'];
        $course_name = $courseRow['course_name'];
        $course_duration = $courseRow['totallyears']; // Replace with the actual column name

        echo "<option value=\"$course_id\" data-duration=\"$course_duration\">$course_name</option>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
        </select>
        
        <script>
        function updateToYear() {
            const fromYearInput = document.getElementById("from_year");
            const toYearInput = document.getElementById("to_year");
            const courseSelect = document.getElementsByName("course_id")[0];

            const selectedOption = courseSelect.options[courseSelect.selectedIndex];
            const courseDuration = selectedOption.getAttribute("data-duration");

            if (courseDuration) {
                const fromYear = parseInt(fromYearInput.value);
                const toYear = fromYear + parseInt(courseDuration);
                toYearInput.value = toYear;
            }
        }
    </script>

        
        <br>
        <button type="submit" name="add_batch" style="background-color: #007bff; color: #fff; border: none; cursor: pointer;">Add Batch</button>
    </form>
    
    
    <!-- Batch Table -->
    <h3>Batch List</h3>
    <h3 style="margin-top: 20px;">Batch List</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; background-color: #fff;">
        <tr>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; background-color: #f4f4f4;">Batch Name</th>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; background-color: #f4f4f4;">From Year</th>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; background-color: #f4f4f4;">To Year</th>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; background-color: #f4f4f4;">Course ID</th>
            <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd; background-color: #f4f4f4;">Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['batch_name']; ?></td>
                <td><?php echo $row['from_year']; ?></td>
                <td><?php echo $row['to_year']; ?></td>
                <td><?php echo $row['course_id']; ?></td>
                <td>
                <a href="?delete_batch=<?php echo $row['batch_id']; ?>">Delete</a>
                <a href="edit_batch.php?edit_batch_id=<?php echo $row['batch_id']; ?>">Edit</a>


                </td>
            </tr>
            
        <?php endwhile; ?>
    </table>
        </div>

</div>

<?php include 'footer.php';?>

</body>
</html>
