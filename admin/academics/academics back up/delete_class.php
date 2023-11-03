<?php
include '../connection/db.php';

if (isset($_GET['id'])) {
    $class_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    if ($stmt->execute()) {
        // Deletion successful
        header('Location: academics.php#class');
        exit();
    } else {
        // Error occurred during execution
        echo "Error: " . $stmt->error;
    }
}
?>
