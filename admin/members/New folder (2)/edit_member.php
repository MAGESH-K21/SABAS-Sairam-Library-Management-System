<?php
include '../connection/db.php';

// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

$errors = array();
$success = "";

// Check if member ID is provided
if (!isset($_GET['member_id'])) {
    header('Location: members.php');
    exit();
}

$member_id = $_GET['member_id'];

// Retrieve member information from the database
$sql = "SELECT * FROM members WHERE member_id = $member_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Member not found
    header('Location: members.php');
    exit();
}

$row = $result->fetch_assoc();
$memberType = $row['member_type'];

// Update member information
if (isset($_POST['update_member'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Additional fields based on member type
    $department = ($memberType == 'staff') ? $_POST['department'] : $_POST['department'];
    $class = ($memberType == 'student') ? $_POST['class'] : null;
    $collegeID = ($memberType == 'student') ? $_POST['college_id'] : null;

    // Validate input
    $errors = validateMemberInput($name, $email, $phone);

    // Additional validation based on member type
    if ($memberType == 'student') {
        $errors = array_merge($errors, validateStudentInput($department, $class, $collegeID));
    } else if ($memberType == 'staff') {
        $errors = array_merge($errors, validateStaffInput($department, $collegeID));
    }

    if (empty($errors)) {
        // Update member information in the database
        $sql = "UPDATE members SET name = '$name', email = '$email', phone = '$phone'";

        // Update additional fields based on member type
        if ($memberType == 'student') {
            $sql .= ", department = '$department', class = '$class', college_id = '$collegeID'";
        } else if ($memberType == 'staff') {
            $sql .= ", department = '$department', college_id = '$collegeID'";
        }

        $sql .= " WHERE member_id = $member_id";

        if ($conn->query($sql)) {
            $success = "Member information updated successfully.";
        } else {
            $errors[] = "Failed to update member information.";
        }
    }
}

// Function to validate common member input fields
function validateMemberInput($name, $email, $phone)
{
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Please enter the member name.';
    }

    if (empty($email)) {
        $errors[] = 'Please enter the member email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($phone)) {
        $errors[] = 'Please enter the member phone number.';
    }

    return $errors;
}

// Function to validate student-specific input fields
function validateStudentInput($department, $class, $collegeID)
{
    $errors = [];

    if (empty($department)) {
        $errors[] = 'Please enter the student department.';
    }

    if (empty($class)) {
        $errors[] = 'Please enter the student class.';
    }

    if (empty($collegeID)) {
        $errors[] = 'Please enter the student college ID.';
    }

    return $errors;
}

// Function to validate staff-specific input fields
function validateStaffInput($department, $collegeID)
{
    $errors = [];

    if (empty($department)) {
        $errors[] = 'Please enter the staff department.';
    }

    if (empty($collegeID)) {
        $errors[] = 'Please enter the staff college ID.';
    }

    return $errors;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
    </style>
    <style>
        header{
            padding: 0;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }


        .error {
            color: red;
        }

        .success {
            color: green;
        }
         /* CSS styles for the header */
         .header {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        .header h1 {
            margin: 0;
            color: white;
        }
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
    </style>

<style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 300px;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<header class="bg-blue-500">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <h1 class="text-white text-3xl font-bold">Library Management System</h1>
            <nav>
                <ul class="flex space-x-4 text-white text-lg">
                    <!-- <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="books.php">Manage Books</a></li>
                    <li><a href="add_books.php">Add Books</a></li>
                    <li><a href="members2.php">Manage Members</a></li>
                    <li><a href="borrowings.php">Manage Borrowings</a></li> -->
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a href="../../index.php">Home</a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Members</a>
                            <a href="../../testings/reports.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Reports</a>
                            <a href="admin/circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Settings</a>
                           
                        </div>
                       
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="#" class="dropbtn">books</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="../books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="../books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="../academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../academics/academics.php/#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="../academics/academics.php/#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="../academics/academics.php/#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="../academics/academics.php/#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Course</a>
                        </div>
                    </li>

                
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-gray-900 bg-blue-900" ><a href="../logout.php">Logout</a></li>

                    <?php
                        }
                        else{

                        ?>
                         <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a href="./admin/login.php">Admin Login</a></li>
                         <?php

                        }
                        ?>
                </ul>
            </nav>
        </div>
    </header>

 

    <?php if ($success) : ?>
      <CENter> <p class="success"><?php echo $success; ?></p></CENter> 
    <?php endif; ?>
<CENTER>

    <?php if (!empty($errors)) : ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</CENTER>

    <div style="display:flex;justify-content:center;align-items:center;">
        
    <form method="POST" style="">
        <label>Name: </label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>

        <label>Email: </label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>

        <label>Phone: </label>
        <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required><br>

        <?php if ($memberType == 'student') : ?>
            <label>Department: </label>
            <input type="text" name="department" value="<?php echo $row['department']; ?>" required><br>

            <label>Class: </label>
            <input type="text" name="class" value="<?php echo $row['class']; ?>" required><br>


            <label>College ID: </label>
            <input type="text" name="college_id" value="<?php echo $row['college_id']; ?>" required><br>
        <?php elseif ($memberType == 'staff') : ?>
            <label>Department: </label>
            <input type="text" name="department" value="<?php echo $row['department']; ?>" required><br>

            <label>College ID: </label>
            <input type="text" name="college_id" value="<?php echo $row['college_id']; ?>" required><br>
        <?php endif; ?>

     <center>
     <input type="submit" name="update_member" value="Update Member">
     </center>
    </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
