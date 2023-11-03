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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap&family=Mandali&family=Signika+Negative&display=swap" rel="stylesheet">
</head>
<body class="bg-[#EEEFFF]">
<div class="font-['Signika Negative'] min-h-screen flex justify-center items-center" >
        <div class="bg-white p-8 rounded-lg shadow-md max-w-screen-sm w-full animation__fadeIn duration-500">
    <form method="POST" action="course_edit.php?course_id=<?php echo $course['course_id']; ?>" class="grid grid-row-3 p-8 rounded-xl">
    <h1 class="text-3xl font-bold text-center pb-8"> EDIT COURSE</h1>
    <div>
        <label>Course Name:</label>
        <input type="text" name="course_name" value="<?php echo $course['course_name']; ?>" class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl">
    </div>
    <center>
        <input type="submit" name="update_course" value="Update Course" class="w-1/2 px-4 py-2 mt-8 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600" >
</center>
    </form>
</div>
</div>
</body>
</html>
