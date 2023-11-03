<?php
session_start();
include '../connection/db.php';
error_reporting(0);
// Check if the user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
ini_set('memory_limit', '512M'); // Set memory limit to 512MB
set_time_limit(300); // Sets the limit to 300 seconds (5 minutes)



function validateBookInput($title, $author, $publication_year)
{
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Please enter the book title.';
    }

    if (empty($author)) {
        $errors[] = 'Please enter the author name.';
    }

    if (empty($publication_year)) {
        $errors[] = 'Please enter the publication year.';
    } elseif (!is_numeric($publication_year)) {
        $errors[] = 'Publication year should be a number.';
    }

    return $errors;
}

$currenttotalbooks = "SELECT COUNT(*) from books";
$resultbooks = $conn->query($currenttotalbooks);



// Add Book Form
if(isset($_POST['submit'])) {
    // Retrieve the form data
     $isbn_no = $_POST['isbn_no'];
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
     $total_page = $_POST['total_page'];
     $source = $_POST['source'];
     $subject = $_POST['subject'];
     $department = $_POST['department'];

     $startNumber = $_POST['start'];
     $copies = $_POST['end'];
     $endNumber = $startNumber+$copies;
     $available = 1;

    // Generate and insert multiple books
    $successCount = 0; // Counter for successful insertions
    $errorMessages = []; // Array to store error messages

    // Generate and insert multiple books
    for ($i = $startNumber; $i < $endNumber; $i++) {
        // Generate a unique code

         $code =  generateUniqueCode($isbn_no, $i); 
        // Insert the book into the database with the generated code
        $sql = "INSERT INTO books (isbn_no, code, title, sub_title, category, author, price, edition, edition_year, language, publisher, publisher_year, series, rack_location, total_page, source,subject,department,available) VALUES ('$isbn_no', '$code', '$title', '$sub_title', '$category', '$author', '$price', '$edition', '$edition_year', '$language', '$publisher', '$publisher_year', '$series', '$rack_location', '$total_page', '$source','$subject','$department','$available')";
         $result = $conn->query($sql);

        if ($result) {
             $successCount++; 
             $_SESSION['success_badd'] = "Book was added successfully!";
             // Increment the success count
        } else {
            // Error occurred while adding the book
            
             echo "error is : ".mysqli_error($conn);
            $errorMessages[] = "Error adding book with number $i";

        }
    }
     // Check if all books were successfully inserted
     if ($successCount != ($endNumber - $startNumber + 1)) {
        // Display the error messages
        echo "Errors occurred while adding the books:<br>";
        foreach ($errorMessages as $errorMessage) {
            echo "- $errorMessage<br>";
        }
    }
    // Redirect or display a success message
    header('Location: add_books.php');
}
require '../../vendor/autoload.php'; // Include PhpSpreadsheet autoloader

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
  // Check if a file is selected
  if ($_FILES['file']['name']) {
      $filename = $_FILES['file']['tmp_name'];

      try {
          $objPHPExcel = IOFactory::load($filename);
          $worksheet = $objPHPExcel->getActiveSheet();
          $highestRow = $worksheet->getHighestRow();

          $successMessages = []; // Array to store success messages
          $errorMessages = [];   // Array to store error messages

          // Start from the second row to skip the header row
          $successCount = 0; // Counter for successful insertions
          for ($row = 2; $row <= $highestRow; ++$row) {
            $isbn_no = $worksheet->getCell('A' . $row)->getValue();
            $code = $worksheet->getCell('B' . $row)->getValue();
            $start = 1;
            $end = 1;
            $title = $worksheet->getCell('E' . $row)->getValue();
            $sub_title = $worksheet->getCell('F' . $row)->getValue();
            $category = $worksheet->getCell('G' . $row)->getValue();
            $author = $worksheet->getCell('H' . $row)->getValue();
            $price = $worksheet->getCell('I' . $row)->getValue();
            $edition = $worksheet->getCell('J' . $row)->getValue();
            $edition_year = $worksheet->getCell('K' . $row)->getValue();
            $language = $worksheet->getCell('L' . $row)->getValue();
            $publisher = $worksheet->getCell('M' . $row)->getValue();
            $publication_year = $worksheet->getCell('N' . $row)->getValue();
            $publisher_year = $worksheet->getCell('N' . $row)->getValue();
            $series = $worksheet->getCell('O' . $row)->getValue();
            $rack_location = $worksheet->getCell('P' . $row)->getValue();
            $total_page = $worksheet->getCell('Q' . $row)->getValue();
            $source = $worksheet->getCell('R' . $row)->getValue();
            $uploaded_date = $worksheet->getCell('S' . $row)->getValue();
            $updated_date = $worksheet->getCell('T' . $row)->getValue();
            $subject = $worksheet->getCell('U' . $row)->getValue();
            $department = $worksheet->getCell('V' . $row)->getValue();
            $available = $end - $start + 1;
            $available = $available/$available;

            // Perform validation on the book data
            $errors = array();

            // Validate required fields
            // if (empty($isbn_no)) {
            //     $errors[] = 'ISBN number is required.';
            // }
            // if (empty($title)) {
            //     $errors[] = 'Title is required.';
            // }
            // if (empty($author)) {
            //     $errors[] = 'Author is required.';
            // }
            $code = generateUniqueCode($isbn, $i);
              // Insert the book if there are no validation errors
              if (empty($errors)) {
                  // Generate and insert multiple books
                  $i = $start;
                  
                  // Generate and insert multiple books
                  for ($i; $i <= $end; $i++) {
                    // Generate a unique code
                                    
                    $code = generateUniqueCode($isbn_no, $i);
                                            
                    // Insert the book into the database with the generated code
                    $sql = "INSERT INTO books (isbn_no, code, title, sub_title, category, author, price, edition, edition_year, language, publisher, publisher_year, series, rack_location, total_page, source,available,subject,department) VALUES ('$isbn_no', '$code', '$title', '$sub_title', '$category', '$author', '$price', '$edition', '$edition_year', '$language', '$publisher', '$publisher_year', '$series', '$rack_location', '$total_page', '$source','$available','$subject','$department')";
                    
                    $result = $conn->query($sql);

                      if ($result) {
                          $successCount++;
                          $successMessages[] = "Book with ISBN $isbn_no and Title '$title' (Row $row) has been added.";
                      } else {
                          // Error occurred while adding the book
                          $errorMessages[] = "Error adding book with ISBN $isbn_no and Title '$title' (Row $row)";
                      }
                  }

                  // Check if all books were successfully inserted
                  if ($successCount === ($highestRow - 1)) {
                      // All books were successfully imported
                      $successMessages[] = "$successCount books have been imported successfully.";
                  }
              }
          }

          // Display success and error messages
    if (!empty($successMessages)) {
      // Store success messages in an array
      $successMessagesArray = [];
      foreach ($successMessages as $message) {
          $successMessagesArray[] = $message;
      }
      // Store messages in session and redirect
      $_SESSION['success_messages'] = $successMessagesArray;
     
  }

  if (!empty($errorMessages)) {
      // Store error messages in an array
      $errorMessagesArray = [];
      foreach ($errorMessages as $message) {
          $errorMessagesArray[] = $message;
      }
      // Store messages in session and redirect
      $_SESSION['error_messages'] = $errorMessagesArray;
     
  }
      } catch (Exception $e) {
          $importError = 'Failed to import the Excel file.';
      }
  } else {
      $importError = 'Please select an Excel file to import.';
  }
  header("Location:add_books.php");
}

