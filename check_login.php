<?php
session_start();
header('Content-Type: application/json');

include "connect.php";

if (isset($_SESSION['user']) && isset($_SESSION['user']['email'])) {
    // Verify session user still exists in DB (optional security)
    $email = $_SESSION['user']['email'];
    $stmt = $conn->prepare("SELECT email, full_name, address, phone_number FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            "logged_in" => true,
            "user" => $user
        ]);
    } else {
        // Session invalid, destroy
        session_destroy();
        echo json_encode(["logged_in" => false]);
    }
} else {
    echo json_encode(["logged_in" => false]);
}
?>

