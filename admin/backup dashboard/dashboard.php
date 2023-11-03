<?php

include 'connection/db.php';

error_reporting(0);
// Check if the user is authenticated
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}
// Functions to get the required data
function getNumberOfBooks()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of books from the "books" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM books";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfBooksTaken()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of books taken from the "borrowings" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM borrowings";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStudents()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of students from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'student'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStaffs()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of staffs from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'staff'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getTotalFineAmount()
{
    include 'connection/db.php';
    // Implement your logic to get the total fine amount from the "fines" table
    // For example:
    $query = "SELECT SUM(fine_amount) AS total_amount FROM members";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_amount'];
}

function getNumberOfStudentMembersWithFine()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of student members with fine from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'student' AND fine_amount > 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getNumberOfStaffMembersWithFine()
{
    include 'connection/db.php';
    // Implement your logic to get the No. Of student members with fine from the "members" table
    // For example:
    $query = "SELECT COUNT(*) AS count FROM members WHERE member_type = 'staff' AND fine_amount > 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}
// ...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body{
        background-color: white;
      }
      
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
        .dashboard {
            display: flex;

            max-width: 90vw;
            margin: 0 0 25px 0;
            justify-content:space-evenly;
            
        }
      

        .dashboard-card {
            background-color: white;   
            width:250px;
            height:100px;   
            padding: 20px;  
            color:black;      
            font-weight:bolder; 
            font-size:20px; 
            text-align: right;  
            display: flex; 
        }
       
        .dashboard-card p{
          font-size:20px;  
        }
       
        .dashboard-card-graph {
            background-color: while;
            width:40vw;
            height:20vw;
            margin-bottom:10px;
            text-align: center;
        }

        .dashboard-card h2 {
            font-size: 17px;
            margin: 0;
        }

        .dashboard-card p {
            font-size: 14px;
            margin: 0px 0 0 0;
        }

        .daashboard-card-graph h2 {
            font-size: 18px;
            margin: 0;
        }

        .dashboard-card-graph p {
            font-size: 14px;
            margin: 10px 0 0 0;
        }
        
       
        
       
.text{
  font-size:16px;
}
.chart-container {
            width: 25%;
            margin: auto;
            padding: 20px;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }
        .container{
          display:flex;
        }

        
      .pur{
          color: #5500A9;
      }
      .rr{
          color: #D00000;
      }
    </style>
</head>
<body>

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
        #overlay {
            position:absolute;
  width: 100%; /* Full width (cover the whole page) */
  height: 300px; /* Full height (cover the whole page) */
  top: 100px;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
  cursor: pointer; /* Add a pointer on hover */
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
background-image: linear-gradient(to right,#83A5FF,#DFA2FC,white);
}
    </style>
      <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
   