// Function to generate a unique code based on book title and number
function generateUniqueCode($isbn_no, $number) {
    // Generate a unique code using the book title and number
    
    $digits = array();

    while (count($digits) < 6) {
        $digit = mt_rand(0, 9);
        if (!in_array($digit, $digits)) {
            $digits[] = $digit;
        }
    }

    $code = implode('', $digits);
    return $code;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin:10px 0px;
        }

        input::file-selector-button {
            width:40%;
            padding:5px;
            border:none;
            background: #eeefff;
            font-weight:bold;
        }

        form input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
            color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 10px;
        }
        .error {
            color: #dc3545;
            margin-bottom: 20px;
        }
        .actions {
            display: flex;
            gap: 5px;
        }

        .actions a {
            display: inline-block;
            padding: 5px 10px;
            text-decoration: none;
            background-color: #007bff;
            color: #ffffff;
        }

        a.logout {
            display: block;
            text-align: right;
            margin-top: 20px;
        }
         /* CSS styles for the header */
         body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
  .form-column label,
  .form-column input {
    display: block;
    margin-bottom: 10px;
  }
  #column1,#column2,#column3{
    width: 100%;
    margin: 20px;
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
    color:#D00000;
}


#keerthe{
  width:200px;
  height:200px;
  background-image:linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));;
  margin-left: auto;
    margin-right: auto;
    margin-top:auto;
    left: 0;
    right: 0;
  position: absolute;
}

