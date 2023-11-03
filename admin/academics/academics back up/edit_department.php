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
    <!-- Add your CSS styling here -->
</head>
<body>

    <h2>Edit Department</h2>
    <form method="POST" action="edit_department.php">
        <input type="hidden" name="department_id" value="<?php echo $department['department_id']; ?>">
        <label>Department Name:</label>
        <input type="text" name="department_name" value="<?php echo $department['department_name']; ?>">
        <input type="submit" name="update_department" value="Update Department">
    </form>

</body>
</html>
