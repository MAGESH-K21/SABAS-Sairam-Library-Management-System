<?php
include '../connection/db.php';
// Check if the user is authenticated
session_start();


error_reporting(0);
// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}



// Add Department Form
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];

    // Validate input
    if (empty($course_name)) {
        $error = 'Course name is required.';
    } else {
        // Insert department into the database
        $sql = "INSERT INTO courses (course_name) VALUES ('$course_name')";
        $conn->query($sql);

        // Redirect to the department list page
        header('Location: academics.php#course');
        exit();
    }
}
?>

    <h1 style="font-size:30px;font-weight:bold;text-align:center;">Course Management</h1>

    <!-- Add Department Form -->
    <h2 style="font-size:20px;font-weight:bold;text-align:center;">Add Course</h2>
    <form method="POST" action="course.php" class="grid grid-cols-2 bg-[#EEEFFF] p-8 rounded-xl">
        <div>
        <label>Course Name:</label>
        <input type="text" name="course_name"class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl" placeholder="Course Name">
    </div>

        <div>
        <input type="submit" name="add_course" value="Add Course" class="w-1/2 px-4 py-2 mt-8 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600">
        </div>
    </form>
    <h2 style="font-size:20px;font-weight:bold;text-align:center;">Course List</h2>

    <!-- Department List -->
    <?php
    // Retrieve departments
    $sql = "SELECT * FROM courses";
    $result = $conn->query($sql);

    // Display the department list
   
    echo "<table>";
    echo "<tr><th>Course ID</th>
    <th>Course Name</th><th>Actions</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['course_id'] . "</td>";
        echo "<td>" . $row['course_name'] . "</td>";
        ?>
        <td>
<a href="../academics/course_edit.php?course_id=<?php echo $row['course_id'];?>" class="text-green-500">Edit</a> |
<a href="../academics/course_delete.php?course_id=<?php echo $row['course_id'];?>" class="text-red-500">Delete</a>
<?php
        echo "</tr>";
    }

    echo "</table>";
    ?>