h1 {
  color: #FFFFFF;
  text-align: center;
  font-family: sans-serif;
  text-transform: uppercase;
  font-size: 20px;
  position: relative;
}

h1:after {
  position: absolute;
  content: "";
  -webkit-animation: Dots 2s cubic-bezier(0, .39, 1, .68) infinite;
  animation: Dots 2s cubic-bezier(0, .39, 1, .68) infinite;
}

.loader {
  margin: 5% auto 30px;
}

.book {
  border: 4px solid #FFFFFF;
  width: 60px;
  height: 45px;
  position: relative;
  perspective: 150px;
}

.page {
  display: block;
  width: 30px;
  height: 45px;
  border: 4px solid #FFFFFF;
  border-left: 1px solid #8455b2;
  margin: 0;
  position: absolute;
  right: -4px;
  top: -4px;
  overflow: hidden;
  background: #8455b2;
  transform-style: preserve-3d;
  -webkit-transform-origin: left center;
  transform-origin: left center;
}

.book .page:nth-child(1) {
  -webkit-animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68) 1.6s infinite;
  animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68)  infinite;
}

.book .page:nth-child(2) {
  -webkit-animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68) 1.45s infinite;
  animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68) 1.45s infinite;
}

.book .page:nth-child(3) {
  -webkit-animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68) 1.2s infinite;
  animation: pageTurn 1.2s cubic-bezier(0, .39, 1, .68) 1.2s infinite;
}


/* Page turn */

@-webkit-keyframes pageTurn {
  0% {
    -webkit-transform: rotateY( 0deg);
    transform: rotateY( 0deg);
  }
  20% {
    background: #4b1e77;
  }
  40% {
    background: rebeccapurple;
    -webkit-transform: rotateY( -180deg);
    transform: rotateY( -180deg);
  }
  100% {
    background: rebeccapurple;
    -webkit-transform: rotateY( -180deg);
    transform: rotateY( -180deg);
  }
}

@keyframes pageTurn {
  0% {
    transform: rotateY( 0deg);
  }
  20% {
    background: #4b1e77;
  }
  40% {
    background: rebeccapurple;
    transform: rotateY( -180deg);
  }
  100% {
    background: rebeccapurple;
    transform: rotateY( -180deg);
  }
}

.loadcontainer{
    width:100vw;
    height:100vh;
    background:rgba(0, 0, 0, 0.489);
    display:flex;
    align-items:center;
    position:fixed;
}
/* Dots */

@-webkit-keyframes Dots {
  0% {
    content: "";
  }
  33% {
    content: ".";
  }
  66% {
    content: "..";
  }
  100% {
    content: "...";
  }
}

