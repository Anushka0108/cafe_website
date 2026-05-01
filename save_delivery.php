<?php

include("connect.php");

$type = $_POST['delivery']; 
$date = $_POST['date'] ?? NULL;
$delivery_time = $_POST['delivery_time'] ?? NULL;

if ($_POST['time'] === "now") {
    $date = date("Y-m-d");
    $delivery_time = date("H:i:s");
}

$sql = "INSERT INTO delivery (type, date, delivery_time)
        VALUES ('$type', '$date', '$delivery_time')";

if ($conn->query($sql) === TRUE) {
    header("Location: payment.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>