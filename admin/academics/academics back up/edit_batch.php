<?php
include '../connection/db.php';
error_reporting(0);

// Check if the user is authenticated
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch batch data for editing
if (isset($_GET['edit_batch_id'])) {
    $edit_batch_id = $_GET['edit_batch_id'];

    // Fetch batch details
    $sql = "SELECT batch.*, courses.* FROM batch
            JOIN courses ON batch.course_id = courses.course_id
            WHERE batch.batch_id = '$edit_batch_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $batch = $result->fetch_assoc();
    } else {
        // Redirect to the batch list page or show an error message
        header('Location: academics.php#course');
        exit();
    }
} else {
    // Redirect to the batch list page
    header('Location: academics.php');
    exit();
}

if (isset($_POST['edit_batch'])) {
    $batch_id = $_POST['batch_id'];
    $batch_name = $_POST['edit_batch_name'];
    $from_year = $_POST['edit_from_year'];
    $to_year = $_POST['edit_to_year'];
    $course_id = $_POST['edit_course_id'];

    $sql = "UPDATE batch SET batch_name = '$batch_name', from_year = '$from_year', to_year = '$to_year', course_id = '$course_id' WHERE batch_id = '$batch_id'";
    if ($conn->query($sql) === TRUE) {
        // Successfully updated, redirect to batch list or display a success message
        header('Location: academics.php');
        exit();
    } else {
        // Error occurred, display the error message
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Batch</title>
    <!-- Add your CSS and JavaScript includes here -->
<style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .edit-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
</style>
</head>
<body>
<div class="edit-container">
        <h2>Edit Batch</h2>
       
    <form method="post" action="">
        <input type="hidden" name="batch_id" value="<?php echo $batch['batch_id']; ?>">
        
        <label for="edit_batch_name">Batch Name (Year):</label>
        <input type="number" name="edit_batch_name" value="<?php echo $batch['batch_name']; ?>" required>
        
        <label for="edit_from_year">From Year:</label>
        <input type="number" name="edit_from_year" value="<?php echo $batch['from_year']; ?>" required>
        
        <label for="edit_to_year">To Year:</label>
        <input type="number" name="edit_to_year" value="<?php echo $batch['to_year']; ?>" required>
        
        <div class="form-group">
            <label for="edit_course_id">Course:</label>
            <select name="edit_course_id" required>
                <?php
                $courseQuery = "SELECT * FROM courses";
                $courseResult = mysqli_query($conn, $courseQuery);

                while ($courseRow = mysqli_fetch_assoc($courseResult)) {
                    $course_id = $courseRow['course_id'];
                    $course_name = $courseRow['course_name'];

                    // If the course ID matches, mark it as selected
                    $selected = ($batch['course_id'] == $course_id) ? 'selected' : '';

                    echo "<option value=\"$course_id\" $selected>$course_name</option>";
                }
                ?>
            </select>     
        
        <button type="submit" name="edit_batch">Save</button>
    </form>
    
    </div>
</body>
</html>
