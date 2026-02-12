<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db = "carrental";  // Your DB name here

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = "predicted_prices.csv";
$file = fopen($filename, "r");

if (!$file) {
    die("Failed to open file: $filename");
}

// Skip header row
fgetcsv($file);

while (($data = fgetcsv($file)) !== FALSE) {
    $car_id = $data[0];
    $price = $data[1];
    // Update dynamic_price for the vehicle
    $conn->query("UPDATE tblvehicles SET dynamic_price = $price WHERE id = $car_id");
}

fclose($file);
echo "Imported dynamic prices to MySQL successfully.";
?>
