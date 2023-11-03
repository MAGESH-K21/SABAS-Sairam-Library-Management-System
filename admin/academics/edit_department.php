<?php
include '../connection/db.php';

if (isset($_POST['update_department'])) {
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];

    // Validate input
    if (empty($department_name)) {
        $error = 'Department name is required.';
    } else {
        // Update department in the database using prepared statement
        $stmt = $conn->prepare("UPDATE departments SET department_name = ? WHERE department_id = ?");
        $stmt->bind_param("si", $department_name, $department_id);
        $stmt->execute();

        // Redirect to the department list page
        header('Location: academics.php');
        exit();
    }
}

// Retrieve department details based on the ID passed from the link
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM departments WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add your CSS styling here -->
</head>
<body class="bg-[#EEEFFF]">
    <div class="font-sans min-h-screen flex justify-center items-center" >
    <div class="bg-white p-8 rounded-lg shadow-md max-w-screen-sm w-full animation__fadeIn duration-500">
    <form method="POST" action="edit_department.php" class="grid grid-row-3 p-8 rounded-xl">
        <h1 class="text-3xl text-bolder text-center pb-4"> Edit Department</h1>
        <input type="hidden" name="department_id" value="<?php echo $department['department_id']; ?>">
        <div>
        <label>Department Name:</label>
        <input type="text" name="department_name" value="<?php echo $department['department_name']; ?>"  class="w-full px-8 py-2 mt-2 mb-2 border-violet-500 border rounded-xl">
        </div>
        <center>
        <input type="submit" name="update_department" value="Update Department" class="w-3/4 px-4 py-2 mt-6 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600">
        </center>
    </form>
    </div>
    </div>
</body>
</html>
