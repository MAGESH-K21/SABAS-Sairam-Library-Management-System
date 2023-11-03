<?php
include '../connection/db.php';
error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}



// Delete a book
if (isset($_GET['delete_book'])) {
    $book_id = $_GET['delete_book'];
    $sql = "DELETE FROM books WHERE book_id = $book_id";
    $conn->query($sql);
    header('Location: books.php');
    exit();
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
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
        table,tr,td{
            border-bottom:1px solid gray;
            font-size:14px; 
         
        }
        th{
            background-color:rgb(220,220,220);
            position: sticky;
            top:0px;
        }
        table{
           border-collapse:separate;
            border-spacing:0px;
            min-width:max-content;
        }

        .outer{
            box-shadow: 0px 0px 3px black;
            margin:20px;
            max-width:fit-content;
            /* position:fixed; */
        }
       .inner{
        min-height:50vh;
        max-height:60vh;
        overflow-y:scroll;
        margin:20px;
       }
       
    </style>
</head>
<body>

<header class="bg-blue-500">
        <div class="container mx-auto py-4 px-6 flex justify-between items-center">
            <h1 class="text-white text-3xl font-bold">SABAS</h1>
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
                            <a href="../circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Settings</a>
                           
                        </div>
                       
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-blue-900">
                        <a href="" class="dropbtn">books</a>
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


      
<div class=" mx-auto px-3">
    <div class="container2 ml-10 mr-10 flex justify-center items-center" style="width:75vw;height:11vh">

    
          
          <!-- Add Book Form -->
        <form action="books.php" method="GET" style="width: 75vw;
    height: 10vh;
    padding: 0px;
    margin-top: 5px;">
            <div class="flex">
            <label class="block mx-2" style="width: fit-content;
    text-align: center; font-weight:bold;padding-top:5px">Search Book:</label>
                <input type="text" name="searchInput" id="searchInput" class="w-full px-2 py-1 border border-gray-300 " placeholder="Enter a search term">
                <select name="subjectFilter" class="ml-2 px-2 py-1 border border-gray-300">
        <option value="">Select Subject</option>
        <?php
    $sqlSubjects = "SELECT DISTINCT subject FROM books WHERE TRIM(subject) <> ''";
    $resultSubjects = $conn->query($sqlSubjects);

    while ($rowSubject = $resultSubjects->fetch_assoc()) {
        $subject = $rowSubject['subject'];
        echo '<option value="' . $subject . '">' . $subject . '</option>';
    }
    ?>
    </select>
    <select name="departmentFilter" class="ml-2 px-2 py-1 border border-gray-300">
        <option value="">Select Department</option>
        <?php
    $sqlDepartments = "SELECT DISTINCT department FROM books WHERE TRIM(department) <> ''";
    $resultDepartments = $conn->query($sqlDepartments);

    while ($rowDepartment = $resultDepartments->fetch_assoc()) {
        $department = $rowDepartment['department'];
        echo '<option value="' . $department . '">' . $department . '</option>';
    }
    ?>
    </select>


                <button type="submit" name="search" class="ml-2 bg-blue-500 text-white px-4 py-2 ">Search</button>
            </div>
        </form>

    </div>
      <div style="height: 67vh;width:94vw;"class="mx-auto ">
<!--       
          <button id="left" class="hover:bg-blue-700 bg-blue-500 text-gray-100 font-bold " style="position: fixed;left: 0px;
top: 218px;
height: 68vh;
width: 3vw;z-index:2;"><</button>
          <button id="right" class=" hover:bg-blue-700 bg-blue-500 text-gray-100 font-bold " style="position: fixed;right: 0px;
top: 218px;
height: 68vh;
width: 3vw;">></button> -->
          
          <?php

// Determine the total number of records
$sqlCount = "SELECT COUNT(*) as total FROM books";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$totalRecords = $rowCount['total'];

// Set the number of records to display per page
$recordsPerPage = 1000;

// Calculate the total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure the current page is within valid range

// Calculate the starting offset for retrieving records
$offset = ($currentPage - 1) * $recordsPerPage;

// Define the number of visible page links
$visiblePageLinks = 15;

// Calculate the range of page links to display
$startPage = max(1, $currentPage - floor($visiblePageLinks / 2));
$endPage = min($startPage + $visiblePageLinks - 1, $totalPages);
$startPage = max(1, $endPage - $visiblePageLinks + 1);

// Adjust the range if it extends beyond the total number of pages
$endPage = min($endPage, $totalPages);
$startPage = max(1, $startPage);

?>
<div class=" books flex items-center justify-center space-x-2 m-2">

    <?php if ($totalPages > 1): ?>
        <?php if ($currentPage > 1): ?>
            <a href="<?php echo getPageUrl($currentPage - 1); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Previous</a>
        <?php endif; ?>

        <?php if ($startPage > 1): ?>
            <a href="<?php echo getPageUrl(1); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 ">1</a>
            <?php if ($startPage > 2): ?>
                <span class="current bg-blue-500 text-white font-bold py-2 px-4 ">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="current bg-blue-500 text-white font-bold py-2 px-4 "><?php echo $i; ?></span>
            <?php else: ?>
                <a href="<?php echo getPageUrl($i); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <span class="current bg-blue-500 text-white font-bold py-2 px-4 ">...</span>
            <?php endif; ?>
            <a href="<?php echo getPageUrl($totalPages); ?>" class="bg-blue-200 hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $totalPages; ?></a>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo getPageUrl($currentPage + 1); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
// Function to generate the page URL with the given page number
function getPageUrl($pageNumber) {
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $queryParams);
    $queryParams['page'] = $pageNumber;
    return $_SERVER['PHP_SELF'] . '?' . http_build_query($queryParams);
}
?>
<div class="outer">
<div class="inner">
      
              <table id="booksTable" class="" >
                <script>
                    var d = document.getElementById('right');d.addEventListener('click',function (){
                    var b = document.getElementById('booksTable');
                    b.style="transition:transform 0.2s ease-out;transform:translateX(-972px);";
});
    //remove
    var d = document.getElementById('left');d.addEventListener('click',function (){
    var b = document.getElementById('booksTable');
    
    b.style="transition:transform 0.2s ease-out;transform:translateX(0px);";
});
                </script>
                  <tr>
                      <th class="px-4 py-2">Action</th>
                      <th class="px-4 py-2">Borrow</th>
                      <th class="px-4 py-2" onclick="sortTable(0)">ID</th>
                      <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Author</th>
                            <th class="px-4 py-2">Publication Year</th>
                            <th class="px-4 py-2">Available</th>
                            <th class="px-4 py-2">ISBN</th>
                            <th class="px-4 py-2">Accession Code</th>
                            <th class="px-4 py-2">Subtitle</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Edition</th>
                            <th class="px-4 py-2">Edition Year</th>
                            <th class="px-4 py-2">Language</th>
                            <th class="px-4 py-2">Publisher</th>
                            <th class="px-4 py-2">Publisher Year</th>
                            <th class="px-4 py-2">Series</th>
                            <th class="px-4 py-2">Rack Location</th>
                            <th class="px-4 py-2">Total Pages</th>
                            <th class="px-4 py-2">Source</th>
                            <th class="px-4 py-2">Uploaded Date</th>
                            <th class="px-4 py-2">Updated Date</th>
                            <th class="px-4 py-2">Subject</th>
                            <th class="px-4 py-2">Department</th>
                  </tr>
                  <?php
                 
          
          
          // Retrieve all books or perform a search
          if (isset($_GET['search']) || isset($_GET['subjectFilter']) || isset($_GET['departmentFilter'])) {
            $search = isset($_GET['searchInput']) ? $_GET['searchInput'] : '';
            $subjectFilter = isset($_GET['subjectFilter']) ? $_GET['subjectFilter'] : '';
            $departmentFilter = isset($_GET['departmentFilter']) ? $_GET['departmentFilter'] : '';
            
            $sql = "SELECT * FROM books WHERE (title LIKE '%$search%' OR author LIKE '%$search%' OR code LIKE '%$search%')";
        
            if (!empty($subjectFilter) && !empty($departmentFilter)) {
                $sql .= " AND subject = '$subjectFilter' AND department = '$departmentFilter'";
            } elseif (!empty($subjectFilter)) {
                $sql .= " AND subject = '$subjectFilter'";
            } elseif (!empty($departmentFilter)) {
                $sql .= " AND department = '$departmentFilter'";
            }
        
            $sql .= " ORDER BY department"; // You can change this to ORDER BY subject or any other column
            $sql .= " LIMIT $offset, $recordsPerPage";
        } else {
            $sql = "SELECT * FROM books LIMIT $offset, $recordsPerPage";
        }
            $result = $conn->query($sql);
          
                  // Display the paginated results
                   $col = mysqli_num_fields($result);
          
          
          
                      while ($row = $result->fetch_array()) {
                          // Display the book details
                          $i = 0;
                         
                          ?>
                          <tr>
                          <td class="px-4 py-2" nowrap>
                              <a href="edit_books.php?book_id=<?php echo $row['book_id']; ?>"class="px-2 py-1 bg-blue-800 hover:bg-blue-300 text-gray-100 hover:text-gray-900">Edit</a>
                              <a class="bg-red-700 hover:bg-red-300 text-gray-100 hover:text-gray-900 px-3 py-1 text-center" href="books.php?delete_book=<?php echo $row['book_id']; ?>"onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                          </td>
                          <td class="px-4 py-2">
                          <?php if ($row['available']) 
                          {
                              ?>
          
                      <a href="issue.php?book_id=<?php echo $row['book_id']; ?>" style="border:#428bca" class="relative inline-flex items-center justify-center  px-6  overflow-hidden font-medium text-indigo-600 transition duration-300 ease-out border-2 -full shadow-md group">
                       
<span style="background-color:#428bca;" class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 -translate-x-full group-hover:translate-x-0 ease" name="issue_page" value="issue">
<svg class="w-6 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
</span>
<span style="text:#428bca" class="absolute flex items-center justify-center w-full h-full  transition-all duration-300 transform group-hover:translate-x-full ease">Issue Book</span>
<span class="relative invisible">Issue Book</span>
</a>
              <?php
              }
              else{
                  ?>
                  Book unavailable
              <?php
               } 
              ?>
          </td>
                              <?php
                          while($i<$col)
                          {
                            if($i!=7 && $i !=8)
                            {
                      ?>
                      <td class="px-4 py-2" nowrap>
                          <?php
                         
                              echo $row[$i];
                          
                          
                              ?>
                              </td>
                              <?php
                            }
                              $i++;
                          }
                          ?>
                         
              </tr>
          <?php  
          }
          
          
          // Close the database connection
          $conn->close();
          ?>
          
              </table>
          </div>
</div>

</div>

  </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
