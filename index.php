<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        th {
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
        }

        .pagination li {
            list-style-type: none;
            margin: 0 5px;
        }

        .pagination a {
            color: #000;
            text-decoration: none;
        }
    </style>
</head>

<body>
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

    // Get the total number of records
    $total_sql = "SELECT COUNT(*) FROM SensorData";
    $total_result = $conn->query($total_sql);
    $total_row = $total_result->fetch_row();
    $total_records = $total_row[0];

    // Define how many results you want per page
    $results_per_page = 10;

    // Determine the total number of pages available
    $total_pages = ceil($total_records / $results_per_page);

    // Find out the current page number
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    // Calculate the starting limit number
    $start_limit = ($page - 1) * $results_per_page;

    $sql = "SELECT id, sensor, location, humvalue, temvalue, disvalue, reading_time FROM SensorData ORDER BY id DESC LIMIT " . $start_limit . ", " . $results_per_page;

    echo '<div class="container py-5">
      <div class="row py-5">
        <div class="col-lg-10 mx-auto">
            <div class="card rounded shadow border-0">
                <div class="card-body p-5 bg-white rounded">
                    <div class="table-responsive">
                        <table id="example" style="width:100%" class="table table-striped table-bordered table-hover">
                              <thead>
                              <tr> 
                                <th onclick="sortTable(0)">ID</th>
                                <th onclick="sortTable(1)">Sensor</th> 
                                <th onclick="sortTable(2)">Location</th> 
                                <th onclick="sortTable(3)">Humidity</th> 
                                <th onclick="sortTable(4)">Temperature</th>
                                <th onclick="sortTable(5)">Distance</th> 
                                <th onclick="sortTable(6)">Timestamp</th> 
                              </tr>
                              </thead>';

    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $row_id = $row["id"];
            $row_sensor = $row["sensor"];
            $row_location = $row["location"];
            $row_humvalue = $row["humvalue"];
            $row_temvalue = $row["temvalue"];
            $row_disvalue = $row["disvalue"];
            $row_reading_time = $row["reading_time"];

            // Adjust timezone and format the date to desired format
            $row_reading_time = date("h:iA l d-m-Y", strtotime("$row_reading_time + 8 hours"));

            echo '<tbody>
              <tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_sensor . '</td> 
                <td>' . $row_location . '</td> 
                <td>Humidity = ' . number_format($row_humvalue, 2) . '%</td> 
                <td>Temperature = ' . number_format($row_temvalue, 2) . ' °C</td>
                <td>Distance = ' . number_format($row_disvalue, 2) . ' cm</td> 
                <td>' . $row_reading_time . '</td> 
              </tr>';
        }
        $result->free();
    }

    $conn->close();
    ?>
    </tbody>
    </table>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#espChartModal">Show ESP Chart</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#espInsightModal">Show ESP Insight</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#espGaugeModal">Show ESP Gauge Sensors</button>
    <p>
        
        
    </p>
    
</div>
<nav aria-label="Page navigation example">
<ul class="pagination justify-content-center">
    <?php
    // Previous Button
    if ($page > 1) {
        echo '<li class="page-link"><a href="index.php?page=' . ($page - 1) . '">Previous</a></li>';
    }

    // Display the links to the pages
    for ($p = 1; $p <= $total_pages; $p++) {
        if ($p == $page) {
            echo '<li class="page-item"><strong>' . $p . '</strong></li>';
        } else {
            echo '<li class="page-item"><a href="index.php?page=' . $p . '">' . $p . '</a></li>';
        }
    }

    // Next Button
    if ($page < $total_pages) {
        echo '<li class="page-link"><a href="index.php?page=' . ($page + 1) . '">Next</a></li>';
    }
    ?>
</ul>
</nav>
</div>
</div>
</div>
</div>
</div>

<!-- Modal for Chart -->
<div class="modal fade" id="espChartModal" tabindex="-1" aria-labelledby="espChartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="espChartModalLabel">ESP Chart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="espChartIframe" src="esp-chart.php" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal for Insight -->
<div class="modal fade" id="espInsightModal" tabindex="-1" aria-labelledby="espInsightModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="espInsightModalLabel">ESP Insight</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="espChartIframe" src="esp-insight.php" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Gauge -->
<div class="modal fade" id="espGaugeModal" tabindex="-1" aria-labelledby="espGaugeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="espGaugeModalLabel">ESP Gauge Sensors</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <iframe id="espChartIframe" src="esp-gauge.php" style="width:100%; height:500px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("example");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
