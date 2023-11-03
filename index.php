<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <link rel="shortcut icon" href="newfavi2.png" height="64px" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
            color: #333;
        }
        *{
            margin: 0;
            padding: 0;
        }
       
.pur{
    color: #5500A9;
}
.rr{
    color: #D00000;
}
#slideimg{
    height:300px;
    width:100%;
}
body {
background-color: white;
background-image: linear-gradient(to left,#83A5FF,#DFA2FC,white);
}
    </style>
      <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
   

<header class="bg-white-500">
  <div class="container mx-auto py-1 px-10 flex justify-between items-center">
          <img src="newlogo.png" alt="Logo" style="width: 80px;
    height: 100px;margin-left:60px;">
            <h1 class=" pur text-6xl font-bold"></h1>
            <div class="hidden lg:block absolute top-2 left-14 p-4">
            </div>
            <nav>
            <ul class="flex space-x-4 text-purple text-lg">
                    
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-white-900 font-bold" ><a href="index.php"><h1 class="pur">Home</h1></a></li>
                   

                    <?php

if(isset($_SESSION['authenticated']) == true)
    {
        ?>
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-white-900 font-bold" ><a href='admin/dashboard.php'>Dashboard</a></li>

                    <li class="px-4 py-1  font-bold hover:bg-gray-100 hover:text-gray-900 bg-white-900 relative group dropdown">
                        <a href="#" class="dropbtn">members</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg  py-1 z-10" nowrap style="margin-top: 3px;">
                            <a href="admin/members/members2.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Members</a>
                            <a href="testings/reports.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Manage Reports</a>
                            <a href="admin/circulation.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Settings</a>

                           
                        </div>
                       
                    </li>
                    <li class="px-4 py-1 relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-white-900 font-bold">
                        <a href="#" class="dropbtn">books</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="admin/books/add_books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Add Books</a>
                            <a href="admin/books/books.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Book List</a>
                            <a href="admin/books/borrowings.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Borrowings</a>
                           
                        </div>
                    </li>
                    <li class=" px-4 py-1  relative group dropdown hover:bg-gray-100 hover:text-gray-900 bg-white-900 text-black font-bold">
                        <a href="admin/academics/academics.php" class="dropbtn">Academics</a>
                        <div class="dropdown-content absolute hidden bg-white shadow-lg mt-2 py-2 z-10" style="margin-top: 3px;">
                            <a href="admin/academics/academics.php#department" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" style=" width: 160px;font-size: 15px;
    ">Department</a>
                            <a href="admin/academics/academics.php#class" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Class</a>
                            <a href="admin/academics/academics.php#year" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Year</a>
                            <a href="admin/academics/academics.php#course" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" style=" width: 160px;font-size: 15px;
    ">Course</a>
                        </div>
                    </li>

                
                    <li class="px-4 py-1 hover:bg-gray-200 hover:text-red-900 bg-white-900 font-bold" ><a href="admin/logout.php"><h1 class="rr">Logout</h1></a></li>

                    <?php
                        }
                        else{

                        ?>
                         <li class="px-4 py-1 hover:bg-gray-200 hover:text-red-900 bg-white-900 font-bold" ><a href="admin/login.php"><h1 class="rr">Admin Login</h1></a></li>
                         <?php

                        }
                        ?>
                </ul>
            </nav>
        </div>
    </header>

<div class="container mx-auto mt-8">
   
  
  <div class="flex justify-evenly">
  <div class="p-8" style="margin-right:100px;">
    <p class="text-5xl font-bold" style="line-height: 4rem;">Sai Automated <br>Bibliotheca <br>Administrative<br>System </p>
    <br>
    <p class="text-gray-900" style="line-height: 1.5rem;">Experience a limitless journey of learning, <br>empowered by SABAS, and thrive in knowledge <br>through our innovative platform. </p>
  </div>
  <div >
    <img style="width:400px;" class="relative " src="http://localhost/sairam_lms/homegif.gif" alt="">
  </div>
</div>

   
    <br>
    <section class="text-gray-600 body-font py-24  relative">
      <div class="container px-5  mx-auto">
        <div class="flex flex-col text-center w-full mb-14">
          <h1 class="sm:text-3xl text-5xl font-bold title-font mb-4 text-gray-900">Event Gallery</h1>
          <!-- <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them man bun deep jianbing selfies heirloom.</p> -->
        </div>
        <div class="flex flex-wrap -m-4  ">
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (1).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Shooting Stars</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (2).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">The Catalyzer</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (3).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">The 400 Blows</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (5).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Neptune</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (4).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Holden Caulfield</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/3 sm:w-1/2 p-4">
            <div class="flex relative">
              <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="image/1 (6).jpg">
              <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100">
                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">THE SUBTITLE</h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Alper Kamu</h1>
                <p class="leading-relaxed">Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing tousled waistcoat.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<footer class="bg-gradient-to-l from-blue-400 via-purple-400 to-white-400 shadow-inner body-font mt-10">
  <div class="container px-4 py-1 mx-auto flex items-center sm:flex-row flex-col">
    <a href="http://localhost/sairam_lms/index.php" class="flex title-font font-medium items-center md:justify-start justify-center ">
    <img src="http://localhost/sairam_lms/newlogo.png" alt="Logo" style="width: 80px;
    height: 100px; margin-left: 30px;"class="lg:hidden">
            <img src="http://localhost/sairam_lms/newlogo.png" alt="Logo" style="width: 80px;
    height: 100px; margin-left: 30px;" class=" hidden lg:block">    </a>
    <p class="text-sm sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4 font-medium">© 2023 future8.infotech@gmail.com —
      <a href="#" class="font-medium ml-1" rel="noopener noreferrer" target="_blank">@future8</a>
    </p>
    <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
      <a class="">
        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
          <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
          <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
        </svg>
      </a>
      <a class="ml-3 ">
        <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
          <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
          <circle cx="4" cy="4" r="2" stroke="none"></circle>
        </svg>
      </a>
    </span>
  </div>
</footer>
</body>
</html>
