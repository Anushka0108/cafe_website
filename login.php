<?php
session_start();
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
        $_SESSION['user'] = $user;
        session_regenerate_id(true);
        $is_admin = ($_SESSION['user']['email'] === 'admin@gmail.com');
        $redirect_url = $is_admin ? 'admin\admin.php' : 'main_page.php';
        echo json_encode([
            'status' => 'success',
            'is_admin' => $is_admin,
            'redirect_url' => $redirect_url
        ]);
    } else {
        echo "invalid";
    }

} else {
    echo "invalid";
}
?>
