<?php
include '../connection/db.php';

if (isset($_POST['update_class'])) {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];

    // Validate input
    if (!empty($class_name)) {
        $stmt = $conn->prepare("UPDATE classes SET class_name = ? WHERE class_id = ?");
        $stmt->bind_param("si", $class_name, $class_id);
        if ($stmt->execute()) {
            // Update successful
            header('Location: academics.php');
            exit();
        } else {
            // Error occurred during execution
            echo "Error: " . $stmt->error;
        }
    }
}

// Retrieve class details based on the ID passed from the link
if (isset($_GET['id'])) {
    $class_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap&family=Mandali&family=Signika+Negative&display=swap" rel="stylesheet">
    <!-- Add your CSS styling here -->
</head>
<body class="bg-[#EEEFFF]">
    <div class="font-['Signika Negative'] min-h-screen flex justify-center items-center" >
        <div class="bg-white p-8 rounded-lg shadow-md max-w-screen-sm w-full animation__fadeIn duration-500">
            <form method="POST" action="edit_class.php" class="grid grid-row-3 p-8 rounded-xl">
            <h1 class="text-3xl font-bold text-center pb-4"> EDIT CLASS</h1>
            <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
            <div>
                <label for="class_name">Class Name:</label>
                <input type="text" name="class_name" id="class_name" value="<?php echo $class['class_name']; ?>" required class="w-full px-8 py-2 mt-2 mb-2 border-violet-500 border rounded-xl">
            </div>
            <center>
                <input type="submit" name="update_class" value="Update Class" class="w-3/4 px-4 py-2 mt-6 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600">
            </center>
            </form>
        </div>
    </div>
</body>
</html>
