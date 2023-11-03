<?php
require_once('../db.php');
require_once('fpdf/fpdf.php');

function generateReport($fromDate, $toDate)
{
    include('../db.php');
    $query = "SELECT members.name, books.title, borrowings.borrowed_date, borrowings.returned_date, fines.fine_amount
              FROM members
              LEFT JOIN borrowings ON members.member_id = borrowings.member_id
              LEFT JOIN books ON borrowings.book_id = books.book_id
              LEFT JOIN fines ON members.member_id = fines.member_id AND borrowings.book_id = fines.book_id
              WHERE borrowings.borrowed_date >= '$fromDate' AND (borrowings.returned_date <= '$toDate' OR borrowings.returned_date IS NULL)";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    mysqli_free_result($result);
    mysqli_close($conn);

    // Generate the PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Member Name', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Book Title', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Borrowed Date', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Returned Date', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Fine Amount', 1, 1, 'C');

    // Add table rows
    $pdf->SetFont('Arial', '', 12);
    foreach ($data as $row) {
        $pdf->Cell(40, 10, $row['name'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['title'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['borrowed_date'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['returned_date'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['fine_amount'], 1, 1, 'C');
    }

    // Output the PDF as a download
    $pdf->Output('library_report.pdf', 'D');
}

if (isset($_POST['generate_report'])) {
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];

    generateReport($fromDate, $toDate);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Library Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Generate Library Report</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>From Date:</label>
                <input type="text" name="from_date" placeholder="YYYY-MM-DD" required>
            </div>
            <div class="form-group">
                <label>To Date:</label>
                <input type="text" name="to_date" placeholder="YYYY-MM-DD" required>
            </div>
            <div class="form-group">
                <input type="submit" name="generate_report" value="Generate Report">
            </div>
        </form>
    </div>
</body>

</html>
