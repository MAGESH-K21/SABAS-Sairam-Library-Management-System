<?php
include '../connection/db.php';
// Check if the user is authenticated
// session_start();

// include 'db.php';
error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Create classes table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS classes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Add Class
if (isset($_POST['addclass'])) {
    echo "succews";
    echo $className = $_POST['class_name'];

    // Validate input
    if (!empty($className)) {
        $stmt = $conn->prepare("INSERT INTO classes (class_name) VALUES (?)");
        $stmt->bind_param("s", $className);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            // Insertion successful
            echo "Class added successfully.";
        } else {
            // Error occurred during execution
            echo "Error: " . $stmt->error;
        }

    }
    header('Location:academics.php#class');
}

// Fetch Classes
$sql = "SELECT * FROM classes";
$result = $conn->query($sql);
$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

?>

    <h2 class="bg-blue-500" style="font-size:20px;font-weight:bold;text-align:center;">Add Class</h2>
    <form method="post" action="class.php">
        <label for="class_name">Class Name:</label>
        <input type="text" name="class_name" id="class_name" required>
        <input type="submit" name="addclass" value="Add Class">
    </form>
    
    <h2>Class List</h2>
    <form method="get" action="">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Search">
    </form>
    
    <table>
    <tr>
        <th>ID</th>
        <th>Class Name</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php
        // Fetch and display the classes from the database
        foreach ($classes as $class) {
            echo '<tr>';
            echo '<td>'.$class['class_id'].'</td>';
            echo '<td>'.$class['class_name'].'</td>';
            echo '<td><a href="edit_class.php?id='.$class['class_id'].'">Edit</a></td>';
            echo '<td><a href="delete_class.php?id='.$class['class_id'].'" onclick="return confirm(\'Are you sure you want to delete this class?\')">Delete</a></td>';
            echo '</tr>';
        }
    ?>
</table>
    
    <script>
        // JavaScript code for sorting the table columns
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector('table');
            switching = true;
            
            while (switching) {
                switching = false;
                rows = table.rows;
                
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName('td')[columnIndex];
                    y = rows[i + 1].getElementsByTagName('td')[columnIndex];
                    
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
                
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
    </script>