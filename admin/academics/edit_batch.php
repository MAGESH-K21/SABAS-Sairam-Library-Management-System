<?php
include '../connection/db.php';
error_reporting(0);

// Check if the user is authenticated
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch batch data for editing
if (isset($_GET['edit_batch_id'])) {
    $edit_batch_id = $_GET['edit_batch_id'];

    // Fetch batch details
    $sql = "SELECT batch.*, courses.* FROM batch
            JOIN courses ON batch.course_id = courses.course_id
            WHERE batch.batch_id = '$edit_batch_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $batch = $result->fetch_assoc();
    } else {
        // Redirect to the batch list page or show an error message
        header('Location: academics.php#course');
        exit();
    }
} else {
    // Redirect to the batch list page
    header('Location: academics.php');
    exit();
}

if (isset($_POST['edit_batch'])) {
    $batch_id = $_POST['batch_id'];
    $batch_name = $_POST['edit_batch_name'];
    $from_year = $_POST['edit_from_year'];
    $to_year = $_POST['edit_to_year'];
    $course_id = $_POST['edit_course_id'];

    $sql = "UPDATE batch SET batch_name = '$batch_name', from_year = '$from_year', to_year = '$to_year', course_id = '$course_id' WHERE batch_id = '$batch_id'";
    if ($conn->query($sql) === TRUE) {
        // Successfully updated, redirect to batch list or display a success message
        header('Location: academics.php');
        exit();
    } else {
        // Error occurred, display the error message
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Batch</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap&family=Mandali&family=Signika+Negative&display=swap" rel="stylesheet">
    <!-- Add your CSS and JavaScript includes here -->
</head>
<body class="bg-[#EEEFFF]">

<div  class="font-['Signika Negative'] min-h-screen flex justify-center items-center" >
        <div class="bg-white p-8 rounded-lg shadow-md max-w-screen-sm w-full animation__fadeIn duration-500">       
        <form method="post" action="edit_batch.php" class="grid grid-row-6 p-8 rounded-xl" >
        <input type="hidden" name="batch_id" value="<?php echo $batch['batch_id']; ?>">
        <h1 class="text-3xl font-bold text-center pb-8"> EDIT BATCH </h1>
        <div class="mb-2">   
        <label for="edit_batch_name">Batch Name (Year):</label>
        <div>
        <input type="number" name="edit_batch_name" value="<?php echo $batch['batch_name']; ?>" required class="w-full px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        </div>
        <div class="mb-2">
        <label for="edit_from_year">From Year:</label>
        <div>
        <input type="number" name="edit_from_year" value="<?php echo $batch['from_year']; ?>" required class="w-full px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        </div>
        <div class="mb-2">
        <label for="edit_to_year">To Year:</label>
        <div>
        <input type="number" name="edit_to_year" value="<?php echo $batch['to_year']; ?>" required class="w-full px-8 py-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        </div>
        <div class="mb-2">
            <label for="edit_course_id">Course:</label>
            <div>
            <select name="edit_course_id" required class="w-full px-8 py-2 mb-2 border-violet-500 border rounded-xl" >
                <?php
                $courseQuery = "SELECT * FROM courses";
                $courseResult = mysqli_query($conn, $courseQuery);

                while ($courseRow = mysqli_fetch_assoc($courseResult)) {
                    $course_id = $courseRow['course_id'];
                    $course_name = $courseRow['course_name'];

                    // If the course ID matches, mark it as selected
                    $selected = ($batch['course_id'] == $course_id) ? 'selected' : '';

                    echo "<option value=\"$course_id\" $selected>$course_name</option>";
                }
                ?>
            </select> 
            </div>    
            </div>
            <center>
        <button type="submit" name="edit_batch" class="w-1/2 px-4 py-2 mt-8 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600">Save</button>
            </center>
    </form>
    
    </div>
</body>
</html>
