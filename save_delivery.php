<?php
session_start();
include("connect.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: Sign_Up.html");
    exit();
}

$type = $_POST['delivery'];
$time = $_POST['time'];

$date = $_POST['date'] ?? null;
$delivery_time = $_POST['delivery_time'] ?? null;

if ($time === "now") {
    $date = date("Y-m-d");
    $delivery_time = date("H:i:s");
}

$email = $_SESSION['user']['email'];

$stmtUser = $conn->prepare("SELECT address FROM users WHERE email = ?");
$stmtUser->bind_param("s", $email);
$stmtUser->execute();
$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

$address = $user['address'] ?? null;

if ($type === "pickup") {
    $address = $_POST['location'] ?? null;  // Use the location for pickup
}

$stmt = $conn->prepare("INSERT INTO delivery (type, date, delivery_time, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $type, $date, $delivery_time, $address);

if (!$stmt->execute()) {
    die($stmt->error);
}

$deliveryid = $conn->insert_id;

header("Location: payment.html?deliveryid=$deliveryid");
exit();

$conn->close();
?>