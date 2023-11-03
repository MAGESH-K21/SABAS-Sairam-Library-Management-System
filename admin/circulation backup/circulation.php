<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Circulation Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form {
            margin-top: 20px;
        }

        form input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<a href="../admin/dashboard.php" class="text-center block px-4 py-2 text-gray-100 hover:bg-blue-100 bg-blue-800 hover:text-gray-900"  style=" width: 160px;font-size: 15px;
    ">Dashboard</a>


    <h1 class="text-2xl text-center font-bold m-8">Circulation Page</h1>
    <table>
        <tr>
            <th>Renew Days</th>
            <th>Fine Amount</th>
            <th>Student Books Allowed</th>
            <th>Staff Books Allowed</th>
            <th>Actions</th>
        </tr>
        <?php
        // Include the database connection file
        include 'connection/db.php';

        // Retrieve data from the settings table
        $query = "SELECT * FROM settings";
        $result = mysqli_query($conn, $query);

        // Loop through the rows and display the data
        while ($row = mysqli_fetch_assoc($result)) {
                //    $_SESSION['returndays'] = $row["return_date"] ;
                //    $_SESSION['CirculationAmount'] = $row["circulation_amount"];
                //    $_SESSION['StudentAllowed'] = $row["student_books_allowed"];
                //    $_SESSION['StaffAllowed'] = $row["staff_books_allowed"];
            echo '<tr>
                    <td>' . $row["return_date"] . '</td>
                    <td>' . $row["circulation_amount"] . '</td>
                    <td>' . $row["student_books_allowed"] . '</td>
                    <td>' . $row["staff_books_allowed"] . '</td>
                    <td><a href="?id=' . $row["id"] . '">Edit</a></td>
                  </tr>';
        }

        

       
        ?>
    </table>

    <?php
    // Check if the edit_id parameter is set in the URL
    if (isset($_GET["id"])) {
        // Retrieve the ID of the rule to be edited
        $editId = $_GET["id"];

        // Retrieve data for the selected rule from the settings table
        $query = "SELECT * FROM settings WHERE id = $editId";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        // Display the edit form
        echo '<form method="post" action="">
                <input type="hidden" name="id" value="' . $editId . '">
                Renew Days: <input type="text" name="return_date" value="' . $row["return_date"] . '"><br>
                Fine Amount: <input type="text" name="circulation_amount" value="' . $row["circulation_amount"] . '"><br>
                Student Books Allowed: <input type="text" name="student_books_allowed" value="' . $row["student_books_allowed"] . '"><br>
                Staff Books Allowed: <input type="text" name="staff_books_allowed" value="' . $row["staff_books_allowed"] . '"><br>
                <input type="submit" name="submit" value="Update">
              </form>';

        // Handle the form submission and update the data (replace this with your data update logic)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            // Retrieve and sanitize the updated values
            $renewDays = $_POST["return_date"];
            $fineAmount = $_POST["circulation_amount"];
            $studentAllowed = $_POST["student_books_allowed"];
            $staffAllowed = $_POST["staff_books_allowed"];

            // Update the data (replace this with your data update logic)
            $updateQuery = "UPDATE settings SET return_date = $renewDays, circulation_amount = $fineAmount, student_books_allowed = $studentAllowed, staff_books_allowed = $staffAllowed WHERE id = $editId";
            mysqli_query($conn, $updateQuery);

            // Redirect back to the page
            header("Location: circulation.php");
            exit();
        }
    }
    ?>

</body>
</html>

