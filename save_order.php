<?php
include("connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1); 

header('Content-Type: application/json');

// --------------------
// GET JSON DATA
// --------------------
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "No data received"]);
    exit();
}

$items = $data['items'] ?? [];
$total = (float) $data['total'];
$payment = $data['payment'] ?? '';
$deliveryid = $data['deliveryid'] ?? null;

session_start();
if (!isset($_SESSION['user']['email'])) {
    echo json_encode(["error" => "Login required"]);
    exit();
}
$email = $_SESSION['user']['email'];

if (!$payment || !$deliveryid) {
    echo json_encode(["error" => "Missing payment or deliveryid"]);
    exit();
}

// --------------------
// GET OR CREATE PAYMENT ID
// --------------------
$payment = strtolower($data['payment']);

$stmt = $conn->prepare("SELECT pid FROM payment WHERE LOWER(paymenttype)=LOWER(?)");
$stmt->bind_param("s", $payment);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if ($row) {
    $pid = $row['pid'];
} else {
    $stmt = $conn->prepare("INSERT INTO payment (paymenttype) VALUES (?)");
    $stmt->bind_param("s", $data['payment']);
    $stmt->execute();
    $pid = $stmt->insert_id;
}

// --------------------
// INSERT ORDER
// --------------------
$stmt = $conn->prepare("INSERT INTO orders (email, paymentid, deliveryid, total) VALUES (?, ?, ?, ?)"); 
$stmt->bind_param("siid", $email, $pid, $deliveryid, $total);

if (!$stmt->execute()) { // ✅ ADDED ERROR CHECK
    echo json_encode(["error" => $stmt->error]);
    exit();
}

$orderid = $stmt->insert_id;

// --------------------
// INSERT ORDER DETAILS
// --------------------
foreach ($items as $item) {
    $itemName = $item['name'] ?? '';
    
    if (empty($itemName)) continue;

    // Get menu_id (mid) from menuitems table by item name
    $stmt2 = $conn->prepare("SELECT mid FROM menuitems WHERE LOWER(itemname) = LOWER(?)"); 
    $stmt2->bind_param("s", $itemName);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $row2 = $res2->fetch_assoc();
    
    $mid = $row2['mid'] ?? 0;
    if ($mid === 0) continue;

    $qty = (int)($item['quantity'] ?? 1);
    $size = $item['size'] ?? '';
    $milk = $item['milk'] ?? '';
    $sugar = $item['sugar'] ?? '';
    $toppings = isset($item['toppings']) ? implode(",", $item['toppings']) : '';
    $extras = isset($item['extras']) ? implode(",", $item['extras']) : '';

    $stmt3 = $conn->prepare("
        INSERT INTO order_details 
        (orderid, mid, quantity, size, milk, sugar, toppings, extras)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt3->bind_param(
        "iiisssss",
        $orderid,
        $mid,
        $qty,
        $size,
        $milk,
        $sugar,
        $toppings,
        $extras
    );

    $stmt3->execute();
}

// --------------------
// RETURN SUCCESS
// --------------------
echo json_encode([
    "success" => true,
    "orderid" => $orderid,
    "total" => $total,
    "payment" => $payment,
    "items_count" => count($items)
]);