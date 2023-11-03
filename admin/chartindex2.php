<!DOCTYPE html>
<html>
<head>
  <title>Today's Analytics</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h2>Today's Analytics</h2>
  <div class="graph-container">
    <canvas id="today-analytics-chart"></canvas>
  </div>

  <script>
    // Sample static data for demonstration
    const todayAnalyticsData = {
      labels: ['Borrowed', 'Returned'],
      values: [10, 5], // Replace with your actual data
    };

    const ctx = document.getElementById('today-analytics-chart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar', // Use 'bar' chart type for this example
      data: {
        labels: todayAnalyticsData.labels,
        datasets: [{
          label: "Today's Analytics",
          data: todayAnalyticsData.values,
          backgroundColor: [
            'rgb(75, 192, 192)',
            'rgb(255, 99, 132)',
          ],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  </script>
</body>
</html>
