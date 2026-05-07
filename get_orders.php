<?php
include("connect.php");

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Query to fetch orders based on the user's email
    $stmt = $conn->prepare("SELECT * FROM orders WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($order = $result->fetch_assoc()) {
        $orders[] = $order;
    }

    // Return orders as a JSON response
    echo json_encode($orders);
} else {
    echo json_encode([]); // Return an empty array if no email is provided
}
?>