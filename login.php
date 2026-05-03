<?php
include "connect.php";

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email == '' || $password == '') {
    echo "missing";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo "success";
    } else {
        echo "invalid";
    }

} else {
    echo "invalid";
}
?>