<?php
include '../connection/db.php';
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $id = $_POST['book_id'];
    $isbn_no = $_POST['isbn_no'];
    $code = $_POST['code'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $title = $_POST['title'];
    $sub_title = $_POST['sub_title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $edition = $_POST['edition'];
    $edition_year = $_POST['edition_year'];
    $language = $_POST['language'];
    $publisher = $_POST['publisher'];
    $publisher_year = $_POST['publisher_year'];
    $series = $_POST['series'];
    $rack_location = $_POST['rack_location'];
    $total_pages = $_POST['total_page'];
    $source = $_POST['source'];

    $sql = "UPDATE books SET isbn_no='$isbn_no', code='$code', start='$start', end='$end', title='$title', sub_title='$sub_title', category='$category', author='$author', price='$price', edition='$edition', edition_year='$edition_year', language='$language', publisher='$publisher', publisher_year='$publisher_year', series='$series', rack_location='$rack_location', total_page='$total_pages', source='$source' WHERE book_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully";
        header("Location:books.php");
    } else {
        echo "Error updating book: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['book_id'])) {
    $id = $_GET['book_id'];

    $sql = "SELECT * FROM books WHERE book_id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $isbn_no = $row['isbn_no'];
        $code = $row['code'];
        $start = $row['start'];
        $end = $row['end'];
        $title = $row['title'];
        $sub_title = $row['sub_title'];
        $category = $row['category'];
        $author = $row['author'];
        $price = $row['price'];
        $edition = $row['edition'];
        $edition_year = $row['edition_year'];
        $language = $row['language'];
        $publisher = $row['publisher'];
        $publisher_year = $row['publisher_year'];
        $series = $row['series'];
        $rack_location = $row['rack_location'];
        $total_pages = $row['total_page'];
        $source = $row['source'];
    } else {
        echo "No book found with the given ID";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
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
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../../index.php'">Home</a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900" ><a onclick="document.location='../dashboard.php'">Dashboard</a></li>

                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-blue-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="../members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
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
                            <a href="./add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="./books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="./borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="../academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="../academics/academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="../academics/academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="../academics/academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="../academics/academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
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
    <form method="POST" action="">
        <input type="hidden" name="book_id" value="<?php echo $id; ?>">
        <div style="display: flex; flex-direction:row;justify-content:space-evenly;align-items:center; margin-top:50px;"><div>
        <label>ISBN:</label>
        <input type="text" name="isbn_no" value="<?php echo $isbn_no; ?>" required><br><br>
        
        <label>Code:</label>
        <input type="text" name="code" value="<?php echo $code; ?>" required><br><br>
        
        <label>Start:</label>
        <input type="text" name="start" value="<?php echo $start; ?>" disabled><br><br>
        
        <label>End:</label>
        <input type="text" name="end" value="<?php echo $end; ?>" disabled><br><br>
        
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo $title; ?>" required><br><br>
        
        <label>Subtitle:</label>
        <input type="text" name="sub_title" value="<?php echo $sub_title; ?>"><br><br>
        
        <label>Category:</label>
        <input type="text" name="category" value="<?php echo $category; ?>" required><br><br>
        
        <label>Author:</label>
        <input type="text" name="author" value="<?php echo $author; ?>" required><br><br>
        
        <label>Price:</label>
        <input type="text" name="price" value="<?php echo $price; ?>" required><br><br>
                    </div>
                    <div>
        <label>Edition:</label>
        <input type="text" name="edition" value="<?php echo $edition; ?>" required><br><br>
        
        <label>Edition Year:</label>
        <input type="text" name="edition_year" value="<?php echo $edition_year; ?>" required><br><br>
        
        <label>Language:</label>
        <input type="text" name="language" value="<?php echo $language; ?>" required><br><br>
        
        <label>Publisher:</label>
        <input type="text" name="publisher" value="<?php echo $publisher; ?>" required><br><br>
        
        <label>Publisher Year:</label>
        <input type="text" name="publisher_year" value="<?php echo $publisher_year; ?>" required><br><br>
        
        <label>Series:</label>
        <input type="text" name="series" value="<?php echo $series; ?>" required><br><br>
        
        <label>Rack Location:</label>
        <input type="text" name="rack_location" value="<?php echo $rack_location; ?>" required><br><br>
        
        <label>Total Pages:</label>
        <input type="text" name="total_page" value="<?php echo $total_pages; ?>" required><br><br>
        
        <label>Source:</label>
        <input type="text" name="source" value="<?php echo $source; ?>" required><br><br></div></div>
        
        <center><input type="submit" name="submit" value="Update Book"></center>
    </form>
</body>
</html>
