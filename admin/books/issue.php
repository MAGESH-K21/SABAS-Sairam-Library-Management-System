<?php
session_start();
error_reporting(0);
include '../connection/db.php';
$book_id = $_GET['book_id'];


$count = 0;

// Borrow a book
if (isset($_POST['issue_book'])) {
    $book_id = $_GET['book_id'];
    $member_id = $_POST['member_id'];
    date_default_timezone_set('Asia/Bangkok'); 
    $borrowed_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+7 days'));
    $fine_amount = 0;
    $fineDate = NULL;
    
    
    $sql1 = "SELECT member_type,bookscount from members where member_id='$member_id'";
    $res3 =  $conn->query($sql1);

    $sql111 = "SELECT * from settings";
    $res31=  $conn->query($sql111);
    $row_member = $res3->fetch_assoc();

    $row_member2 = $res31->fetch_assoc();
    

    if($row_member['member_type'] == 'student')
    {
        $limit = $row_member2['student_books_allowed']; 
    }
    else{
         $limit = $row_member2['staff_books_allowed']; 
    }
    
    $count = $row_member['bookscount'];
    
    if($row_member['bookscount'] < $limit)
    {
    $sql = "INSERT INTO borrowings (book_id, member_id,fine_amount, borrowed_date, due_date,status,borrowings_time)
            VALUES ($book_id, $member_id, $fine_amount, '$borrowed_date', '$due_date','Borrowed',CURDATE())";

    echo $sql2 = "INSERT INTO fines (member_id,book_id, fine_amount, fine_date) VALUES ('$member_id','$book_id','$fine_amount', NULL)";
    $res =  $conn->query($sql2);

if(!$res)
{
    $_SESSION['fail_fine'] =  "fail";
    header('Location:borrowings.php');
}
else{
    $count++;
   
}
    if ($conn->query($sql)) {
        // Update the book availability status
        $updateSql = "UPDATE books SET available = available-1 WHERE book_id = $book_id";
        $conn->query($updateSql);
        $updateSql2 = "UPDATE members SET bookscount = $count WHERE member_id = '$member_id'";
        $conn->query($updateSql2);
        $_SESSION['success_badd'] = "The Book Was Issued Successfully";
        header('Location: borrowings.php');
        exit();
    } else {
        $_SESSION['Failed_msg'] = 'Failed to borrow the book.';
        header('Location: borrowings.php');
    }
}
else{
    $_SESSION['Failed_msg'] = 'Failed to borrow the book Limit Exeeded.';
    header('Location: borrowings.php');
}
}

 // Function to sanitize user input
 function sanitize($input)
 {
     return htmlspecialchars(stripslashes(trim($input)));
 }

 // Get the search query from the form submission
 $search_query = sanitize($_POST['search_query']);

 // Prepare the SQL query to search for records matching the search query
 $query = "SELECT * FROM members WHERE college_id LIKE '%$search_query%' OR name LIKE '%$search_query%'";

 


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
        .cont {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50vw;
            margin-top: 100px;
            background-color: #eeefff;
        }
        @media (max-width: 640px) {
            .cont {
                width: 100vw;
            }
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .pur {
            color: #5500A9;
        }
        .rr {
            color: #D00000;
        }
       input[type="submit"],button[type="submit"]{
        background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
       }
    </style>
   
</head>
<body>

<?php
include '../includes/header.php';
?>
    <?php

// Execute the query
$result = $conn->query($query);

?>
   
  <div class="container mx-auto p-4 my-24 ">
      <form method="POST" class="bg-[#EEEFFF] rounded-b-lg" >
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
        <div class="bg-[#B2ABFD] p-4 col-span-3 rounded-t-lg">
            <h2 class="text-center font-bold text-2xl text-white">Issue Member</h2>
        </div>
        <div class="p-4 col-span-2">
        <label class="inline m-2 font-medium">Search:</label>
                <input type="text" name="search_query" id="search_query" class="w-11/12 px-2 py-2 border border-[#B2ABFD] rounded-lg " placeholder="Enter a search term">

        </div>


        <!-- Row 1, Column 3 -->
        <div class="p-4">                      <button type="submit" name="search" class="ml-2 my-auto bg-blue-500 text-white px-4 py-2 w-1/2 rounded-lg">Search</button>
</div>

<div class=" p-4 col-span-3">
<?php
                if ($result->num_rows > 0) {
                    // If results are found, create a dropdown with the matching college details
                    echo '<h2 class="font-medium ">Search Results</h2>';
                    echo '<form action="issue.php" method="post">';
                    echo '<select name="member_id" class="font-normal block w-full p-2 border border-[#B2ABFD] rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">';

                    while ($row = $result->fetch_assoc()) {
                        $member_id = $row['member_id'];
                        $college_id = $row['college_id'];
                        $staff_college_id = $row['staff_college_id'];
                        $name = $row['name'];
                        echo "<option class='w-full' value='$member_id'>$name (College ID: $college_id, Staff College ID: $staff_college_id)</option>";
                    }

                    echo '</select>';

                } else {
                    echo '<h2>No results found.</h2>';
                }
                ?></div>
        <div class=" p-4 col-span-3">
                       <center><input type="submit" name="issue_book" value="Issue" class="mt-4 px-4 py-2 text-white bg-blue-500 hover:bg-blue-600 w-1/4 rounded-lg"></center>     
</div>

       
    </div>
        </form>
        <hr style="background-color:white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);">
    </div>
</body>
</html>