@keyframes Dots {
  0% {
    content: "";
  }
  33% {
    content: ".";
  }
  66% {
    content: "..";
  }
  100% {
    content: "...";
  }
}
/* CSS for the modal */
.modal {
    display: none; 
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height:100%;
    background-color: rgba(0, 0, 0, 0.7);
}
.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px 20px 0px 20px;
    border-radius: 5px;
    width: 80%;
    overflow:scroll;
    height: 75vh;
}

.close {
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}



    </style>
</head>
<body >

<?php
    include '../includes/header.php';
    ?>
<div class="loadcontainer">

    <div id="keerthe">
    <div class="loader book">
      <figure class="page"></figure>
      <figure class="page"></figure>
      <figure class="page"></figure>
    </div>
    <h1>Reading</h1>
    </div>
</div>

<script>
    
window.addEventListener('load', function () {
    var loaderContainer = document.getElementById('keerthe');
    var loadContainer = document.getElementsByClassName('loadcontainer')[0];
    var content = document.getElementsByClassName('cont')[0];
    loadContainer.style.display = 'none';
    loaderContainer.style.display = 'none';
    content.style.display = 'block'; // Show the content
});
</script>
  
<div class="cont mx-auto "style="margin:auto; width:100%;">
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <div class="modal-body">
        <div class="grid grid-cols-3 gap-4 my-2">
          <div id="successCountContainer" class="bg-purple-200 p-2 ">
          <label class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300" for="">Success Counts</label>
 
            <div id="successCount">0</div>
        </div>
          <div id="errorCountContainer" class="bg-purple-200 p-2">
          <label class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300" for="">Error Counts</label>
 
          <div id="errorCount">0</div>
        </div>
        <div class="bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-bold rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 text-center" id="hideModalContainer">
          View Later
        </div>
        
</div>
        
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Books Inserted!</strong>
          <span class="message-box block sm:inline" id="successBox"></span>
          
        </div>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">Books Not Inserted!</strong>
          <span class="message-box block sm:inline" id="errorBox"></span>
          
        </div>
           
            <div id="pagination" class="pagination sticky bottom-0 bg-white py-5 px-[300px]">
                <button id="prevPage" class="pagination-btn px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
                

                <span class="text-sm text-gray-700 dark:text-gray-400">
      Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white"id="currentPage">1</span> of <span class="font-semibold text-gray-900 dark:text-white"id="totalPages">1</span> Entries
  </span>
                <button id="nextPage" class="pagination-btn px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
            </div>
        </div>
    </div>
</div>

<?php
    if(isset($_SESSION['success_badd'])){
?>
<div id="closeModalNotification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
  <strong class="font-bold">Success!</strong>
  <span class="block sm:inline"><?php
    echo $_SESSION['success_badd'];
  ?></span>
  <span class="absolute top-0 bottom-0 right-0 px-4 py-3"  onclick="hideModalNotification()">
    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
  </span>
</div>
<?php
}
?>
      <!-- Import Books Form -->
    <form method="POST" enctype="multipart/form-data" class="">
        <div class="flex items-center flex-col sm:flex-row bg-[#EEEFFF]">
        <div class="w-full sm:w-1/3 p-4">
        <h2 class="text-xl font-bold text-center">Import Books</h2>
      </div>
    <div class="w-full sm:w-1/3 p-4 bg-[#EEEFFF]">
        <input class="w-full p-4 "type="file" name="file" required class="import-excel" style="background-color: #B2ABFD;border-radius: 10px;">

    </div>
    <div class="w-full sm:w-1/3 p-4">
    <input type="submit" name="import" value="Import" class="w-full">
  </div>
