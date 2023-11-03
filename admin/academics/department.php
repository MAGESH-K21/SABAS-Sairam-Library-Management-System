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

        table {
            width: 100%;
            border-radius: 30px; 
        }
        th{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background: linear-gradient(269deg, #B3A3FE 22.86%, #B1B9FF 91.9%);
            

        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background: #EEEFFF;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <!-- Add Department Form -->
    <h1 class="text-2xl text-bold">Department</h1>
    <form method="POST" action="department.php" class="grid grid-cols-2 bg-[#EEEFFF] p-8 rounded-xl">
    <div>
        <label class="text-lg">Department Name:</label>
        <input type="text" name="department_name" class="w-3/4 px-8 py-2 mb-2 border-violet-500 border rounded-xl" placeholder="Department Name">
    </div>
    <div>
        <input type="submit" name="add_department" value="Add Department" class="w-1/2 px-4 py-2 mt-8 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white font-semibold rounded-xl hover:bg-blue-600">
    </div>
    </form>
    <?php
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);

    echo "<h2 style='font-size:20px;font-weight:bold;text-align:center;'>Department List</h2>";
    echo "<table>";
    echo "<tr><th class=''>Department ID</th><th>Department Name</th><th>Edit</th><th>Delete</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['department_id'] . "</td>";
        echo "<td>" . $row['department_name'] . "</td>";
        echo "<td><a href='../academics/edit_department.php?id=" . $row['department_id'] . "' class='text-green-500'>Edit</a></td>";
        echo "<td><a href='../academics/delete_department.php?id=" . $row['department_id'] . "' class='text-red-500' onclick='return confirm(\"Are you sure you want to delete this department?\")'>Delete</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
</body>
</html>
