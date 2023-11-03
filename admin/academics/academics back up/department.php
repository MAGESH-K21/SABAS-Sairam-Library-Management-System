<?php
include '../connection/db.php';

error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Check if the user is authenticated
// session_start();

// Add Department Form
if (isset($_POST['add_department'])) {
    $department_name = $_POST['department_name'];

    // Validate input
    if (empty($department_name)) {
        $error = 'Department name is required.';
    } else {
        // Insert department into the database using prepared statement
        $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (?)");
        $stmt->bind_param("s", $department_name);
        $stmt->execute();

        // Redirect to the department list page
        header('Location: academics.php#department');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1, h2 {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            : 3px;
            width: 300px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            : 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <!-- Add Department Form -->
    <h2 class="bg-blue-500" style="font-size:20px;font-weight:bold;text-align:center;">Add Department</h2>
    <form method="POST" action="department.php">
        <label>Department Name:</label>
        <input type="text" name="department_name">
        <input type="submit" name="add_department" value="Add Department">
    </form>

    <!-- Department List -->
    <?php
    // Retrieve departments
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);

    echo "<h2 style='font-size:20px;font-weight:bold;text-align:center;'>Department List</h2>";
    echo "<table>";
    echo "<tr><th>Department ID</th><th>Department Name</th><th>Edit</th><th>Delete</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['department_id'] . "</td>";
        echo "<td>" . $row['department_name'] . "</td>";
        echo "<td><a href='../academics/edit_department.php?id=" . $row['department_id'] . "'>Edit</a></td>";
        echo "<td><a href='../academics/delete_department.php?id=" . $row['department_id'] . "' onclick='return confirm(\"Are you sure you want to delete this department?\")'>Delete</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
</body>
</html>
