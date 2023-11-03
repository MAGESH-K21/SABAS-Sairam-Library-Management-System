<?php
include '../connection/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    echo $id = $_POST['book_id'];

    // Check if the book can be renewed
    echo $today = date("Y-m-d");
    $sql = "SELECT * FROM borrowings WHERE book_id='$id'";
     $result = $conn->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        $row = $result->fetch_assoc();
        $end = $row['due_date'];
        if($end >= $today) {
            // Calculate the new end date for book renewal (e.g., +14 days)
            $renewal_days = 7;
            $new_end = date('Y-m-d', strtotime($end . " + $renewal_days days"));

            $sql = "UPDATE borrowings SET due_date='$new_end' , renew = renew+1 WHERE book_id='$id'";

            if ($conn->query($sql) === TRUE) {
                echo "Book renewed successfully";
            } else {
                echo "Error renewing book: " . $conn->error;
            }
        } else {
            echo "Book cannot be renewed as it has already expired";
        }
    } else {
        echo "No book found with the given ID";
    }
}

$conn->close();
?>
