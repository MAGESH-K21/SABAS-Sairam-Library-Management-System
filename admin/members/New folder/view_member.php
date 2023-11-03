<?php
// Assuming you have a MySQL database connection
session_start();
include '../connection/db.php';

if(isset($_GET['member_id']))
{
    $id = $_GET['member_id'];
}
// Fetch student details
$studentQuery = "SELECT * FROM members WHERE member_id = '$id'"; // Replace with the actual SQL query
$studentResult = mysqli_query($conn, $studentQuery);
$studentData = mysqli_fetch_assoc($studentResult);

// Fetch borrowing history data
$borrowingHistoryQuery = "SELECT * FROM borrowings WHERE member_id = '$id'"; // Replace with the actual SQL query
$borrowingHistoryResult = mysqli_query($conn, $borrowingHistoryQuery);

// Fetch fines and penalties data
$finesQuery = "SELECT * FROM borrowings WHERE member_id = '$id'"; // Replace with the actual SQL query
$finesResult = mysqli_query($conn, $finesQuery);

$query = "SELECT * FROM settings";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #333;
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .details {
            margin-left: 20px;
        }

        .strong {
            font-weight: bold;
        }

        .highlight {
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007BFF;
            color: #fff;
            font-size: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
    <a href="../dashboard.php" class="btn text-center block px-4 py-2 text-gray-100 hover:bg-blue-100 bg-blue-800 hover:text-gray-900"  style="text-align:center; width: 160px;font-size: 15px;
    ">Dashboard</a>
        <h1>Student Details</h1>
        <div class="student-info">
            <!-- <div class="student-photo">
                <img src="<?php echo $studentData['photo_url']; ?>" alt="Student Photo">
            </div> -->

            <?php
            if($studentData['bookscount'] == NULL)
            {
                $studentData['bookscount'] = 0;
            }
               $CURRENTELIGIBLE =  $row["student_books_allowed"] - $studentData['bookscount'];
            ?>
            <div class="details">
                <h2>Student ID: <span class="highlight"><?php echo $studentData['member_id']; ?></span></h2>
                <p><span class="strong">Total Permissible Books:</span> <?php echo $row["student_books_allowed"]; ?></p>
                <p><span class="strong">currently Eligible Books:</span> <?php echo  $CURRENTELIGIBLE; ?></p>
                <p><span class="strong">Borrowed Books:</span> <?php  
                
                echo  $studentData['bookscount']; ?></p>
                <p><span class="strong">Name:</span> <?php echo $studentData['name']; ?></p>
                <p><span class="strong">Email:</span> <?php echo $studentData['member_type']; ?></p>
                <p><span class="strong">department:</span> <?php echo $studentData['department']; ?></p>
                <p><span class="strong">class:</span> <?php echo $studentData['class']; ?></p>
                <p><span class="strong">email:</span> <?php echo $studentData['email']; ?></p>
                <p><span class="strong">phone:</span> <?php echo $studentData['phone']; ?></p>
                <p><span class="strong">college_id:</span> <?php echo $studentData['college_id']; ?></p>
                <p><span class="strong">Total Fine:</span> <?php echo $studentData['fine_amount']; ?></p>
                <!-- Add more student details here -->
                <h3>Borrowing History:</h3>
                <table>
                    <tr>
                        <th>Book</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                    </tr>
                    <?php while ($borrowingRow = mysqli_fetch_assoc($borrowingHistoryResult)) { ?>
                        <tr>
                            <td><?php echo $borrowingRow['book_id']; ?></td>
                            <td><?php echo $borrowingRow['due_date']; ?></td>
                            <td><?php echo $borrowingRow['returned_date']; ?></td>
                            <td><?php echo $borrowingRow['fine_amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <h3>Fines and Penalties:</h3>
                <table>
                    <tr>
                        <th>Borrowed Date</th>
                        <th>Returned Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>book id</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($finesRow = mysqli_fetch_assoc($finesResult)) { ?>
                        <tr>
                            <td><?php echo $finesRow['borrowed_date']; ?></td>
                            <td><?php echo $finesRow['returned_date']; ?></td>
                            <td><?php echo $finesRow['due_date']; ?></td>
                            <td><?php echo $finesRow['fine_amount']; ?></td>
                            <td><?php echo $finesRow['book_id']; ?></td>
                            <td><?php echo $finesRow['status']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
               
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>