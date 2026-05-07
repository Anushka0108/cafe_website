<?php
$conn = new mysqli("localhost", "root", "", "cafe_db");

if ($conn->connect_error) {
    die("Connection failed");
}

$email = $_GET['email'];

$newPassword = "123456";

$stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss", $newPassword, $email);

if ($stmt->execute()) {
    echo "Password reset to 123456";
} else {
    echo "Error";
}

$stmt->close();
$conn->close();
?>