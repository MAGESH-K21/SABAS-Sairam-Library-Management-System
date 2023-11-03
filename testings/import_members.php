<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         /* CSS styles for the header */
         body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar h1 {
            margin: 0;
            padding: 0;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar li {
            margin-right: 10px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
        }

        .navbar a:hover {
            background-color: #555;
        }

        .active {
            background-color: #555;
        }
        .import-excel{
            padding: 1vw;
    font-size: 18px;
    /* border-radius: 33px; */
    background-color: darkgray;
    margin-bottom: 19px;
    font-weight: 700;
    color: black;
        }
        .import-excel-form{
            background-color: #333333;
    color: white;
    font-size: 19px;
    padding: 21px;
    font-weight: 700;
        }
    </style>
</head>
<body>
<?php include 'header.php';?>

<?php
require_once 'Classes/PHPExcel.php';
error_reporting(1);
include 'db.php';

// Function to validate member input
function validateMemberInput($name, $email, $phone, $member_type)
{
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Please enter the member name.';
    }

    if (empty($email)) {
        $errors[] = 'Please enter the email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($phone)) {
        $errors[] = 'Please enter the phone number.';
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Please enter a valid 10-digit phone number.';
    }

    if (empty($member_type)) {
        $errors[] = 'Please select the member type.';
    }

    return $errors;
}


// ...

// Import members from Excel file
if (isset($_POST['import_members'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    // Load the Excel file
    $objPHPExcel = PHPExcel_IOFactory::load($file);
    $worksheet = $objPHPExcel->getActiveSheet();

    // Get the highest row and column index
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    // Loop through each row (starting from the second row)
    for ($row = 2; $row <= $highestRow; $row++) {
        $name = $worksheet->getCell('A' . $row)->getValue();
        $email = $worksheet->getCell('B' . $row)->getValue();
        $phone = $worksheet->getCell('C' . $row)->getValue();
        $memberType = $worksheet->getCell('D' . $row)->getValue();

        // Additional fields for students
        $department = $worksheet->getCell('E' . $row)->getValue();
        $section = $worksheet->getCell('G' . $row)->getValue();
        // $class = $worksheet->getCell('F' . $row)->getValue();
        $collegeID = $worksheet->getCell('H' . $row)->getValue();
        $class = "$department "."$section";
        // Additional fields for staff
        $staffDepartment = $worksheet->getCell('E' . $row)->getValue();
        $staffCollegeID = $worksheet->getCell('F' . $row)->getValue();

        // Validate input
        $errors = validateMemberInput($name, $email, $phone, $memberType);
        if (empty($errors)) {
            if ($memberType == 'student') {
                $sql = "INSERT INTO members (name, email, phone, member_type, department,year, class, section, college_id)
                        VALUES ('$name', '$email', '$phone', '$memberType', '$department','$year', '$class', '$section', '$collegeID')";
            } elseif ($memberType == 'staff') {
                $sql = "INSERT INTO members (name, email, phone, member_type, staff_department, staff_college_id)
                        VALUES ('$name', '$email', '$phone', '$memberType', '$staffDepartment', '$staffCollegeID')";
            }

            if($conn->query($sql))
            {
                continue;
            }
              
        }

        // Handle errors
        $errors[] = 'Failed to import member from Excel row ' . $row;
    }
}
?>

<!-- Import Members Form -->
<h2>Import Members from Excel</h2>
<form method="POST" enctype="multipart/form-data" class="import-excel-form">
    <input type="file" name="excel_file" required class="import-excel">
    <input type="submit" name="import_members" value="Import Members">
</form>

</body>
</html>