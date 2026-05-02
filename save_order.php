<?php
include("connect.php");

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

if (!$payment || !$deliveryid) {
    echo json_encode(["error" => "Missing payment or deliveryid"]);
    exit();
}

// --------------------
// FIX PAYMENT MATCH (SAFE)
// --------------------
$payment = strtolower($data['payment']);

$stmt = $conn->prepare("SELECT pid FROM payment WHERE LOWER(paymenttype)=LOWER(?)");
$stmt->bind_param("s", $payment);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

$pid = $row['pid'] ?? null;

// 🔥 DEBUG (TEMP ONLY)
echo json_encode([
  "payment_received" => $payment,
  "pid" => $pid
]);
exit;
// --------------------
// INSERT ORDER
// --------------------
$stmt = $conn->prepare("INSERT INTO orders (pid, deliveryid, total) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $pid, $deliveryid, $total);
$stmt->execute();

$orderid = $stmt->insert_id;

// --------------------
// INSERT ORDER ITEMS
// --------------------
foreach ($items as $item) {

    $name = $item['name'] ?? '';
    $qty = (int)($item['quantity'] ?? 1);

    // get menu id
    $stmt2 = $conn->prepare("SELECT mid FROM menuitems WHERE itemname=?");
    $stmt2->bind_param("s", $name);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $row2 = $res2->fetch_assoc();

    if (!$row2) continue;

    $mid = (int)$row2['mid'];

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
// RESPONSE
// --------------------
echo json_encode([
    "status" => "success",
    "orderid" => $orderid
]);
?>