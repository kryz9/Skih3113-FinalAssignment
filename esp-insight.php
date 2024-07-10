<?php
$servername = "localhost";
$dbname = "id22317235_esp_data";
$username = "id22317235_esp_board";
$password = "@Z33m2011";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT humvalue, temvalue, disvalue FROM SensorData ORDER BY id DESC LIMIT 100";
$result = $conn->query($sql);

$humvalues = [];
$temvalues = [];
$disvalues = [];

while ($data = $result->fetch_assoc()){
    $humvalues[] = $data['humvalue'];
    $temvalues[] = $data['temvalue'];
    $disvalues[] = $data['disvalue'];
}

function calculate_stats($values) {
    $average = array_sum($values) / count($values);
    $min = min($values);
    $max = max($values);
    return [$average, $min, $max];
}

list($avgHum, $minHum, $maxHum) = calculate_stats($humvalues);
list($avgTemp, $minTemp, $maxTemp) = calculate_stats($temvalues);
list($avgDis, $minDis, $maxDis) = calculate_stats($disvalues);

$conn->close();
?>

<div class="modal-content">
  <h2>ESP Insights</h2>
  <h3>Temperature</h3>
  <p>Average: <?php echo $avgTemp; ?> °C</p>
  <p>Min: <?php echo $minTemp; ?> °C</p>
  <p>Max: <?php echo $maxTemp; ?> °C</p>
  <h3>Humidity</h3>
  <p>Average: <?php echo $avgHum; ?> %</p>
  <p>Min: <?php echo $minHum; ?> %</p>
  <p>Max: <?php echo $maxHum; ?> %</p>
  <h3>Distance</h3>
  <p>Average: <?php echo $avgDis; ?> cm</p>
  <p>Min: <?php echo $minDis; ?> cm</p>
  <p>Max: <?php echo $maxDis; ?> cm</p>
</div>

<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 60px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 5% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("insightsBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
