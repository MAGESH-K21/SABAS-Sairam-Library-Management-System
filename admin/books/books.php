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
if (isset($_GET['delete_book'])) 
{
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left:10px
        }
        tbody{
            background-color:#eeefff;
        }   
        th{
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background: linear-gradient(269deg, #B3A3FE 22.86%, #B1B9FF 91.9%);
            

        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background: #EEEFFF;
        }

        th {
            background-color: #f2f2f2;
        }
        .outer{
            max-width:fit-content;
            /* position:fixed; */
        }
       .inner{
        min-height:50vh;
        max-height:60vh;
        overflow-y:scroll;
       }
       

       
    </style>
</head>
<body>

<?php
include '../includes/header.php';
?>

<h1 class="text-center text-gray-900 font-bold text-2xl mx-auto mt-2 mb-4">Book List</h1>
      
<div class=" mx-auto px-3">
    <div class="container2 ml-10 mr-10 flex justify-center items-center" style="width:75vw;height:11vh">

    
          
          <!-- Add Book Form -->
        <form action="books.php" method="GET" style="width: 75vw;
    height: 10vh;
    padding: 0px;
    margin-bottom: 10px;">
            <div class="flex">
            <label nowrap class="block mx-2" style="width: fit-content;
    text-align: center; font-weight:bold;padding-top:5px">Search Book:</label>
                <input type="text" name="searchInput" id="searchInput" class="w-3/4 px-8 py-2 m-4 border-violet-500 border  " placeholder="Enter a search term">
                <select name="subjectFilter" class="w-3/4 px-8 py-2 m-4 border-violet-500 border">
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
    <select name="departmentFilter" class="w-3/4 px-8 py-2 m-4 border-violet-500 border">
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
                    <button type="submit" name="search" class=" w-1/2 ml-5 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 text-white px-4 py-2 m-4 ">Search</button>
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
$recordsPerPage = 500;

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
                      <th nowrap class="px-4 py-2">Action</th>
                      <th nowrap class="px-4 py-2">Borrow</th>
                      <th nowrap class="px-4 py-2" onclick="sortTable(0)">ID</th>
                      <th nowrap class="px-4 py-2">Title</th>
                            <th nowrap class="px-4 py-2">Author</th>
                            <th nowrap class="px-4 py-2">Publication Year</th>
                            <th nowrap class="px-4 py-2">Available</th>
                            <th nowrap class="px-4 py-2">ISBN</th>
                            <th nowrap class="px-4 py-2">Accession Code</th>
                            <th nowrap class="px-4 py-2">Subtitle</th>
                            <th nowrap class="px-4 py-2">Category</th>
                            <th nowrap class="px-4 py-2">Price</th>
                            <th nowrap class="px-4 py-2">Edition</th>
                            <th nowrap class="px-4 py-2">Edition Year</th>
                            <th nowrap class="px-4 py-2">Language</th>
                            <th nowrap class="px-4 py-2">Publisher</th>
                            <th nowrap class="px-4 py-2">Publisher Year</th>
                            <th nowrap class="px-4 py-2">Series</th>
                            <th nowrap class="px-4 py-2">Rack Location</th>
                            <th nowrap class="px-4 py-2">Total Pages</th>
                            <th nowrap class="px-4 py-2">Source</th>
                            <th nowrap class="px-4 py-2">Uploaded Date</th>
                            <th nowrap class="px-4 py-2">Updated Date</th>
                            <th nowrap class="px-4 py-2">Subject</th>
                            <th nowrap class="px-4 py-2">Department</th>
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
                          <td nowrap class="px-4 py-2" nowrap>
                              <a href="edit_books.php?book_id=<?php echo $row['book_id']; ?>"class="px-2 py-1 bg-blue-800 hover:bg-blue-300 text-gray-100 hover:text-gray-900">Edit</a>
                              <a class="bg-red-700 hover:bg-red-300 text-gray-100 hover:text-gray-900 px-3 py-1 text-center" href="books.php?delete_book=<?php echo $row['book_id']; ?>"onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                          </td>
                          <td nowrap class="px-4 py-2">
                          <?php if ($row['available']) 
                          {
                              ?>
          
                      <a href="issue.php?book_id=<?php echo $row['book_id']; ?>" style="border:#428bca" class="relative inline-flex items-center justify-center  px-6  overflow-hidden font-medium  text-indigo-600 transition duration-300 ease-out border-2 -full shadow-md group">
                       
<span style="background-color:#428bca;" class="absolute inset-0 flex items-center justify-center w-full h-full text-white duration-300 -translate-x-full group-hover:translate-x-0 ease group-hover:bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400" name="issue_page" value="issue">
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
                      <td nowrap class="px-4 py-2" nowrap>
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
<div class=" books flex items-center justify-center space-x-2 m-2">

    <?php if ($totalPages > 1): ?>
        <?php if ($currentPage > 1): ?>
            <a href="<?php echo getPageUrl($currentPage - 1); ?>" class="bg-[#b4a2fc] hover:bg-blue-700 text-white font-bold py-2 px-4 ">Previous</a>
        <?php endif; ?>

        <?php if ($startPage > 1): ?>
            <a href="<?php echo getPageUrl(1); ?>" class="bg-[#EEEFFF] hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 ">1</a>
            <?php if ($startPage > 2): ?>
                <span class="current bg-black text-white font-bold py-2 px-4 ">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="current bg-black text-white font-bold py-2 px-4 "><?php echo $i; ?></span>
            <?php else: ?>
                <a href="<?php echo getPageUrl($i); ?>" class="bg-[#EEEFFF] hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <span class="current bg-black text-white font-bold py-2 px-4 ">...</span>
            <?php endif; ?>
            <a href="<?php echo getPageUrl($totalPages); ?>" class="bg-[#EEEFFF] hover:bg-blue-300 text-blue-700 font-bold py-2 px-4 "><?php echo $totalPages; ?></a>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo getPageUrl($currentPage + 1); ?>" class="bg-[#b4a2fc] hover:bg-blue-700 text-white font-bold py-2 px-4 ">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
</div>

  </div>
  <?php
include '../includes/footer.php';
?>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
