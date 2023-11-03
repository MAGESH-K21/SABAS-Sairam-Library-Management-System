<?php

if (isset($_GET['export_csv'])) {
    // Get the CSV data from the form submission
    $csvData = $_GET['csv_data'];

    // Convert the JSON-encoded data to an array
    $data = json_decode($csvData, true);

    // Define the CSV filename
    $filename = 'library_report.csv';

    // Set the CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Create a file pointer
    $output = fopen('php://output', 'w');

    // Write the CSV headers
    fputcsv($output, array('Member Name', 'Book Title', 'Borrowed Date', 'Returned Date', 'Fine Amount'));

    // Write the CSV rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);

    // Stop the script from further execution
    exit();
}

?>