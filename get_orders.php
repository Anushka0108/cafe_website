<?php
$conn = new mysqli("localhost", "root", "", "cafe_db");

if ($conn->connect_error) {
    die("Connection failed");
}

$email = $_GET['email'] ?? '';

$stmt = $conn->prepare("SELECT orderid, total, payment FROM orders WHERE email=? ORDER BY orderid DESC");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);

$stmt->close();
$conn->close();
?>