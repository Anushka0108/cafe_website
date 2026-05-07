<?php
$conn = new mysqli("localhost", "root", "", "cafe_db");

if ($conn->connect_error) {
    die("Connection failed");
}

$sql = "SELECT full_name, email, address, password FROM users";
$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();
?>