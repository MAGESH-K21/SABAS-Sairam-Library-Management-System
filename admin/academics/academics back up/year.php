<?php
include '../connection/db.php';
// Check if the user is authenticated
// session_start();



// Create years table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS years (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    year_name INT(4) NOT NULL)";
$conn->query($sql);

// Add Year
if (isset($_POST['addYear'])) {
    $yearValue = $_POST['year_value'];
    $yearName = $_POST['year_name'];

    // Validate input
    if (!empty($yearName)) {
        $sql = "INSERT INTO years (year_value,year_name) VALUES ('$yearValue','$yearName')";
        $conn->query($sql);
    }
    header('Location: year.php');

}

// Fetch Years
$sql = "SELECT * FROM years";
$result = $conn->query($sql);
$years = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $years[] = $row;
    }
}
?>


    <h2 style="font-size:20px;font-weight:bold;text-align:center;">Add Year</h2>
    <form method="post" action="year.php">
        <label for="year_name">Year Value:</label>
        <input type="text" name="year_value" id="year_value" required>
        <label for="year_name">Year Name:</label>

        <input type="text" name="year_name" id="year_name" required>
        <input type="submit" name="addYear" value="Add Year">
    </form>
    
    <h2>Year List</h2>
    <form method="get" action="">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search">
        <input type="submit" value="Search">
    </form>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Year Value</th>
            <th>Year Name</th>
        </tr>
        <?php
            // Fetch and display the years from the database
            // Replace this section with your own PHP code for fetching years from the database
                  
            foreach ($years as $year) {
                echo '<tr>';
                echo '<td>'.$year['year_id'].'</td>';
                echo '<td>'.$year['year_name'].'</td>';
                echo '<td>'.$year['year_value'].'</td>';
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
