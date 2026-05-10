<?php
include("connect.php");

if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];

    while ($order = $result->fetch_assoc()) {

        $itemStmt = $conn->prepare("
            SELECT m.itemname, od.quantity 
            FROM order_details od
            JOIN menuitems m ON od.mid = m.mid
            WHERE od.orderid = ?
        ");
        $itemStmt->bind_param("i", $order['orderid']);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();

        $items = [];
        while ($item = $itemResult->fetch_assoc()) {
            $items[] = $item;
        }

        $order['items'] = $items;

        $orders[] = $order;

        $itemStmt->close();
    }

    echo json_encode($orders);

} else {
    echo json_encode([]);
}
?>