</div>
</form>
<button class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900 absolute" id="showModalbutton">Show Inserted Data</button>
    <!-- Import Error Handling -->
    <?php if (isset($importError)) : ?>
        <div class="error">
            <?php echo $importError; ?>
        </div>
    <?php endif; ?>
    <h2 class="text-xl font-bold text-center m-4 ">Add Book</h2>

    
    <form method="POST" action="add_books.php" class="bg-[#EEEFFF] P-8">
    <div class ="flex justify-evenly w-full border-none  border-r-10 shadow-2xl">
        <div id="column1" >
    <label>ISBN:</label>
        <input type="text" placeholder="Enter ISBN No." name="isbn_no" >
     
        <label>Start:</label>
        <input type="text" name="start" value="<?php if ($resultbooks) {
    $row = $resultbooks->fetch_assoc();
    $count = $row['COUNT(*)'];
    echo $count+1;
} ?>">
        <label>No.Of Copies:</label>
        <input type="text" placeholder="Enter No. Of Copies" name="end" >
        <label>Title:</label>
        <input type="text" placeholder="Enter Book Title" name="title" >
        <label>Subtitle:</label>
        <input type="text" placeholder="Enter Book Sub-Title" name="sub_title">
        <label>Category:</label>
        <input type="text" placeholder="Enter Book Category" name="category" >
        </div>
<div id="column2">
        <label>Author:</label>
        <input type="text" placeholder="Enter Auther Name" name="author" >
        <label>Price:</label>
        <input type="text" placeholder="Enter Book Price" name="price" >

        <label>Edition:</label>
        <input type="text"  placeholder="Enter Edition "name="edition" >
        <label>Edition Year:</label>
        <input type="text" placeholder="Enter Edition Year" name="edition_year" >
        <label>Language:</label>
        <input type="text" placeholder="Enter Language" name="language" >
        <label>Publisher:</label>
        <input type="text" placeholder="Enter publisher detail" name="publisher" >
        <!-- <label>Publisher Year:</label>
        <input type="text" name="publisher_year" required> -->
        <br>
        <center>    <input class="m-4" type="submit" name="submit" value="add_Book" style="width:100%" ></center>
        </div>
<div id="column3">
        <label>Series:</label>
        <input type="text" placeholder="Enter series No." name="series" >

        <label>Rack Location:</label>
        <input type="text" placeholder="Enter Rack Location" name="rack_location" >
        <label>Total Pages:</label>
        <input type="text" placeholder="Enter Total Pages" name="total_page" >
        <label>Source:</label>
        <input type="text" placeholder="Enter source" name="source" >
        <label>Subject:</label>
        <input type="text" placeholder="Enter subject" name="subject" >
        <label>Department:</label>
        <input type="text" placeholder="Enter department" name="department" >

</div>

        </div>

    </form>


    <!-- Error Handling -->
    <?php if (!empty($errors)) : ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<script>
    // Get references to the modal and close button
    var modal = document.getElementById('myModal');
    var closeModals = document.getElementById('closeModal');
    var closeModalNotification = document.getElementById('closeModalNotification');
    var successBox = document.getElementById('successBox');
    var errorBox = document.getElementById('errorBox');
    var prevPage = document.getElementById('prevPage');
    var nextPage = document.getElementById('nextPage');
    var currentPage = document.getElementById('currentPage');
    var totalPages = document.getElementById('totalPages');
    var successCount = document.getElementById('successCount');
    var errorCount = document.getElementById('errorCount');
    var successCountContainer = document.getElementById('successCountContainer');
    var errorCountContainer = document.getElementById('errorCountContainer');
    var hideModalContainer = document.getElementById('hideModalContainer');
    var showModalbutton = document.getElementById('showModalbutton');
    showModalbutton.style.display = 'none';

    
    <?php if(isset($_SESSION['success_messages'])) { ?>
        var successMessages = <?php echo json_encode($_SESSION['success_messages']); ?>;
<?php } ?>

    <?php if(isset($_SESSION['error_messages'])) { ?>
        var errorMessages = <?php echo json_encode($_SESSION['error_messages']); ?>;
<?php } ?>
   
    var itemsPerPage = 10; // Number of messages per page
    var currentSuccessPage = 1;
    var currentErrorPage = 1;
    var showSuccessMessages = true; // Flag to indicate whether to show success or error messages

    // Function to show the modal
    function showModal() {
      console.log("showmodal called");
        modal.style.display = 'block';
        showModalbutton.style.display = 'none';
    }

    // Function to hide the modal
    function hideModalContainers() {
        modal.style.display = 'none';
        // Show the modal when it's supposed to be displayed (e.g., after form submission)
    <?php if( isset($_SESSION['success_messages']) || isset($_SESSION['error_messages'])) { ?>
        showModalbutton.style.display = 'block';
    <?php } ?>
    }
    // Function to hide the modal
    function hideModal() {
        modal.style.display = 'none';
    }
    // Function to hide the modal
    function closeModal() {
      console.log("sdfjsf");
      showModalbutton.style.display = 'none';
      hideModal();
    // Perform an AJAX request to a PHP script that unsets session data
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/sairam_lms/admin/books/unset_session_data.php', true); // Replace 'unset_session_data.php' with the actual path to your PHP script
    xhr.send();
}

