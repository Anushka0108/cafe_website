<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$orderid = $_GET['orderid'] ?? null;

if (!$orderid) {
    header("Location: user.php");
    exit();
}

$email = $_SESSION['user']['email'];

$stmt = $conn->prepare("
    SELECT m.itemname, m.price, od.quantity
    FROM order_details od
    JOIN menuitems m ON od.mid = m.mid
    JOIN orders o ON od.orderid = o.orderid
    WHERE od.orderid = ? AND o.email = ?
");
$stmt->bind_param("is", $orderid, $email);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = [
    "name" => $row['itemname'],
    "price" => $row['price'],
    "orgprice" => $row['price'],
    "quantity" => $row['quantity']
];
}
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        let items = <?php echo json_encode($items); ?>;

        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        items.forEach(item => {
            let existing = cart.find(c => c.name === item.name);

            if (existing) {
                existing.quantity += item.quantity;
            } else {
                cart.push(item);
            }
        });

        localStorage.setItem("cart", JSON.stringify(cart));

        window.location.href = "cart.php";
    </script>
</head>
<body></body>
</html>