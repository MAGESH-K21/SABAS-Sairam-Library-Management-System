<?php
session_start();
error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Circulation Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *{
            margin:0px;
            padding:0px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f7f7f7;
        }

        #update-btn{
            background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        .pur{
        color: #5500A9;
        }
        .rr{
            color: #D00000;
        }

                table {
        border-collapse: collapse;
        width: 100%;
        border: none;
        background-color: #EEEFFF;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        
        }


        th, td {
        border: none;
        padding: 8px;
        text-align: center;
        }

        th {
        background-color:#EEEFFF;
        border-bottom: 1px solid #ddd;
        }

        /* Add a line between the two rows */
        tr:nth-child(even) {
        border-bottom: 1px solid #ddd;
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
        .overallform{
            background-color:#EEEFFF;
            border-radius: 10px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <?php
    include 'includes/header.php';
    ?>


    <h1 class="text-2xl  m-8 font-family: Signika Negative;">Settings</h1>
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
                    <td><a href="?id=' . $row["id"] . '"style=color:#FF0000;font-weight:bold;">Edit</a></td>
                  </tr>';
        }
        ?>
    </table>

    <?php
if (isset($_GET["id"])) {
    $editId = $_GET["id"];
    $query = "SELECT * FROM settings WHERE id = $editId";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $renewDate = isset($row["return_date"]) ? $row["return_date"] : "";
        $fineAmount = isset($row["circulation_amount"]) ? $row["circulation_amount"] : "";
        $studentAllowed = isset($row["student_books_allowed"]) ? $row["student_books_allowed"] : "";
        $staffAllowed = isset($row["staff_books_allowed"]) ? $row["staff_books_allowed"] : "";


        echo '<div class="overallform"><form method="post" action=""><br><br>
                <input type="hidden" name="id" value="' . $editId . '">
                <div style="display: flex; justify-content: space-between;">
                    <div style="width: 48%;padding-left: 200px;">
                        Renew Days: <input type="text" name="return_date" value="' . $renewDate . '" style="border-radius: 29px; padding: 8px; width: 100%; border: 1px solid #ddd; margin-bottom: 10px;"><br>
                        Fine Amount: <input type="text" name="circulation_amount" value="' . $fineAmount . '" style="border-radius: 29px; padding: 8px; width: 100%; border: 1px solid #ddd; margin-bottom: 10px;"><br>
                    </div>
                    <div style="width: 48%;padding-right: 200px;">
                        Student Books Allowed: <input type="text" name="student_books_allowed" value="' . $studentAllowed . '" style="border-radius: 29px; padding: 8px; width: 100%; border: 1px solid #ddd; margin-bottom: 10px;"><br>
                        Staff Books Allowed: <input type="text" name="staff_books_allowed" value="' . $staffAllowed . '" style="border-radius: 29px; padding: 8px; width: 100%; border: 1px solid #ddd; margin-bottom: 10px;"><br>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <input id="update-btn" type="submit" name="submit" value="Update" class="bg-blue-800 text-white px-4 py-2 rounded hover:bg-blue-600" style="width: 480px; height: 50px; border-radius:15px;">
                </div>
              </form>
              <br><br></div>';


        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            // Sanitize and validate input
            $renewDays = mysqli_real_escape_string($conn, $_POST["return_date"]);
            $fineAmount = mysqli_real_escape_string($conn, $_POST["circulation_amount"]);
            $studentAllowed = mysqli_real_escape_string($conn, $_POST["student_books_allowed"]);
            $staffAllowed = mysqli_real_escape_string($conn, $_POST["staff_books_allowed"]);

            // Update data
            $updateQuery = "UPDATE settings SET return_date = '$renewDays', circulation_amount = '$fineAmount', student_books_allowed = '$studentAllowed', staff_books_allowed = '$staffAllowed' WHERE id = $editId";
            mysqli_query($conn, $updateQuery);

            // Redirect back to the page
            header("Location: circulation.php");
            exit();
        }
    } else {
        echo "Record not found.";
    }
}
?>


</body>
</html>

