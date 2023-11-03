<!DOCTYPE html>
<html>
<head>
  <title>Library Statistics Dashboard</title>
  <!-- <link rel="stylesheet" href="styles.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<!-- Today's Analytics Filter Options -->
<div class="filter-options">
    <label for="chart-type-today">Select Chart Type:</label>
    <select id="chart-type-today" onchange="updateChartTypeToday()">
        <!-- <option value="line">Line Chart</option> -->
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
    </select>

    <!-- Department Filter -->
    <label for="department-today">Select Department:</label>
    <select id="department-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Departments</option>
        <option value="CSE">CSE</option>
        <option value="CSBS">CSBS</option>
        <!-- Add other departments here -->
    </select>

    <!-- Year Filter -->
    <label for="year-today">Select Year:</label>
    <select id="year-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <!-- Add other years here -->
    </select>

    <!-- Member Type Filter -->
    <label for="member-type-today">Select Member Type:</label>
    <select id="member-type-today" onchange="fetchTodayAnalyticsData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
    </select>
</div>

<div class="dashboard-card-graph">
    <h2>Today's Analytics</h2>
    <div class="graph-container">
        <canvas id="today-analytics-chart"></canvas>
    </div>
</div>

<script>
   let todayAnalyticsData = {
        labels: [],
        datasets: [{
            label: "Borrowed",
            data: [],
            backgroundColor: 'rgb(75, 192, 192)'
        },
        {
            label: "Returned",
            data: [],
            backgroundColor: 'rgb(255, 99, 132)'
        }]
    };
    let selectedChartTypeToday = 'line';
    let chartToday = null;

    // Update chart type for today's analytics
    function updateChartTypeToday() {
        selectedChartTypeToday = document.getElementById('chart-type-today').value;
        destroyChartToday();
        updateChartToday();
    }

    // Destroy the current chart instance
    function destroyChartToday() {
        if (chartToday !== null) {
            chartToday.destroy();
            chartToday = null;
        }
    }

    // Update the chart for today's analytics
    function updateChartToday() {
        const ctx = document.getElementById('today-analytics-chart').getContext('2d');

        if (selectedChartTypeToday === 'line') {
            chartToday = new Chart(ctx, {
                type: 'line',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        } else if (selectedChartTypeToday === 'bar') {
            chartToday = new Chart(ctx, {
                type: 'bar',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        } else if (selectedChartTypeToday === 'pie') {
            chartToday = new Chart(ctx, {
                type: 'pie',
                data: todayAnalyticsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                    // Additional options as needed
                }
            });
        }
    }

    function fetchTodayAnalyticsData() {
        const departmentFilter = document.getElementById('department-today').value;
        const yearFilter = document.getElementById('year-today').value;
        const memberTypeFilter = document.getElementById('member-type-today').value;

        const fetchURL = `fetch_today_analytics_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}`;

        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                todayAnalyticsData.labels = ['Today'];
                todayAnalyticsData.datasets[0].data = [data.borrowed[0]];
                todayAnalyticsData.datasets[1].data = [data.returned[0]];

                updateChartTypeToday();
            })
            .catch(error => {
                console.error("Error fetching today's analytics data:", error);
            });
    }
    // Fetch and display today's analytics data on page load
    fetchTodayAnalyticsData();
</script>


<div class="dashboard">
    <div class="filter-options">
      <label for="chart-type">Select Chart Type:</label>
      <select id="chart-type" onchange="updateChartType()">
        <option value="line">Line Chart</option>
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
      </select>

      <label for="department">Select Department:</label>
      <select id="department" onchange="fetchBooksReturnedData()">
        <option value="">All Departments</option>
        <option value="CSE">CSE</option>
        <option value="CSBS">CSBS</option>
        <!-- Add other departments here -->
      </select>

      <label for="year">Select Year:</label>
      <select id="year" onchange="fetchBooksReturnedData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <option value="3">Third Year</option>
        <option value="4">Fourth Year</option>
        <!-- Add other years here -->
      </select>

      <label for="member-type">Select Member Type:</label>
      <select id="member-type" onchange="fetchBooksReturnedData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
      </select>


      <label for="time-filter">Select Time Filter:</label>
        <select id="time-filter" onchange="updateTimeFilter()">
            <option value="">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- Weekly and Monthly filter options -->
        <div id="weekly-filter" style="display: none;">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date">

            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" onchange="updateTimeFilter()">
        </div>

        <div id="monthly-filter" style="display: none;">
            <label for="selected-month">Select Month:</label>
            <input type="month" id="selected-month" onchange="updateTimeFilter()">
        </div>
        <div id="yearly-filter" style="display: none;">
            <label for="selected-year">Select Year:</label>
            <input type="year" id="selected-year" onchange="updateTimeFilter()">
        </div>
    </div>
    <div class="dashboard-card-graph">
      <h2>Books Returned</h2>
      <div class="graph-container">
        <canvas id="books-returned-chart"></canvas>
      </div>
    </div>
  </div>

  <script>
    let selectedChartType = 'line';
    let booksReturnedData = {};
    let chart = null; // Track the current chart instance

    function updateChartType() {
      selectedChartType = document.getElementById('chart-type').value;
      destroyChart(); // Destroy the previous chart before updating
      updateChart();
    }

    function destroyChart() {
      if (chart !== null) {
        chart.destroy();
        chart = null;
      }
    }

    let chartWidth = 800;
    let chartHeight = 400;

     function updateChart() {
      const ctx = document.getElementById('books-returned-chart').getContext('2d');
      

      if (selectedChartType === 'line') {
        chart = new Chart(ctx, {
          type: 'line',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      } else if (selectedChartType === 'bar') {
        chart = new Chart(ctx, {
          type: 'bar',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      } else if (selectedChartType === 'pie') {
        chart = new Chart(ctx, {
          type: 'pie',
          data: booksReturnedData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            width: chartWidth,
            height: chartHeight
          }
        });
      }
      
    }

    function fetchBooksReturnedData() {
        const departmentFilter = document.getElementById('department').value;
        const yearFilter = document.getElementById('year').value;
        const memberTypeFilter = document.getElementById('member-type').value;
        const timeFilter = document.getElementById('time-filter').value;

        let fetchURL = `fetch_books_returned_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}&timeFilter=${timeFilter}`;

        if (timeFilter === 'weekly') {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            fetchURL += `&startDate=${startDate}&endDate=${endDate}`;
        } else if (timeFilter === 'monthly') {
            const selectedMonth = document.getElementById('selected-month').value;
            fetchURL += `&selectedMonth=${selectedMonth}`;
        } else if (timeFilter === 'yearly') {
            const selectedYear = document.getElementById('selected-year').value;
            fetchURL += `&selectedYear=${selectedYear}`;
        }
console.log(fetchURL)
        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                booksReturnedData = {
                    labels: data.labels,
                    datasets: [{
                        label: 'Books Returned',
                        data: data.values,
                        backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)']
                    }]
                };

                updateChartType();
            })
            .catch(error => {
                console.error('Error fetching books returned data:', error);
            });
    }
   

    function updateTimeFilter() {
        const selectedTimeFilter = document.getElementById('time-filter').value;
        const weeklyFilter = document.getElementById('weekly-filter');
        const monthlyFilter = document.getElementById('monthly-filter');
        const yearlyFilter = document.getElementById('yearly-filter');

        if (selectedTimeFilter === 'weekly') {
            weeklyFilter.style.display = 'block';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'monthly') {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'block';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'yearly') {
            yearlyFilter.style.display = 'block';
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
        } else {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        }

        fetchBooksReturnedData();
    }
    fetchBooksReturnedData();



  </script>





<div class="dashboard">
    <div class="filter-options">
      <label for="chart-type-borrowed">Select Chart Type:</label>
      <select id="chart-type-borrowed" onchange="updateChartTypeBorrowed()">
        <option value="line">Line Chart</option>
        <option value="bar">Bar Chart</option>
        <option value="pie">Pie Chart</option>
      </select>

      <label for="department-borrowed">Select Department:</label>
      <select id="department-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Departments</option>
        <option value="CSE">CSE</option>
        <option value="CSBS">CSBS</option>
        <!-- Add other departments here -->
      </select>

      <label for="year-borrowed">Select Year:</label>
      <select id="year-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Years</option>
        <option value="1">First Year</option>
        <option value="2">Second Year</option>
        <!-- Add other years here -->
      </select>

      <label for="member-type-borrowed">Select Member Type:</label>
      <select id="member-type-borrowed" onchange="fetchBooksBorrowedData()">
        <option value="">All Member Types</option>
        <option value="student">Student</option>
        <option value="staff">Staff</option>
      </select>

      
        <!-- Books Borrowed Time Filter -->
        <label for="time-filter-borrowed">Select Time Filter:</label>
        <select id="time-filter-borrowed" onchange="updateTimeFilterBorrowed()">
            <option value="">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- Weekly and Monthly filter options for Books Borrowed -->
        <div id="weekly-filter-borrowed" style="display: none;">
            <label for="start-date-borrowed">Start Date:</label>
            <input type="date" id="start-date-borrowed">

            <label for="end-date-borrowed">End Date:</label>
            <input type="date" id="end-date-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>

        <div id="monthly-filter-borrowed" style="display: none;">
            <label for="selected-month-borrowed">Select Month:</label>
            <input type="month" id="selected-month-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>

        <div id="yearly-filter-borrowed" style="display: none;">
            <label for="selected-year-borrowed">Select Year:</label>
            <input type="year" id="selected-year-borrowed" onchange="updateTimeFilterBorrowed()">
        </div>
    </div>
    <div class="dashboard-card-graph">
        <h2>Books Borrowed</h2>
        <div class="graph-container">
            <canvas id="books-borrowed-chart"></canvas>
        </div>
    </div>
  </div>

<script>
    let selectedChartTypeBorrowed = 'line';
    let booksBorrowedData = {};
    let chartBorrowed = null;

    function updateChartTypeBorrowed() {
        selectedChartTypeBorrowed = document.getElementById('chart-type-borrowed').value;
        destroyChartBorrowed();
        updateChartBorrowed();
    }

    function destroyChartBorrowed() {
        if (chartBorrowed !== null) {
            chartBorrowed.destroy();
            chartBorrowed = null;
        }
    }

    function updateChartBorrowed() {
        const ctx = document.getElementById('books-borrowed-chart').getContext('2d');

        if (selectedChartTypeBorrowed === 'line') {
            chartBorrowed = new Chart(ctx, {
                type: 'line',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        } else if (selectedChartTypeBorrowed === 'bar') {
            chartBorrowed = new Chart(ctx, {
                type: 'bar',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        } else if (selectedChartTypeBorrowed === 'pie') {
            chartBorrowed = new Chart(ctx, {
                type: 'pie',
                data: booksBorrowedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    width: chartWidth,
                    height: chartHeight
                }
            });
        }
    }
    function fetchBooksBorrowedData() {
        const departmentFilter = document.getElementById('department-borrowed').value;
        const yearFilter = document.getElementById('year-borrowed').value;
        const memberTypeFilter = document.getElementById('member-type-borrowed').value;
        const timeFilter = document.getElementById('time-filter-borrowed').value;

        let fetchURL = `fetch_books_borrowed_data.php?department=${departmentFilter}&year=${yearFilter}&memberType=${memberTypeFilter}&timeFilter=${timeFilter}`;

        if (timeFilter === 'weekly') {
            const startDate = document.getElementById('start-date-borrowed').value;
            const endDate = document.getElementById('end-date-borrowed').value;
            fetchURL += `&startDate=${startDate}&endDate=${endDate}`;
        } else if (timeFilter === 'monthly') {
            const selectedMonth = document.getElementById('selected-month-borrowed').value;
            fetchURL += `&selectedMonth=${selectedMonth}`;
        } else if (timeFilter === 'yearly') {
            const selectedYear = document.getElementById('selected-year-borrowed').value;
            fetchURL += `&selectedYear=${selectedYear}`;
        }

        fetch(fetchURL)
            .then(response => response.json())
            .then(data => {
                booksBorrowedData = {
                    labels: data.labels,
                    datasets: [{
                        label: 'Books Borrowed',
                        data: data.values,
                        backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)']
                    }]
                };

                updateChartTypeBorrowed();
            })
            .catch(error => {
                console.error('Error fetching books borrowed data:', error);
            });
    }

    // Update Time Filter for Books Borrowed
    function updateTimeFilterBorrowed() {
        const selectedTimeFilter = document.getElementById('time-filter-borrowed').value;
        const weeklyFilter = document.getElementById('weekly-filter-borrowed');
        const monthlyFilter = document.getElementById('monthly-filter-borrowed');
        const yearlyFilter = document.getElementById('yearly-filter-borrowed');

        if (selectedTimeFilter === 'weekly') {
            weeklyFilter.style.display = 'block';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'monthly') {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'block';
            yearlyFilter.style.display = 'none';
        } else if (selectedTimeFilter === 'yearly') {
            yearlyFilter.style.display = 'block';
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
        } else {
            weeklyFilter.style.display = 'none';
            monthlyFilter.style.display = 'none';
            yearlyFilter.style.display = 'none';
        }

        fetchBooksBorrowedData();
    }

    fetchBooksBorrowedData();
</script>
</body>
</html>
