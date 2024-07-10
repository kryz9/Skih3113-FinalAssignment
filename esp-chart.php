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

$sql = "SELECT id, sensor, location, humvalue, temvalue, disvalue, reading_time FROM SensorData ORDER BY id DESC LIMIT 100";

$result = $conn->query($sql);

$sensor_data = []; // Initialize the array

while ($data = $result->fetch_assoc()){
    // Adjust reading_time and format it
    $data['reading_time'] = date("h:iA l d-m-Y", strtotime($data['reading_time'] . ' + 8 hours'));
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');

$humvalue = json_encode(array_reverse(array_column($sensor_data, 'humvalue')), JSON_NUMERIC_CHECK);
$temvalue = json_encode(array_reverse(array_column($sensor_data, 'temvalue')), JSON_NUMERIC_CHECK);
$disvalue = json_encode(array_reverse(array_column($sensor_data, 'disvalue')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time)); // No need for JSON_NUMERIC_CHECK here

$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
      max-width: 1280px;
      height: 500px;
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  <body>
    <h2>ESP Weather Station</h2>
    <div id="chart-temperature" class="container"></div>
    <div id="chart-humidity" class="container"></div>
    <div id="chart-pressure" class="container"></div>

<script>

var humvalue = <?php echo $humvalue; ?>;
var temvalue = <?php echo $temvalue; ?>;
var disvalue = <?php echo $disvalue; ?>;
var reading_time = <?php echo $reading_time; ?>;

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-temperature' },
  title: { text: 'ESP8266 Temperature' },
  series: [{
    showInLegend: false,
    data: temvalue // Corrected variable name
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Temperature (Celsius)' }
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity' },
  title: { text: 'ESP8266 Humidity' },
  series: [{
    showInLegend: false,
    data: humvalue // Corrected variable name
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Humidity (%)' }
  },
  credits: { enabled: false }
});


var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart-pressure' },
  title: { text: 'ESP8266 Distance' },
  series: [{
    showInLegend: false,
    data: disvalue // Corrected variable name
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#18009c' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Distance (cM)' }
  },
  credits: { enabled: false }
});

</script>
</body>
</html>
