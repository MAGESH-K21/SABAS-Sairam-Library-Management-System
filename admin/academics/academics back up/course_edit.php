<?php
include '../connection/db.php';
error_reporting(0);

// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Get the course ID from the URL parameter

echo $courseId = $_GET['course_id'];

// Fetch the course details
$sql = "SELECT * FROM courses WHERE course_id = '$courseId'";
$result = $conn->query($sql);

// Fetch the course data
$course = $result->fetch_assoc();

// Update Course
if (isset($_POST['update_course'])) {
   echo $courseName = $_POST['course_name'];

    // Validate input
    if (empty($courseName)) {
        $error = 'Course name is required.';
    } else {
        // Update course in the database
        $sql = "UPDATE courses SET course_name = '$courseName' WHERE course_id = '$courseId'";
        $result = $conn->query($sql);
        if(!result)
        {
            echo "fail";
        }
        else
        {
            header('Location:academics.php #course');
        }
        // Redirect to the course list page
           }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Management - Edit Course</title>
    <style>
        /* Styles for the form and table are omitted for brevity */
    </style>
</head>
<body>
    <h1 style="font-size:30px;font-weight:bold;text-align:center;">Course Management - Edit Course</h1>

    <!-- Edit Course Form -->
    <h2 style="font-size:20px;font-weight:bold;text-align:center;">Edit Course</h2>
    <form method="POST" action="course_edit.php?course_id=<?php echo $course['course_id']; ?>">

        <label>Course Name:</label>
        <input type="text" name="course_name" value="<?php echo $course['course_name']; ?>">
        
        <input type="submit" name="update_course" value="Update Course">
    </form>
</body>
</html>
