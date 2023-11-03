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
    <!-- Add your CSS styling here -->
</head>
<body>

    <h2>Edit Class</h2>
    <form method="POST" action="edit_class.php">
        <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
        <label for="class_name">Class Name:</label>
        <input type="text" name="class_name" id="class_name" value="<?php echo $class['class_name']; ?>" required>
        <input type="submit" name="update_class" value="Update Class">
    </form>

</body>
</html>
