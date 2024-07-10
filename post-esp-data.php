<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id22317235_esp_data";
// REPLACE with Database user
$username = "id22317235_esp_board";
// REPLACE with Database user password
$password = "@Z33m2011";

$api_key_value = "fdc7d98d-ef4c-433f-bcc4-3f86ae34a96f";

$api_key= $sensor = $location = $humvalue = $temvalue = $disvalue = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
   $api_key = test_input($_POST["api_key"]);
   if($api_key == $api_key_value) 
   {
      $sensor = test_input($_POST["sensor"]);
      $location = test_input($_POST["location"]);
      $humvalue = test_input($_POST["humvalue"]);
      $temvalue = test_input($_POST["temvalue"]);
      $disvalue = test_input($_POST["disvalue"]);

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
         die("Connection failed: " . $conn->connect_error);
      }

      $sql = "INSERT INTO SensorData (sensor, location, humvalue, temvalue, disvalue)
      VALUES ('" . $sensor . "', '" . $location . "', '" . $humvalue . "', '" . $temvalue . "', '" . $disvalue . "')";
        
      if ($conn->query($sql) === TRUE) 
      {
         echo "New record created successfully";
      } 

      else 
      {
         echo "Error: " . $sql . "<br>" . $conn->error;
      }
    
      $conn->close();
   }
    
   else 
   {
      echo "Wrong API Key provided.";
   }
}

else 
{
   echo "No data posted with HTTP POST.";
}

function test_input($data) 
{
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>

