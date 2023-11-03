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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Signika+Negative:wght@300&display=swap" rel="stylesheet">
    <style>
`   .body{
        font-family: 'Signika Negative', sans-serif !important;
    }
   
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

<?php
include '../includes/header.php';
?>

<h1  style="font-size:25px;font-weight:bold;text-align:center;" class="text-4xl font-bold mt-4">Academics</h1>

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
    <h1 style="font-size:30px;font-weight:bold;text-align:center;">Batch Management</h1>
    
    <!-- Batch Addition Form -->
    <h1 class="text-2xl text-bold">Add Batch</h1>
    <form method="POST" action="" class="bg-[#EEEFFF] rounded-xl">
        <div class="grid grid-cols-2 p-8">
        <div>
        <label for="batch_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Batch Name (Year):</label>
        <input type="number" name="batch_name" required  class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        
        <div>
        <label for="from_year" style="display: block; margin-bottom: 5px; font-weight: bold;">From Year:</label>
        <input type="year" name="from_year" required id="from_year" onchange="updateToYear();"  class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        
        <div>
        <label for="to_year" style="display: block; margin-bottom: 5px; font-weight: bold;">To Year:</label>
        <input type="year" name="to_year" required id="to_year"  class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        
        <div>
        <label for="course_id" style="display: block; margin-bottom: 5px; font-weight: bold;">Course:</label>
<select name="course_id" required onchange="updateToYear();"  class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl">
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
</div>
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

        
</div >
        <center>
        <button type="submit" name="add_batch" class="w-1/2 px-4 py-2 mt-8 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600 mb-10">Add Batch</button>
        </center>
    </form>
    
    
    <!-- Batch Table -->
    <h2 style='font-size:20px;font-weight:bold;text-align:center;'>Batch List</h2>
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
                <a href="edit_batch.php?edit_batch_id=<?php echo $row['batch_id']; ?>" class="text-green-500">Edit</a> |
                <a href="?delete_batch=<?php echo $row['batch_id']; ?>" class="text-red-500">Delete</a>
                </td>
            </tr>
            
        <?php endwhile; ?>
    </table>
        </div>

</div>

<?php include 'footer.php';?>

</body>
</html>
