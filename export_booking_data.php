<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db = "carrental";  // your actual DB name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filename = "/opt/lampp/htdocs/online-car-rental/Online Car Rental/booking_data.csv";

$file = fopen($filename, "w");
if (!$file) {
    die("Cannot open file ($filename)");
}

fputcsv($file, ["car_id", "days", "total_amount"]);

$query = "
SELECT 
    VehicleId AS car_id,
    DATEDIFF(STR_TO_DATE(ToDate, '%Y-%m-%d'), STR_TO_DATE(FromDate, '%Y-%m-%d')) AS days
FROM tblbooking
WHERE Status = 1
";

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $car_id = $row['car_id'];
    $days = (int)$row['days'];
    if ($days <= 0) continue; // skip invalid durations

    $total_price = $days * 100; // Placeholder price: 100 per day

    fputcsv($file, [$car_id, $days, $total_price]);
}

fclose($file);

echo "Exported to booking_data.csv";
?>
