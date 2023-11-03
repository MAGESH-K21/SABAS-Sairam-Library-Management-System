<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Web Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>

        /* styles.css */
.loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
    z-index: 9999; /* Ensure the loader is on top of other content */
}

.loader {
    border: 8px solid #f3f3f3; /* Light grey border */
    border-top: 8px solid #3498db; /* Blue border on top */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite; /* Rotate animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
body{
background-color: rgba(0, 0, 0, 0.489);
}
    </style>
</head>
<body>
    <!-- Loading animation -->
    <div class="loader-container">
        <div class="loader">hwllo</div>
    </div>

    <!-- Your website content -->
    <div id="content">
        <!-- Your existing content goes here -->
        <h1>Welcome to Your Website</h1>
        <!-- Your existing content continues here -->
    </div>
    <script>
        window.addEventListener('load', function () {
            var loaderContainer = document.querySelector('.loader-container');
            var content = document.getElementById('content');
            
            loaderContainer.style.display = 'none';
            content.style.display = 'block'; // Show the content
        });

        function hello(name){
            document.write(name);
        }
    </script>
<?php
$progress = 99;
echo "<script>hello($progress);</script>"
?>
</body>
</html>
