<?php
session_start();

// Check if the user is already authenticated


// Check if the login form is submitted
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the login credentials
    if ($username === 'admin' && $password === 'password') {
        // Authentication successful
        $_SESSION['authenticated'] = true;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!-- <!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto pt-16">
        <h1 class="text-3xl text-center mb-8">Login</h1>
        <form class="bg-white shadow-md  px-8 py-6 max-w-xs mx-auto" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username:</label>
                <input class="appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500" id="username" type="text" name="username" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password:</label>
                <input class="appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500" id="password" type="password" name="password" required>
            </div>
            <div class="flex items-center justify-between">
            </div>
        </form>
    </div>
</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    input:focus {
      outline: none;
    }
  </style>
</head>
<body class="flex flex-col lg:flex-row items-center relative bg-gray-100">

  <!-- Images occupy half of the screen -->
  <div class="w-full lg:w-1/2 relative h-64 lg:h-screen">
    <img src="images/Group 30.png" alt="Images" class="w-full h-full object-cover">
  </div>

  <!-- Logo placed on top right -->
  <div class="hidden lg:block absolute top-0 right-0 p-4">
    <img src="images/sabas..png" alt="Logo" class="h-7">
  </div>

  <!-- Login form -->
  <div class="lg:w-1/2 h-screen flex flex-col justify-center items-center text-center py-8 lg:py-0">
    <form method="POST" class="w-full md:w-2/3 lg:w-3/4">
      <h1 class="font-sans text-3xl sm:text-4xl mb-4 font-medium text-gray-900">Admin Login</h1>
      <input class="placeholder-gray-500 border-b-2 border-gray-300 py-2 px-4 lg:px-8 mb-2 w-full font-sans shadow-2xl" placeholder="Username" type="text" required id="username" name="username">
      <input class="placeholder-gray-500 border-b-2 border-gray-300 py-2 px-4 lg:px-8 mb-4 w-full font-sans shadow-2xl" type="password" placeholder="Password" id="password" name="password" required>
      <?php if (isset($error)) : ?>
        <div class="text-red-500"><?php echo $error; ?></div>
      <?php endif; ?>
      <button style=" background-image: linear-gradient(to right,rgba(101, 146, 255, 1) , rgba(180, 162, 252, 1),  rgba(127, 95, 255, 1), rgba(157, 185, 255, 1),  rgba(207, 194, 255, 1)); width: 480px;" name="login" value="Login" type="submit" class="font-sans font-bold text-white bg-blue-500 border-0 py-2 px-8 rounded text-lg shadow-2xl w-full">Login</button>
      <!-- <button  class="font-sans font-bold text-white border-0 py-2 px-8 rounded text-lg shadow-2xl">Login</button> -->
    </form>
  </div>

  <style>
    @media (max-width: 767px) {
      /* Adjustments for smaller screens */
      .lg:w-1\/2 {
        width: 100%;
      }
      .h-64 {
        height: 24rem;
      }
    }

    @media (max-width: 639px) {
      /* Further adjustments for narrower screens */
      .lg:w-3\/4 {
        width: 100%;
      }
     
    }
  </style>
</body>
</html>