function hideModalNotification() {
    closeModalNotification.style.display = 'none';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/sairam_lms/admin/books/unset_session_data.php', true); // Replace 'unset_session_data.php' with the actual path to your PHP script
    xhr.send();
    }
    function updateMessages() {
    var messagesToDisplay;
    var currentPageElement;

    if (showSuccessMessages) {
        messagesToDisplay = successMessages;
        currentPageElement = currentSuccessPage;
        successBox.style.display = 'block';
        errorBox.style.display = 'none'; // Hide error messages
    } else {
        messagesToDisplay = errorMessages;
        currentPageElement = currentErrorPage;
        successBox.style.display = 'none'; // Hide success messages
        errorBox.style.display = 'block';
    }

    var start = (currentPageElement - 1) * itemsPerPage;
    var end = start + itemsPerPage;
    var messagesPerPage = messagesToDisplay.slice(start, end);

    if (showSuccessMessages) {
        successBox.innerHTML = messagesPerPage.join('<br>');
        currentPage.innerText = currentSuccessPage;
        totalPages.innerText = Math.ceil(messagesToDisplay.length / itemsPerPage);
        successCount.innerText = messagesToDisplay.length;
    } else {
        errorBox.innerHTML = messagesPerPage.join('<br>');
        currentPage.innerText = currentErrorPage;
        totalPages.innerText = Math.ceil(messagesToDisplay.length / itemsPerPage);
        errorCount.innerText = messagesToDisplay.length;
    }
}

// Show success messages when clicking on the success count
successCountContainer.addEventListener('click', function() {
    showSuccessMessages = true;
    
    currentSuccessPage = 1;
    currentErrorPage = 1; // Reset error page
    updateMessages();
});

// Show error messages when clicking on the error count
errorCountContainer.addEventListener('click', function() {
    showSuccessMessages = false;
   
    currentSuccessPage = 1; // Reset success page
    currentErrorPage = 1;
    updateMessages();
});

    // Show the modal when it's supposed to be displayed (e.g., after form submission)
    <?php if(isset($_SESSION['success_messages']) || isset($_SESSION['error_messages'])) { ?>
        showModal();
    <?php } ?>

    // Handle pagination buttons
    prevPage.addEventListener('click', function() {
        if (showSuccessMessages) {
            if (currentSuccessPage > 1) {
                currentSuccessPage--;
                updateMessages();
            }
        } else {
            if (currentErrorPage > 1) {
                currentErrorPage--;
                updateMessages();
            }
        }
    });

    nextPage.addEventListener('click', function() {
        var maxPage = Math.ceil(showSuccessMessages ? successMessages.length : errorMessages.length / itemsPerPage);
        
        if (showSuccessMessages) {
            if (currentSuccessPage < maxPage) {
                currentSuccessPage++;
                updateMessages();
            }
        } else {
            if (currentErrorPage < maxPage) {
                currentErrorPage++;
                updateMessages();
            }
        }
    });


    // Initialize messages and pagination
    updateMessages();

    // Close the modal when the close button is clicked
    hideModalContainer.onclick = hideModalContainers;
    closeModals.onclick = closeModal;
    showModalbutton.onclick = showModal;
</script>




</body>
</html>