<?php
session_start();
include '../connection/db.php';

if (isset($_GET['member_id'])) {
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

        .student-info{            
            background-color: #eeefff;
            border-radius: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        .details-left {
            flex: 1; /* Take up half of the available space */
            flex-direction: column;
            justify-content: center; /* Center-align elements vertically */
            align-items: center; 
        }

        .details-right {
            flex: 1;
            align-items: center;
            /* Take up half of the available space */
            flex-direction: column;
            justify-content: center; /* Center-align elements vertically */
            align-items: center; 
        }

        .details {
            margin-left: 20px;
        }

        .strong {
            font-weight: bold;
        }

        .highlight {
            background: -webkit-linear-gradient( #5500A9, #9B68E0, #CFC2FF);
            background-clip: none;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        table {
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            
            background-color: #EEEFFF;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #B2ABFD;
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

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }

        .pur {
            color: #5500A9;
        }

        .rr {
            color: #D00000;
        }
        @media screen and (max-width: 768px) {
            .student-info {
                flex-direction: column; /* Change flex direction to stack columns vertically */
            }

            .details-left,
            .details-right {
                flex: 1; /* Each column takes full width on small screens */
            }
        }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mx-auto my-24">
        <div class="headd" style="background-color:white;box-shadow:none">
        <h1 style="font-size:30px;font-weight:medium">Student Details</h1>
        </div>
        <div class="student-info">
            <div class="details-left">
                <?php
                if ($studentData['bookscount'] == NULL) {
                    $studentData['bookscount'] = 0;
                }
                $CURRENTELIGIBLE =  $row["student_books_allowed"] - $studentData['bookscount'];
                ?>
                <p style="font-weight:500;font-size:25px;"> <span class="pl-4 highlight" >Student ID: </span><?php echo $studentData['member_id']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Name:</span> <?php echo $studentData['name']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Email:</span> <?php echo $studentData['member_type']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Department:</span> <?php echo $studentData['department']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Class:</span> <?php echo $studentData['class']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Email:</span> <?php echo $studentData['email']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Phone:</span> <?php echo $studentData['phone']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">College ID:</span> <?php echo $studentData['college_id']; ?></p>
            </div>
            <div class="details-right">
                <p style="font-weight:600"><span class="highlight strong p-4">Total Permissible Books:</span> <?php echo $row["student_books_allowed"]; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Currently Eligible Books:</span> <?php echo  $CURRENTELIGIBLE; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Borrowed Books:</span> <?php echo  $studentData['bookscount']; ?></p>
                <p style="font-weight:600"><span class="highlight strong p-4">Total Fine:</span> <?php echo $studentData['fine_amount']; ?></p>
            </div>
        </div>


    <h1 style="font-size:30px;font-weight:medium">Borrowing History:</h1>
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
    <h1 style="font-size:30px;font-weight:medium">Fines and Penalties:</h1>
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
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
