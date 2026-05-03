<?php

include("connect.php");

session_start();

$type = $_POST['delivery']; 
$date = $_POST['date'] ?? NULL;
$delivery_time = $_POST['delivery_time'] ?? NULL;

if ($_POST['time'] === "now") {
    $date = date("Y-m-d");
    $delivery_time = date("H:i:s");
}

$stmt = $conn->prepare("INSERT INTO delivery (type, date, delivery_time) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $type, $date, $delivery_time);
$stmt->execute();

$deliveryid = $conn->insert_id;

header("Location: payment.html?deliveryid=$deliveryid");
exit();

$conn->close();
?>