<header class="bg-white-500">
        <div class="container mx-auto py-6 px-6 flex justify-between items-center">
            <h1 class=" pur text-6xl font-bold"></h1>
            <div class="hidden lg:block absolute top-2 left-14 p-4">
              <img src="image/sabas..png" alt="Logo" class="h-7">
            </div>
            <nav>
                <ul class="flex space-x-4 text-purple text-lg">
                    
                    <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-white-900 font-bold" ><a href="index.php"><h1 class="pur">Home</h1></a></li>
                   

           
                     <li class="px-4 py-1  hover:bg-gray-100 hover:text-gray-900 bg-white-900 font-bold" ><a href='admin/dashboard.php'>Dashboard</a></li>

                    <li class="px-4 py-1  font-bold hover:bg-gray-100 hover:text-gray-900 bg-white-900 relative group dropdown">
                        <a href="#" class="dropbtn">Members</a>
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
                        <a href="#" class="dropbtn">Books</a>
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

                
                    <li class="rr px-4 py-1 hover:bg-gray-200 hover:text-red-900 bg-white-900 font-bold" ><a href="admin/logout.php"><h1 >Logout</h1></a></li>
                </ul>
            </nav>
        </div>
    </header>



     <h1 style="font-size:30px;font-weight:bold;text-align:center;margin:10px">Admin Dashboard</h1><br>
    

    <div style="margin:15px 56px 56px 56px">
      
      <div class="dashboard">
          <div class="dashboard-card" style="background-color:rgb(248, 158, 158) ">
           
          <div>  <img  class="dashboard-images" src="/images/2.jpg" alt=""></div>
                     <div style="display:flex;flex-direction:column;"> 
            <div class="text">
            Total No. Of Books 
            </div>
            <div>
            <?php echo getNumberOfBooks(); ?>
            </div>
          </div>
        </div> 
          <div class="dashboard-card" style="background-color:rgb(172, 172, 241)">
          <div><img class="dashboard-images" src="images/1.jpg" alt=""></div>  
          <div style="display:flex;flex-direction:column;"> 
            <div class="text">
              No. Of Books Taken
            </div>
            <div>
              <?php echo getNumberOfBooksTaken(); ?>
            </div>
          </div>
        </div> 


          <div class="dashboard-card" style="background-color:rgb(128, 245, 128)">
           
           <div> <img class="dashboard-images" src="images/3.jpg" alt=""></div>
          

                <div style="display:flex;flex-direction:column;"> 
                  <div class="text">
                    Total No. Of Students
                  </div>
                  <div>
                  <?php echo getNumberOfStudents(); ?>
                  </div>
              </div>
          </div> 

            <div class="dashboard-card" style="background-color:rgb(240, 214, 66)">
              <div><img class="dashboard-images" src="images/4.jpg" alt=""></div>
            
                      <div style="display:flex;flex-direction:column;"> 
                          <div class="text">
                         Total No. Of Staffs Members
                          </div>
                          <div>
                          <?php echo getNumberOfStaffs(); ?>
                          </div>
                        </div>
                      </div> 
             
        </div>
       



        <div class="dashboard">
          <div class="dashboard-card-graph">
            <h2>Borrowing Trends</h2>
            <div class="graph-container">
                <canvas id="borrowing-chart"></canvas>
            </div>
        </div>
        <div class="dashboard-card-graph">
            <h2>Member Fine Trends</h2>
            <div class="graph-container">
                <canvas id="fine-chart"></canvas>
            </div>
        </div>
        </div>
      </div>
    </div>

    <script>
  // Function to fetch borrowing data and update the graph
  function fetchBorrowingData() {
    // Make an AJAX request to fetch borrowing data
    fetch('fetch_borrowing_data.php')
      .then(response => response.json())
      .then(data => {
        // Update the graph data and options
        borrowingChart.data.labels = data.labels;
        borrowingChart.data.datasets[0].data = data.values;
        borrowingChart.update();
      })
      .catch(error => {
        console.error('Error fetching borrowing data:', error);
      });
  }

  // Data for the borrowing trends graph
  var borrowingData = {
    labels: [], // Initially empty
    datasets: [{
      label: "Borrowings",
      data: [], // Initially empty
      fill: false,
      borderColor: "rgb(75, 192, 192)",
      tension: 0.1
    }]
  };

  // Options for the borrowing trends graph
  var borrowingOptions = {
    scales: {
      y: {
        beginAtZero: true,
        precision: 0
      }
    }
  };

  // Create the borrowing trends graph
  var borrowingChart = new Chart(document.getElementById("borrowing-chart"), {
    type: "line",
    data: borrowingData,
    options: borrowingOptions
  });

  // Fetch borrowing data and update the graph periodically
  fetchBorrowingData();
  setInterval(fetchBorrowingData, 5000); // Update every 5 seconds
</script>




<script>
  // Function to fetch fine data and update the graph
  function fetchFineData() {
    // Make an AJAX request to fetch fine data
    fetch('fetch_member_fine_data.php')
      .then(response => response.json())
      .then(data => {
        // Update the graph data and options
        fineChart.data.labels = data.labels;
        fineChart.data.datasets[0].data = data.values;
        fineChart.update();
      })
      .catch(error => {
        console.error('Error fetching fine data:', error);
      });
  }

  // Data for the fine trends graph
  var fineData = {
    labels: [], // Initially empty
    datasets: [{
      label: "Fine",
      data: [], // Initially empty
      fill: false,
      borderColor: "rgb(255, 99, 132)",
      tension: 0.1
    }]
  };

  // Options for the fine trends graph
  var fineOptions = {
    scales: {
      y: {
        beginAtZero: true,
        precision: 0
      }
    }
  };

  // Create the fine trends graph
  var fineChart = new Chart(document.getElementById("fine-chart"), {
    type: "line",
    data: fineData,
    options: fineOptions
  });

  // Fetch fine data and update the graph periodically
  fetchFineData();
  setInterval(fetchFineData, 5000); // Update every 5 seconds
</script>

<h1>Department-wise Report</h1>
    <div class="container">

      <div class="chart-container">
          <canvas id="departmentPieChart"></canvas>
      </div>
      
      <div class="chart-container">
          <canvas id="departmentLineChart"></canvas>
      </div>
      
      <div class="chart-container">
          <canvas id="departmentBarChart"></canvas>
      </div>
    </div>

    <script>
        var ctxPie = document.getElementById('departmentPieChart').getContext('2d');
        var ctxLine = document.getElementById('departmentLineChart').getContext('2d');
        var ctxBar = document.getElementById('departmentBarChart').getContext('2d');

        fetch('data.php')
            .then(response => response.json())
            .then(data => {
                var labels = Object.keys(data);
                var values = Object.values(data);

                // Pie Chart
                var pieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: ['red', 'green', 'blue', 'yellow', 'purple'], // Add more colors as needed
                            borderWidth: 1
                        }]
                    }
                });

                // Line Chart
                var lineChart = new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Books Taken',
                            data: values,
                            borderColor: 'blue',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Bar Chart
                var barChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Books Taken',
                            data: values,
                            backgroundColor: 'blue',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

    </script>
</body>
</html>