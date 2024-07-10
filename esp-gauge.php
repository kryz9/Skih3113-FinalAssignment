<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id22317235_esp_data";
// REPLACE with Database user
$username = "id22317235_esp_board";
// REPLACE with Database user password
$password = "@Z33m2011";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Define the SQL query to retrieve the latest sensor readings
$sql = "SELECT humvalue, temvalue, disvalue FROM SensorData ORDER BY id DESC LIMIT 1";

$result = $conn->query($sql);

$dataFound = false;
$humvalue = null;
$temvalue = null;
$disvalue = null;

// Check if there is a result
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();

  $humvalue = $row["humvalue"];
  $temvalue = $row["temvalue"];
  $disvalue = $row["disvalue"];
  $dataFound = true;
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ESP Sensor Gauges</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/gauge.js@1.4.0/dist/gauge.min.js"></script>
  <style>
    .gauge-container {
      display: flex;
      justify-content: space-around;
      margin-top: 50px;
    }

    .gauge {
      width: 200px;
      height: 200px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>ESP Sensor Gauges</h1>

    <?php if ($dataFound) : ?>
      <div class="gauge-container">
        <div class="gauge" id="gauge-humidity"></div>
        <div class="gauge" id="gauge-temperature"></div>
        <div class="gauge" id="gauge-distance"></div>
      </div>
    <?php else : ?>
      <p class="alert alert-warning">No sensor data available yet.</p>
    <?php endif; ?>

  </div>

  <script>
    <?php if ($dataFound) : ?>
      // Initialize gauges with specific configurations
      var gaugeHumidity = new Gauge({
        element: document.getElementById('gauge-humidity'),
        value: <?php echo $humvalue; ?>, // Set initial value from PHP
        label: 'Humidity (%)',
        min: 0,
        max: 100,
        dial: {
          bgColor: 'gray',
          fgColor: '#007bff' // Adjust color as needed
        }
      });

      var gaugeTemperature = new Gauge({
        element: document.getElementById('gauge-temperature'),
        value: <?php echo $temvalue; ?>, // Set initial value from PHP
        label: 'Temperature (°C)',
        min: 0,
        max: 50, // Adjust max value based on expected temperature range
        dial: {
          bgColor: 'gray',
          fgColor: '#dc3545' // Adjust color as needed
        }
      });

      var gaugeDistance = new Gauge({
        element: document.getElementById('gauge-distance'),
        value: <?php echo $disvalue; ?>, // Set initial value from PHP
        label: 'Distance (cm)',
        min: 0,
        max: 200, // Adjust max value based on expected distance range
        dial: {
          bgColor: 'gray',
          fgColor: '#28a745' // Adjust color as needed
        }
      });
    <?php endif; ?>

    // Add functionality to update gauges dynamically (e.g., using AJAX)
    function updateGauges() {
      // Implement logic to fetch latest sensor readings using AJAX
      // Update gauge values and redraw gauges using Gauge.js methods
    }

    // Call updateGauges initially and set an interval for periodic updates
    updateGauges();
    setInterval(updateGauges, 5000); // Update every 5 seconds (adjust as needed)
  </script>
</body>

</html>