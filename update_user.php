<?php
$conn = new mysqli("localhost", "root", "", "cafe_db");

if ($conn->connect_error) {
    die("Connection failed");
}

$email = $_POST['email'];
$name = $_POST['full_name'];
$address = $_POST['address'];
$password = $_POST['password'];

$stmt = $conn->prepare("UPDATE users SET full_name=?, address=?, password=? WHERE email=?");
$stmt->bind_param("ssss", $name, $address, $password, $email);

if ($stmt->execute()) {
    echo "User updated successfully";
} else {
    echo "Error updating user";
}

$stmt->close();
$conn->close();
?>