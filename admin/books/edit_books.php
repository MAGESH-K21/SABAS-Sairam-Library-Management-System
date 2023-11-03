<?php
include '../connection/db.php';

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
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .ovdiv{
            background-color: #EEEFFF;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
        .but{
            background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1));
            width: 33%;
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
    </style>
</head>

<body>
    <?php
    include '../includes/header.php';
    ?>
    <div class="ovdiv container mx-auto  lg:my-24 p-4 my-44">
        <h2 class="text-2xl font-semibold mb-4 text-center">Edit Book</h2>
        <form method="POST" action="" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" name="book_id" value="<?php echo $id; ?>">

            <!-- ISBN -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="isbn_no">ISBN:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="isbn_no" type="text" name="isbn_no" value="<?php echo $isbn_no; ?>" required>
            </div>

            <!-- Code -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="code">Code:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="code" type="text" name="code" value="<?php echo $code; ?>" required>
            </div>

            <!-- Start -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="start">Start:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start" type="text" name="start" value="<?php echo $start; ?>" disabled>
            </div>

            <!-- End -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="end">End:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="end" type="text" name="end" value="<?php echo $end; ?>" disabled>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="title">Title:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" value="<?php echo $title; ?>" required>
            </div>

            <!-- Subtitle -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="sub_title">Subtitle:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="sub_title" type="text" name="sub_title" value="<?php echo $sub_title; ?>">
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="category">Category:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="category" type="text" name="category" value="<?php echo $category; ?>" required>
            </div>

            <!-- Author -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="author">Author:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="author" type="text" name="author" value="<?php echo $author; ?>" required>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="price">Price:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="text" name="price" value="<?php echo $price; ?>" required>
            </div>

            <!-- Edition -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="edition">Edition:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edition" type="text" name="edition" value="<?php echo $edition; ?>" required>
            </div>

            <!-- Edition Year -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="edition_year">Edition Year:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edition_year" type="text" name="edition_year" value="<?php echo $edition_year; ?>" required>
            </div>

            <!-- Language -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="language">Language:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="language" type="text" name="language" value="<?php echo $language; ?>" required>
            </div>

            <!-- Publisher -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="publisher">Publisher:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="publisher" type="text" name="publisher" value="<?php echo $publisher; ?>" required>
            </div>

            <!-- Publisher Year -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="publisher_year">Publisher Year:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="publisher_year" type="text" name="publisher_year" value="<?php echo $publisher_year; ?>" required>
            </div>

            <!-- Series -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="series">Series:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="series" type="text" name="series" value="<?php echo $series; ?>" required>
            </div>

            <!-- Rack Location -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="rack_location">Rack Location:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rack_location" type="text" name="rack_location" value="<?php echo $rack_location; ?>" required>
            </div>

            <!-- Total Pages -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="total_page">Total Pages:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="total_page" type="text" name="total_page" value="<?php echo $total_pages; ?>" required>
            </div>

            <!-- Source -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="source">Source:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="source" type="text" name="source" value="<?php echo $source; ?>" required>
            </div>

            <div class="mb-4 col-span-2 flex justify-center">
                <input type="submit" name="submit" value="Update Book" class=" but bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer">
            </div>
        </form>
    </div>
    <br>
    <br>
</body>

</html>
