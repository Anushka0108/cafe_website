<?php
include "connect.php";

$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if ($full_name == '' || $email == '' || $password == '') {
    echo "MISSING DATA";
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// check existing user
$check = $conn->prepare("SELECT email FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "EMAIL EXISTS";
    exit();
}

// insert
$stmt = $conn->prepare("INSERT INTO users (email, full_name, address, phone_number, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $email, $full_name, $address, $phone, $hashedPassword);

if ($stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "ERROR";
}
?>